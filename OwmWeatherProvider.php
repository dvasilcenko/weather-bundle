<?php

namespace Nfq\Bundle\WeatherBundle;

class OwmWeatherProvider implements WeatherProviderInterface
{
    /**
     * @var string
     */
    private $apiKey;

    /**
     * @param string $apiKey
     */
    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * {@inheritdoc}
     */
    public function fetch(Location $location): Weather
    {
        // no api is needed for yahoo
        // in 2016 due to developer's outrage yahoo let public weather access
        if (!$this->apiKey) {
            throw new WeatherProviderException('API Key is not configured for OwmWeatherProvider');
        }

        $url = $this->getUrl($location->getLocation());
        $weather = $this->extractWeather($this->getWeatherData($url));

        return new Weather($weather);
    }

    /**
     * Generate a correct url for the weather provider's endpoint
     *
     * @param string
     * @return string
     *  Url
     */
    public function getUrl($location) {
        return 'http://api.openweathermap.org/data/2.5/weather?q=' . $location
            . '&units=metric&APPID=' . $this->apiKey;
    }

    /**
     * Get json data from url and decode it
     *
     * @return array
     *  Weather provider data
     */
    public function getWeatherData($url) {
        $ret = file_get_contents($url);
        return json_decode($ret, 1);
    }

    /**
     * Extract a weather value from provider's data
     *
     * @param array
     *  Weather provider's supplied data
     *
     * @return int|WeatherNotFoundException
     *  Weather in celcius or false if no value
     */
    public function extractWeather( $weatherData ) {
        if ( isset($weatherData['main']['temp']) ) {
            $weather = $weatherData['main']['temp'];
            return (int)$weather;
        } else {
            throw new WeatherProviderException('No weather data for such location');
        }
    }
}
