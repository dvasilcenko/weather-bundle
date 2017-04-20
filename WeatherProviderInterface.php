<?php

namespace Dvasilcenko\Bundle\WeatherBundle;

interface WeatherProviderInterface
{
    /**
     * Fetch current Weather by given Location.
     *
     * @param Location $location
     *
     * @return Weather
     *
     * @throws WeatherProviderException
     */
    public function fetch(Location $location): Weather;

    /**
     * Generate a correct url for the weather provider's endpoint
     *
     * @return string
     *  Url
     */
    public function getUrl($location);

    /**
     * Get json data from url and decode it
     *
     * @return array
     *  Weather provider data
     */
    public function getWeatherData($url);

    /**
     * Extract a weather value from provider's data
     *
     * @param array
     *  Weather provider's supplied data
     *
     * @return int|WeatherNotFoundException
     *  Weather in celcius or false if no value
     */
    public function extractWeather( $weatherData );
}
