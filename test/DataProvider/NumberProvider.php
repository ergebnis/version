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
     * @see https://semver.org/#is-there-a-suggested-regular-expression-regex-to-check-a-semver-string
     * @see https://regex101.com/r/Ly7O1x/3/
     *
     * @return \Generator<string, array{0: string}>
     */
    public static function invalid(): \Generator
    {
        $faker = self::faker();

        $values = [
            'leading-zero' => \sprintf(
                '0%d',
                $faker->numberBetween(1),
            ),
            'word' => $faker->word(),
        ];

        foreach ($values as $value) {
            yield $value => [
                $value,
            ];
        }
    }

    /**
     * @see https://semver.org/#is-there-a-suggested-regular-expression-regex-to-check-a-semver-string
     * @see https://regex101.com/r/Ly7O1x/3/
     *
     * @return \Generator<string, array{0: string}>
     */
    public static function valid(): \Generator
    {
        $values = [
            'zero' => '0',
            'one' => '1',
            'greater-than-one' => (string) self::faker()->numberBetween(2),
        ];

        foreach ($values as $key => $value) {
            yield $key => [
                $value,
            ];
        }
    }

    /**
     * @return \Generator<string, array{0: string, 1: string}>
     */
    public static function valueAndBumpedValue(): \Generator
    {
        $values = [
            'zero' => [
                '0',
                '1',
            ],
            'one' => [
                '1',
                '2',
            ],
            'php-int-max' => [
                (string) \PHP_INT_MAX,
                \bcadd(
                    (string) \PHP_INT_MAX,
                    '1',
                ),
            ],
        ];

        foreach ($values as $key => [$value, $bumpedValue]) {
            yield $key => [
                $value,
                $bumpedValue,
            ];
        }
    }

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
