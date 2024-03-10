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
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\Version\Exception\ExtensionMissing
 */
final class ExtensionMissingTest extends Framework\TestCase
{
    public function testBcmathReturnsException(): void
    {
        $exception = Exception\ExtensionMissing::bcmath();

        $message = \sprintf(
            'The bcmath extension is required to perform calculations for integers that are greater than "%d".',
            \PHP_INT_MAX,
        );

        self::assertSame($message, $exception->getMessage());
    }
}
