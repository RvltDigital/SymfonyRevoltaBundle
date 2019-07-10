<?php

namespace RvltDigital\SymfonyRevoltaBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Yaml\Yaml;

class RvltDigitalSymfonyRevoltaExtension extends Extension implements PrependExtensionInterface
{

    /**
     * Loads a specific configuration.
     *
     * @param array $configs
     * @param ContainerBuilder $container
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../config'));
        $loader->load('services.yaml');

        $configs = $this->processConfiguration(new Configuration(), $configs);

        $container->setParameter('rvlt_digital_revolta.change_tracking_policy', $configs['change_tracking_policy'] ?? null);
        $container->setParameter('rvlt_digital_revolta.mailer', $configs['mailer'] ?? []);
        $container->setParameter('rvlt_digital.internal.revolta.slack_config', $configs['slack_logging'] ?? []);
    }

    /**
     * Allow an extension to prepend the extension configurations.
     * @param ContainerBuilder $container
     */
    public function prepend(ContainerBuilder $container)
    {
        $configs = $container->getExtensionConfig($this->getAlias());
        $configs = $this->processConfiguration(new Configuration(), $configs);

        $this->prependMonolog($configs, $container);
    }

    private function prependMonolog(array $configs, ContainerBuilder $container)
    {
        if (!$configs['slack_logging']['enabled']) {
            return;
        }

        $monologConfig = Yaml::parse(file_get_contents(__DIR__ . '/../config/monolog.yaml'));
        if (!isset($monologConfig['monolog'])) {
            throw new \LogicException("The monolog config file must have a top-level 'monolog' key");
        }

        $container->prependExtensionConfig('monolog', $monologConfig['monolog']);
    }
}
