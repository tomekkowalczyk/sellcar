<?php

namespace App\Entity;

class BusinessCar extends Car
{
    public function __construct(Car $car)
    {
        $this->description = $car->getDescription() . " Business";
    }

    public function cost()
    {
        return 110000;
    }
}
