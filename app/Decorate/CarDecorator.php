<?php
declare(strict_types=1);

namespace App\Decorate;

use App\Entity\Car;

abstract class CarDecorator extends Car
{
    protected $car;

    public function __construct(Car $car)
    {
        $this->car = $car;
    }

    public function getDescription(): string
    {
        return $this->car->getDescription();
    }

    public function cost()
    {
        return $this->car->cost();
    }
}
