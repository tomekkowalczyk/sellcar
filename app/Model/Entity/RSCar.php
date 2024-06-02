<?php

declare(strict_types=1);

namespace App\Model\Entity;

class RSCar extends Car
{
    private const RS_CAR_COST = 150000;

    public function __construct(Car $car)
    {
        $this->description = $car->getDescription() . " RS";
    }

    public function cost(): int
    {
        return self::RS_CAR_COST;
    }
}
