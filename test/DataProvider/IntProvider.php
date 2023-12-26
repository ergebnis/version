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

final class IntProvider extends DataProvider\AbstractProvider
{
    /**
     * @return \Generator<string, array{0: string, 1: string, 2: int}>
     */
    public static function valuesAndResultOfComparison(): \Generator
    {
        $values = [
            0,
            1,
            self::faker()->numberBetween(2),
        ];

        $count = \count($values);

        for ($i = 0; $count - 1 > $i; ++$i) {
            for ($j = $i + 1; $count > $j; ++$j) {
                $value = $values[$i];
                $otherValue = $values[$j];

                $key = \sprintf(
                    '%d-smaller-than-%d',
                    $value,
                    $otherValue,
                );

                yield $key => [
                    (string) $value,
                    (string) $otherValue,
                    -1,
                ];
            }
        }

        foreach ($values as $value) {
            $key = \sprintf(
                '%d-equal-to-%d',
                $value,
                $value,
            );

            yield $key => [
                (string) $value,
                (string) $value,
                0,
            ];
        }

        $reverse = \array_reverse($values);

        for ($i = 0; $count - 1 > $i; ++$i) {
            for ($j = $i + 1; $count > $j; ++$j) {
                $value = $reverse[$i];
                $otherValue = $reverse[$j];

                $key = \sprintf(
                    '%d-greater-than-%d',
                    $value,
                    $otherValue,
                );

                yield $key => [
                    (string) $value,
                    (string) $otherValue,
                    1,
                ];
            }
        }
    }
}
