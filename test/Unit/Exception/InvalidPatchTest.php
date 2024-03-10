<?php

declare(strict_types=1);

/**
 * Copyright (c) 2023-2024 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/version
 */

namespace Ergebnis\Version\Test\Unit\Exception;

use Ergebnis\Version\Exception;
use Ergebnis\Version\Test;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\Version\Exception\InvalidPatch
 */
final class InvalidPatchTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testFromIntReturnsException(): void
    {
        $value = self::faker()->numberBetween(0);

        $exception = Exception\InvalidPatch::fromInt($value);

        $message = \sprintf(
            'Value "%d" does not appear to be valid.',
            $value,
        );

        self::assertSame($message, $exception->getMessage());
    }

    public function testFromStringReturnsException(): void
    {
        $value = self::faker()->word();

        $exception = Exception\InvalidPatch::fromString($value);

        $message = \sprintf(
            'Value "%s" does not appear to be valid.',
            $value,
        );

        self::assertSame($message, $exception->getMessage());
    }
}
