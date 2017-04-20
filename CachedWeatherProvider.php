<?php
namespace Dvasilcenko\Bundle\WeatherBundle;

use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class CachedWeatherProvider extends FilesystemAdapter
{

    public $provider;
    public $cache;

    public function __construct($provider, $providersData, $ttl)
    {
        $this->provider = new $provider($providersData);
        $this->cache = new FilesystemAdapter('', $ttl);
    }

    /**
     * Get a cached value for a selected date
     * If cached value does not exist get it from weather providers and cache it
     *
     * @param string
     *  date as a key for cached weather
     *
     * @return string
     *  weather for the date
     */
    public function fetch(Location $location)
    {
        $cachedWeather = $this->cache->getItem($this->getKey($location->getLocation()));

        // if item does not exists in the cache
        if (!$cachedWeather->isHit()) {
            $weather = $this->provider->fetch($location);

            // save new value to cache
            $cachedWeather->set($weather);
            $this->cache->save($cachedWeather);
        }
        return $cachedWeather->get();
    }

    /**
     * Add a prefix to the cache key
     *
     * @param string
     *  key
     *
     * @return string
     *  key with prefix
     */
    public function getKey($key)
    {
        return 'weather.' . $key;
    }

}