<?php
declare(strict_types=1);

namespace App\Decorate;

class LeatherSeats extends CarDecorator
{
    public function getDescription(): string
    {
        return $this->car->getDescription() . ", Leather Seats";
    }

    public function cost()
    {
        return $this->car->cost() + 3500;
    }
}
