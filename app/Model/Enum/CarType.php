<?php

declare(strict_types=1);

namespace App\Model\Enum;

enum CarType: string
{
    case SEDAN = 'Sedan';
    case SUV = 'SUV';

    public static function getValues(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }

    public static function fromString(string $type): ?self
    {
        return match ($type) {
            'SUV' => self::SUV,
            'Sedan' => self::SEDAN,
            default => null,
        };
    }
}

