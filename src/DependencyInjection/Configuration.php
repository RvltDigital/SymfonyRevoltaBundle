<?php

namespace RvltDigital\SymfonyRevoltaBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{

    /**
     * Generates the configuration tree builder.
     *
     * @return TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder('rvlt_digital_symfony_revolta');

        $rootNode = $builder->getRootNode();
        assert($rootNode instanceof ArrayNodeDefinition);

        $rootNode
            ->children()
                ->enumNode('change_tracking_policy')->defaultNull()->values(['implicit', 'explicit', 'notify', null])->end()
            ->end();

        return $builder;
    }
}
