<?php

declare(strict_types=1);

namespace Elavora\Api\DataTypes\Uuid\Tests;

use Elavora\Api\DataTypes\Uuid;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * Regras de validacao e normalizacao do Uuid.
 *
 * @see docs/USO.md#regras-de-validacao-e-normalizacao
 */
final class UuidTest extends TestCase
{
    /**
     * @return array<string, array{string, string}>
     */
    public static function validProvider(): array
    {
        return [
            'versao 4 minuscula' => [
                '00000000-0000-4000-8000-000000000000',
                '00000000-0000-4000-8000-000000000000',
            ],
            'maiusculas' => [
                '550E8400-E29B-41D4-A716-446655440000',
                '550e8400-e29b-41d4-a716-446655440000',
            ],
            'versao 1' => [
                '6ba7b810-9dad-11d1-80b4-00c04fd430c8',
                '6ba7b810-9dad-11d1-80b4-00c04fd430c8',
            ],
            'versao 2' => [
                '6ba7b810-9dad-21d1-80b4-00c04fd430c8',
                '6ba7b810-9dad-21d1-80b4-00c04fd430c8',
            ],
            'versao 3' => [
                '6ba7b810-9dad-31d1-80b4-00c04fd430c8',
                '6ba7b810-9dad-31d1-80b4-00c04fd430c8',
            ],
            'versao 5' => [
                '6ba7b810-9dad-51d1-80b4-00c04fd430c8',
                '6ba7b810-9dad-51d1-80b4-00c04fd430c8',
            ],
            'variante 9' => [
                '550e8400-e29b-41d4-9716-446655440000',
                '550e8400-e29b-41d4-9716-446655440000',
            ],
            'variante a' => [
                '550e8400-e29b-41d4-a716-446655440000',
                '550e8400-e29b-41d4-a716-446655440000',
            ],
            'variante b' => [
                '550e8400-e29b-41d4-b716-446655440000',
                '550e8400-e29b-41d4-b716-446655440000',
            ],
            'variante A maiuscula' => [
                '550e8400-e29b-41d4-A716-446655440000',
                '550e8400-e29b-41d4-a716-446655440000',
            ],
            'variante B maiuscula' => [
                '550e8400-e29b-41d4-B716-446655440000',
                '550e8400-e29b-41d4-b716-446655440000',
            ],
        ];
    }

    /**
     * @return array<string, array{mixed}>
     */
    public static function invalidProvider(): array
    {
        return [
            'uuid nulo (versao 0)' => ['00000000-0000-0000-0000-000000000000'],
            'versao 6' => ['550e8400-e29b-61d4-a716-446655440000'],
            'versao 7' => ['550e8400-e29b-71d4-a716-446655440000'],
            'variante c' => ['550e8400-e29b-41d4-c716-446655440000'],
            'variante 7' => ['550e8400-e29b-41d4-7716-446655440000'],
            'sem hifens' => ['550e8400e29b41d4a716446655440000'],
            'com chaves' => ['{550e8400-e29b-41d4-a716-446655440000}'],
            'digito nao hexadecimal' => ['550e8400-e29b-41d4-a716-44665544000g'],
            'texto qualquer' => ['invalid'],
            'string vazia' => [''],
            'inteiro' => [123],
            'null' => [null],
            'array vazio' => [[]],
            'objeto' => [new stdClass()],
        ];
    }

    #[DataProvider('validProvider')]
    public function testIsValidAcceptsValidUuid(string $input, string $expected): void
    {
        self::assertTrue(Uuid::isValid($input));
    }

    #[DataProvider('validProvider')]
    public function testFromNormalizesToLowercase(string $input, string $expected): void
    {
        self::assertSame($expected, Uuid::from($input)->value());
    }

    #[DataProvider('invalidProvider')]
    public function testIsValidRejectsInvalidValue(mixed $input): void
    {
        self::assertFalse(Uuid::isValid($input));
    }

    #[DataProvider('invalidProvider')]
    public function testFromThrowsOnInvalidValue(mixed $input): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Valor invalido para Elavora\Api\DataTypes\Uuid.');

        Uuid::from($input);
    }

    public function testFromReturnsUuidInstance(): void
    {
        self::assertInstanceOf(Uuid::class, Uuid::from('00000000-0000-4000-8000-000000000000'));
    }

    public function testJsonSerializeAndStringCastEqualValue(): void
    {
        $uuid = Uuid::from('550E8400-E29B-41D4-A716-446655440000');

        self::assertSame('550e8400-e29b-41d4-a716-446655440000', $uuid->jsonSerialize());
        self::assertSame('550e8400-e29b-41d4-a716-446655440000', (string) $uuid);
        self::assertSame('"550e8400-e29b-41d4-a716-446655440000"', json_encode($uuid));
    }

    public function testGenerateReturnsValidVersion4Uuid(): void
    {
        $value = Uuid::generate()->value();

        self::assertTrue(Uuid::isValid($value));
        self::assertSame(strtolower($value), $value);
        self::assertMatchesRegularExpression(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/D',
            $value,
        );
    }

    public function testGenerateReturnsDifferentValues(): void
    {
        self::assertNotSame(Uuid::generate()->value(), Uuid::generate()->value());
    }

    public function testClassIsFinalReadonlyWithProtectedConstructor(): void
    {
        $class = new \ReflectionClass(Uuid::class);

        self::assertTrue($class->isFinal());
        self::assertTrue($class->isReadOnly());
        self::assertFalse($class->getConstructor()?->isPublic() ?? false);
    }
}
