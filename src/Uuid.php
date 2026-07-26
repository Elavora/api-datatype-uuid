<?php

declare(strict_types=1);

namespace Elavora\Api\DataTypes;

final readonly class Uuid extends AbstractDataType
{
    private const NIL = '00000000-0000-0000-0000-000000000000';
    private const MAX = 'ffffffff-ffff-ffff-ffff-ffffffffffff';

    /**
     * Gera um UUID v4 valido.
     */
    public static function generate(): self
    {
        $bytes = random_bytes(16);
        $bytes[6] = chr((ord($bytes[6]) & 0x0f) | 0x40);
        $bytes[8] = chr((ord($bytes[8]) & 0x3f) | 0x80);
        $hex = bin2hex($bytes);

        return self::from(sprintf(
            '%s-%s-%s-%s-%s',
            substr($hex, 0, 8),
            substr($hex, 8, 4),
            substr($hex, 12, 4),
            substr($hex, 16, 4),
            substr($hex, 20)
        ));
    }

    /**
     * Verifica se o valor e um UUID valido.
     */
    public static function isValid(mixed $value): bool
    {
        if (!is_string($value)) {
            return false;
        }

        $normalized = strtolower($value);
        if ($normalized === self::NIL || $normalized === self::MAX) {
            return true;
        }

        return preg_match(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-[1-8][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/',
            $normalized
        ) === 1;
    }

    protected static function normalize(mixed $value): string
    {
        return strtolower((string) $value);
    }
}
