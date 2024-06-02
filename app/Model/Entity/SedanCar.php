<?php

namespace App\Model\Entity;

class SedanCar extends Car
{
    public function __construct()
    {
        $this->description = "SUV";
    }

    public function cost(): int
    {
        return 0;
    }
}

