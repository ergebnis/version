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
        foreach (self::values() as $key => $value) {
            $adjustedKey = \sprintf(
                '%s-with-leading-zero',
                $key,
            );

            yield $adjustedKey => [
                \sprintf(
                    '0%d',
                    $value,
                ),
            ];
        }

        foreach (self::values() as $key => $value) {
            $adjustedKey = \sprintf(
                '%s-with-leading-space',
                $key,
            );

            yield $adjustedKey => [
                \sprintf(
                    ' %d',
                    $value,
                ),
            ];
        }

        foreach (self::values() as $key => $value) {
            $adjustedKey = \sprintf(
                '%s-with-trailing-space',
                $key,
            );

            yield $adjustedKey => [
                \sprintf(
                    '%d ',
                    $value,
                ),
            ];
        }

        foreach (self::values() as $key => $value) {
            $adjustedKey = \sprintf(
                '%s-with-leading-and-trailing-space',
                $key,
            );

            yield $adjustedKey => [
                \sprintf(
                    ' %d ',
                    $value,
                ),
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
        foreach (self::values() as $key => $value) {
            yield $key => [
                (string) $value,
            ];
        }
    }

    /**
     * @return \Generator<string, array{0: string, 1: string}>
     */
    public static function valueAndBumpedValue(): \Generator
    {
        foreach (self::values() as $key => $value) {
            yield $key => [
                (string) $value,
                (string) ($value + 1),
            ];
        }

        yield 'php-int-max' => [
            (string) \PHP_INT_MAX,
            \bcadd(
                (string) \PHP_INT_MAX,
                '1',
            ),
        ];
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
     * @return array<string, int>
     */
    private static function values(): array
    {
        return [
            'zero' => 0,
            'one' => 1,
            'greater-than-one' => self::faker()->numberBetween(2),
        ];
    }
}
