<?php
declare(strict_types=1);

namespace App\Decorate;

use App\Entity\Car;

class SoundSystem extends CarDecorator
{
    public function getDescription(): string
    {
        return $this->car->getDescription() . ", Sound System";
    }

    public function cost()
    {
        return $this->car->cost() + 3000;
    }
}
