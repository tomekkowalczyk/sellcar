<?php

namespace App\Entity;

class SuvCar extends Car
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

