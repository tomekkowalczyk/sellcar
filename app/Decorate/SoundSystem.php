<?php
declare(strict_types=1);

namespace App\Decorate;

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
