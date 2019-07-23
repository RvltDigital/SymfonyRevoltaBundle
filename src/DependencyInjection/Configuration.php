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
                ->arrayNode('postgres_schemas')
                    ->info('List of postgres schemas to use with PostgresSchemaDriver')
                    ->scalarPrototype()->end()
                ->end()
                ->arrayNode('mailer')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('default_name')
                            ->info('Default sender name')
                            ->defaultNull()
                        ->end()
                        ->scalarNode('default_email')
                            ->info('Default sender email')
                            ->defaultNull()
                        ->end()
                        ->scalarNode('default_reply_to_email')
                            ->info('The default Reply-To address, leave null to ignore')
                            ->defaultNull()
                        ->end()
                        ->scalarNode('default_reply_to_name')
                            ->info('The default Reply-To name, leave null to ignore')
                            ->defaultNull()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('slack_logging')
                    ->addDefaultsIfNotSet()
                    ->info('Logging errors to Slack')
                    ->children()
                        ->booleanNode('enabled')
                            ->info('Whether the Slack logging is enabled or not')
                            ->defaultFalse()
                        ->end()
                        ->booleanNode('bubble')
                            ->info('Whether the messages that are handled can bubble up the stack or not')
                            ->defaultTrue()
                        ->end()
                        ->scalarNode('token')
                            ->info('Slack API token, should be stored in env variable')
                            ->defaultValue('%env(SLACK_API_KEY)%')
                        ->end()
                        ->enumNode('level')
                            ->info('The minimum logging level at which this handler will be triggered')
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
                            ->info('The channel this bot will log the messages to (encoded ID or name)')
                            ->defaultValue('#errors')
                        ->end()
                        ->scalarNode('bot_name')
                            ->info('The bot name')
                            ->defaultValue('Error Logger')
                        ->end()
                        ->scalarNode('icon_emoji')
                            ->info('The emoji used by the bot as a profile image (or null)')
                            ->defaultValue(':poop:')
                        ->end()
                        ->booleanNode('include_extra')
                            ->info('Whether the attachment should include context and extra data')
                            ->defaultTrue()
                        ->end()
                        ->booleanNode('use_attachment')
                            ->info('Whether the message should be added to Slack as attachment (plain text otherwise)')
                            ->defaultTrue()
                        ->end()
                        ->booleanNode('use_short_attachment')
                            ->info('Whether the the context/extra messages added to Slack as attachments are in a short style')
                            ->defaultFalse()
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
                        ->arrayNode('excluded_fields')
                            ->info('Dot separated list of fields to exclude from slack message. E.g. [\'context.field1\', \'extra.field2\']')
                            ->scalarPrototype()->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $builder;
    }
}
