<?php

declare(strict_types=1);

/**
 * Copyright (c) 2023 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/version
 */

namespace Ergebnis\Version\Test\DataProvider;

use Ergebnis\DataProvider;

final class NumberProvider extends DataProvider\AbstractProvider
{
    /**
     * @return \Generator<string, array{0: string, 1: string}>
     */
    public static function valuesWhereFirstValueIsSmallerThanSecondValue(): \Generator
    {
        foreach (self::values() as $firstValue) {
            $secondValue = $firstValue + 1;

            $key = \sprintf(
                '%d-smaller-than-%d',
                $firstValue,
                $secondValue,
            );

            yield $key => [
                (string) $firstValue,
                (string) $secondValue,
            ];
        }
    }

    /**
     * @return \Generator<string, array{0: string, 1: string}>
     */
    public static function valuesWhereFirstValueIsGreaterThanSecondValue(): \Generator
    {
        foreach (self::values() as $secondValue) {
            $firstValue = $secondValue + 1;

            $key = \sprintf(
                '%d-greater-than-%d',
                $firstValue,
                $secondValue,
            );

            yield $key => [
                (string) $firstValue,
                (string) $secondValue,
            ];
        }
    }

    /**
     * @return list<int>
     */
    private static function values(): array
    {
        return [
            0,
            1,
            self::faker()->numberBetween(2),
        ];
    }
}
