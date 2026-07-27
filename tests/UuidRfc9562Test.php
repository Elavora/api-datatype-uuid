<?php

declare(strict_types=1);

namespace Elavora\Api\DataTypes\Uuid\Tests;

use Elavora\Api\DataTypes\Uuid;
use PHPUnit\Framework\TestCase;

final class UuidRfc9562Test extends TestCase
{
    public function testAcceptsCanonicalUuidVersionsOneThroughEight(): void
    {
        $uuids = [
            'f81d4fae-7dec-11d0-a765-00a0c91e6bf6',
            'f81d4fae-7dec-21d0-a765-00a0c91e6bf6',
            '6fa459ea-ee8a-3ca4-894e-db77e160355e',
            '550e8400-e29b-41d4-a716-446655440000',
            '886313e1-3b8a-5372-9b90-0c9aee199e5d',
            '1EC9414C-232A-6B00-B3C8-9F6BDECED846',
            '017F22E2-79B0-7CC3-98C4-DC0C0C07398F',
            '2489E9AD-2EE2-8E00-8EC9-32D5F69181C0',
        ];

        foreach ($uuids as $uuid) {
            self::assertTrue(Uuid::isValid($uuid), $uuid);
        }
    }

    public function testAcceptsNilAndMaxUuid(): void
    {
        self::assertTrue(Uuid::isValid('00000000-0000-0000-0000-000000000000'));
        self::assertTrue(Uuid::isValid('FFFFFFFF-FFFF-FFFF-FFFF-FFFFFFFFFFFF'));
    }

    public function testNormalizesUppercaseUuid(): void
    {
        $uuid = Uuid::from('017F22E2-79B0-7CC3-98C4-DC0C0C07398F');

        self::assertSame('017f22e2-79b0-7cc3-98c4-dc0c0c07398f', $uuid->value());
    }

    public function testGenerateStillReturnsVersionFourWithRfcVariant(): void
    {
        $uuid = (string) Uuid::generate()->value();

        self::assertMatchesRegularExpression(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/',
            $uuid
        );
    }

    public function testRejectsUnsupportedBitsAndNonCanonicalFormats(): void
    {
        $invalid = [
            '017f22e2-79b0-9cc3-98c4-dc0c0c07398f',
            '017f22e2-79b0-7cc3-78c4-dc0c0c07398f',
            '017f22e279b07cc398c4dc0c0c07398f',
            '{017f22e2-79b0-7cc3-98c4-dc0c0c07398f}',
            'urn:uuid:017f22e2-79b0-7cc3-98c4-dc0c0c07398f',
            '017f22e2-79b0-7cc3-98c4-dc0c0c07398f-extra',
            '017f22e2-79b0-7cc3-98c4-dc0c0c07398',
        ];

        foreach ($invalid as $uuid) {
            self::assertFalse(Uuid::isValid($uuid), $uuid);
        }
    }
}
