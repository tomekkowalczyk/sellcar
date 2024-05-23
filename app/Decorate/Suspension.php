<?php
declare(strict_types=1);

namespace App\Decorate;

class Suspension extends CarDecorator
{
    public function getDescription(): string
    {
        return $this->car->getDescription() . ", Suspension";
    }

    public function cost()
    {
        return $this->car->cost() + 2500;
    }
}
