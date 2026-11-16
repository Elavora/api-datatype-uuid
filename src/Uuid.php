<?php

declare(strict_types=1);

namespace Elavora\Api\DataTypes;

use Elavora\Api\Framework\Contracts\DescribesType;

final readonly class Uuid extends AbstractDataType implements DescribesType
{
    /**
     * Descreve o tipo para a documentacao publica (OPTIONS).
     *
     * @return array<string, mixed>
     */
    public static function documentedType(): array
    {
        return [
            'type' => 'string',
            'format' => 'uuid',
            'pattern' => '^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[1-5][0-9a-fA-F]{3}-[89abAB][0-9a-fA-F]{3}-[0-9a-fA-F]{12}$',
            'description' => 'UUID nas versoes 1 a 5, em hexadecimal com hifens; o valor e normalizado para minusculas.',
            'example' => 'f47ac10b-58cc-4372-a567-0e02b2c3d479',
        ];
    }

    /**
     * Gera um UUID v4 valido.
     */
    public static function generate(): self
    {
        $bytes = random_bytes(16);
        $bytes[6] = chr((ord($bytes[6]) & 0x0f) | 0x40);
        $bytes[8] = chr((ord($bytes[8]) & 0x3f) | 0x80);
        $hex = bin2hex($bytes);

        return new self(sprintf(
            '%s-%s-%s-%s-%s',
            substr($hex, 0, 8),
            substr($hex, 8, 4),
            substr($hex, 12, 4),
            substr($hex, 16, 4),
            substr($hex, 20),
        ));
    }

    /**
     * Verifica se o valor e um UUID valido.
     */
    public static function isValid(mixed $value): bool
    {
        return is_string($value)
            && preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i', $value) === 1;
    }

    protected static function normalize(mixed $value): string
    {
        return strtolower((string) $value);
    }
}
