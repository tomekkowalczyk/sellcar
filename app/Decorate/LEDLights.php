<?php
declare(strict_types=1);

namespace App\Decorate;

class LEDLights extends CarDecorator
{
    public function getDescription(): string
    {
        return $this->car->getDescription() . ", LED Lights";
    }

    public function cost()
    {
        return $this->car->cost() + 1350;
    }
}
