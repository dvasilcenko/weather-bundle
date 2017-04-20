<?php

namespace Nfq\Bundle\WeatherBundle;

class Weather
{
    protected $weather;
    protected $type;

    public function __construct($weather, $type = 'C') {
        $this->weather = $weather;
        $this->type = $type;
    }

    /**
     * Get weather
     * Convert if needed
     *
     * @return int
     */
    public function getWeather() {
        if ($this->type === 'F') {
            return $this->toCelsius($this->weather);
        } else {
            return (int)$this->weather;
        }
    }

    public function __toString() {
        return "" . $this->getWeather();
    }

    /**
     * Convert Fahrenheit to Celsius
     *
     * @param int|str
     *  Fahrenheit
     *
     * @return int
     *  Celsius
     */
    public function toCelsius($weather) {
        return round(((int)$weather - 32) * 5 / 9);
    }
}
