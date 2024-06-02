<?php
declare(strict_types=1);

namespace App\Model\Entity;

class BusinessCar extends Car
{
    private const BUSINESS_CAR_COST = 110000;

    public function __construct(Car $car)
    {
        $this->description = $car->getDescription() . " Business";
    }

    public function cost(): int
    {
        return self::BUSINESS_CAR_COST;
    }
}
