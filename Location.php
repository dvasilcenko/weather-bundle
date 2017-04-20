<?php

namespace Nfq\Bundle\WeatherBundle;

class Location
{
    protected $location;

    public function __construct($location) {
        $this->location = $location;
    }

    /**
     * Get current location
     *
     * @return string
     */
    public function getLocation() {
        return $this->location;
    }
}
