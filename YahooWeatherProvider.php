<?php

namespace Dvasilcenko\Bundle\WeatherBundle;

class YahooWeatherProvider implements WeatherProviderInterface
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
            throw new WeatherProviderException('API Key is not configured for YahooWeatherProvider');
        }

        $url = $this->getUrl($location->getLocation());
        $weather = $this->extractWeather($this->getWeatherData($url));

        return new Weather($weather, 'F');
    }

    /**
     * Generate a correct url for the weather provider's endpoint
     *
     * @return string
     *  Url
     */
    public function getUrl($location) {
        $q = 'select * from weather.forecast where woeid in (select woeid from geo.places(1) 
          where text="' . $location . '")';
        $url = "https://query.yahooapis.com/v1/public/yql?format=json&env="
            . "store%3A%2F%2Fdatatables.org%2Falltableswithkeys&q=" . urlencode($q);
        return $url;
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
        if ( isset($weatherData['query']['results']['channel']['item']['condition']['temp']) ) {
            $weather = $weatherData['query']['results']['channel']['item']['condition']['temp'];
            return $weather;
        } else {
            throw new WeatherProviderException('No weather data for such location');
        }
    }
}
