<?php
declare(strict_types=1);

namespace App\Decorate;

use App\Entity\Car;

class BiggerWheels extends CarDecorator
{
    public function getDescription(): string
    {
        return $this->car->getDescription() . ", Bigger Wheels";
    }

    public function cost()
    {
        return $this->car->cost() + 1200;
    }
}
