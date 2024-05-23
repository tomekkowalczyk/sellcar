<?php

namespace App\Entity;

class RSCar extends Car
{
    public function __construct(Car $car)
    {
        $this->description = $car->getDescription() . " RS";
    }

    public function cost()
    {
        return 150000;
    }
}
