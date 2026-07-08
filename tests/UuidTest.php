<?php

declare(strict_types=1);

namespace Elavora\Api\DataTypes\Uuid\Tests;

use Elavora\Api\DataTypes\Uuid;
use PHPUnit\Framework\TestCase;

final class UuidTest extends TestCase
{
    public function testValidatesAndGeneratesUuid(): void
    {
        self::assertTrue(Uuid::isValid(Uuid::generate()->value()));
        self::assertFalse(Uuid::isValid('invalid'));
    }
}
