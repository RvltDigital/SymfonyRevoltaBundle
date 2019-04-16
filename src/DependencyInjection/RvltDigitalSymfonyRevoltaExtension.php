<?php

namespace RvltDigital\SymfonyRevoltaBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class RvltDigitalSymfonyRevoltaExtension extends Extension
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
    }
}
