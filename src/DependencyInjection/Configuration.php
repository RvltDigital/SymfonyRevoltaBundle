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
            ->addDefaultsIfNotSet()
            ->children()
                ->enumNode('change_tracking_policy')
                    ->defaultNull()
                    ->values(['implicit', 'explicit', 'notify', null])
                ->end()
                ->arrayNode('slack_logging')
                    ->addDefaultsIfNotSet()
                    ->info('Logging errors to Slack')
                    ->children()
                        ->booleanNode('enabled')
                            ->info('Whether the Slack logging is enabled or not')
                            ->defaultFalse()
                        ->end()
                        ->scalarNode('token')
                            ->info('The Slack token used for connecting to Slack, should be stored in env variable')
                            ->defaultValue('%env(SLACK_API_KEY)%')
                        ->end()
                        ->enumNode('level')
                            ->info('The minimum level to log messages')
                            ->values([
                                'debug',
                                'info',
                                'notice',
                                'warning',
                                'error',
                                'critical',
                                'alert',
                                'emergency',
                            ])
                            ->defaultValue('warning')
                        ->end()
                        ->scalarNode('channel')
                            ->info('The channel this bot will log the messages to')
                            ->defaultValue('#errors')
                        ->end()
                        ->scalarNode('bot_name')
                            ->info('The bot name')
                            ->defaultValue('Error Logger')
                        ->end()
                        ->scalarNode('icon_emoji')
                            ->info('The emoji used by the bot as a profile image')
                            ->defaultValue(':poop:')
                        ->end()
                        ->booleanNode('include_extra')
                            ->info('Whether to include extras (context etc.)')
                            ->defaultTrue()
                        ->end()
                        ->booleanNode('use_attachment')
                            ->info('Whether to send large messages as attachments')
                            ->defaultTrue()
                        ->end()
                        ->booleanNode('include_stacktraces')
                            ->info('Whether to include stacktrace in message')
                            ->defaultTrue()
                        ->end()
                        ->arrayNode('excluded_404')
                            ->info('List of regular expressions that won\'t trigger error on 404')
                            ->scalarPrototype()->end()
                        ->end()
                        ->booleanNode('exclude_all_404')
                            ->info('Whether to exclude all 404 errors from logging')
                            ->defaultFalse()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $builder;
    }
}
