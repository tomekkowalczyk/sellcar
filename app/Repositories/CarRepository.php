<?php

declare(strict_types=1);

namespace App\Repositories;

use Illuminate\Support\Collection;

interface CarRepository
{
    public function save(array $data): void;
    public function get(): Collection;

}
