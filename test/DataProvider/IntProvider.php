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

final class IntProvider
{
    /**
     * @return \Generator<string, array{0: string, 1: string, 2: int}>
     */
    public static function valuesAndResultOfComparison(): \Generator
    {
        $values = [
            'less' => [
                '0',
                '1',
                -1,
            ],
            'same' => [
                '1',
                '1',
                0,
            ],
            'greater' => [
                '2',
                '1',
                1,
            ],
        ];

        foreach ($values as $key => [$value, $otherValue, $result]) {
            yield $key => [
                $value,
                $otherValue,
                $result,
            ];
        }
    }
}
