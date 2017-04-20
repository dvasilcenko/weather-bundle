<?php

namespace Nfq\Bundle\WeatherBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class NfqWeatherExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $container->setAlias('nfq_weather.provider', 'nfq_weather.provider.'.$config['provider']);

        if (isset($config['providers']['yahoo']['key'])) {
            $container->getDefinition('nfq_weather.provider.yahoo')
                ->replaceArgument(0, $config['providers']['yahoo']['key']);
        }

        if (isset($config['providers']['owm']['key'])) {
            $container->getDefinition('nfq_weather.provider.owm')
                ->replaceArgument(0, $config['providers']['owm']['key']);
        }

        if (isset($config['providers']['delegating']['providers'])) {
            $providers = $config['providers']['delegating']['providers'];
            $providersData = [];
            foreach ($providers as $provider) {
                $class = $container->getDefinition('nfq_weather.provider.' . $provider)->getClass();
                $key = $config['providers'][$provider]['key'];
                $providersData += [$class => $key];
            }
            $container->getDefinition('nfq_weather.provider.delegating')
                ->replaceArgument(0, $providersData);
        }

        if (isset($config['providers']['cached']['provider'])) {
            $provider = $container->getDefinition('nfq_weather.provider.'
                . $config['providers']['cached']['provider']);

            $container->getDefinition('nfq_weather.provider.cached')
                ->replaceArgument(0, $provider->getClass());

            $container->getDefinition('nfq_weather.provider.cached')
                ->replaceArgument(1, $provider->getArguments()[0]);
        }

        if (isset($config['providers']['cached']['ttl'])) {
            $container->getDefinition('nfq_weather.provider.cached')
                ->replaceArgument(2, $config['providers']['cached']['ttl']);
        }
    }
}
