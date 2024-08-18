<?php

declare(strict_types=1);

namespace App\Observe;

interface Observer
{
    public function update(string $event, $data): void;
}
