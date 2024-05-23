<?php

namespace App\Entity;

class BasicCar extends Car
{
    public function __construct(Car $car)
    {
        $this->description = $car->getDescription() . " Basic";
    }

    public function cost()
    {
        return 80000;
    }
}
