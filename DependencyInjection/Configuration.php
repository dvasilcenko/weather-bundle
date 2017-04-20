<?php

namespace Dvasilcenko\Bundle\WeatherBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('nfq_weather');

        $rootNode
            ->children()
                ->scalarNode('provider')->isRequired()->end()
                ->arrayNode('providers')
                    ->children()

                        ->arrayNode('yahoo')
                            ->children()
                                ->scalarNode('key')->isRequired()->end()
                            ->end()
                        ->end()

                        ->arrayNode('owm')
                            ->children()
                                ->scalarNode('key')->isRequired()->end()
                            ->end()
                        ->end()

                        ->arrayNode('delegating')
                            ->children()
                                ->arrayNode('providers')
                                    ->prototype('scalar')
                                    ->end()
                                ->end()
                            ->end()
                        ->end()

                        ->arrayNode('cached')
                            ->children()
                                ->scalarNode('provider')->isRequired()->end()
                                ->scalarNode('ttl')->isRequired()->end()
                            ->end()
                        ->end()

                    ->end()
                ->end()

            ->end();

        return $treeBuilder;
    }
}
