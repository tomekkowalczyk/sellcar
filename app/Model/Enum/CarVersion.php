<?php

declare(strict_types=1);

namespace App\Model\Enum;

enum CarVersion: string
{
    case BASIC = 'Basic';
    case BUSINESS = 'Business';
    case RS = 'RS';

    public static function getValues(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }

    public static function fromString(array|string $version): ?CarVersion
    {
        return match ($version) {
            'Basic' => self::BASIC,
            'Business' => self::BUSINESS,
            'RS' => self::RS,
            default => null,
        };
    }
}

