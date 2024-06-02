<?php
declare(strict_types=1);

namespace App\Decorate;

class BiggerWheels extends CarDecorator
{
    private const BIGGER_WHEELS_COST = 1200;

    public function getDescription(): string
    {
        return $this->car->getDescription() . ", Bigger Wheels";
    }

    public function cost(): int
    {
        return $this->car->cost() + self::BIGGER_WHEELS_COST;
    }
}
