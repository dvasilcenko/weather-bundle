<?php
namespace Dvasilcenko\Bundle\WeatherBundle;


class DelegatingWeatherProvider
{
    public $providers;

    public function __construct($providers)
    {
        $this->providers = $providers;
    }

    /**
     * Try to fetch weather in all providers set in configuration
     * Return the first successful provider's weather
     *
     * @param Location $location
     * @return mixed
     * @throws WeatherProviderException
     */
    public function fetch(Location $location) {
        foreach ($this->providers as $provider => $key) {
            $weather = new $provider($key);
            $value = $weather->fetch($location)->getWeather();
            if (is_numeric($value)) {
                return $value;
            }
        }
        throw new WeatherProviderException('No weather data for such location');
    }
}