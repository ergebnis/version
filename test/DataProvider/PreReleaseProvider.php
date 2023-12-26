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

final class PreReleaseProvider
{
    /**
     * @see https://semver.org/#is-there-a-suggested-regular-expression-regex-to-check-a-semver-string
     * @see https://regex101.com/r/Ly7O1x/3/
     *
     * @return \Generator<string, array{0: string}>
     */
    public static function invalid(): \Generator
    {
        $values = [
            '', // use named constructor to create an empty pre-release
            '0123',
            '0123.0123',
            'alpha_beta',
            'alpha.',
            'alpha..',
            'alpha..1',
            'alpha...1',
            'alpha....1',
            'alpha.....1',
            'alpha......1',
            'alpha.......1',
            '-1.0.3-gamma+b7718',
            '+justmeta',
            '9.8.7+meta+meta',
            '9.8.7-whatever+meta+meta',
            '99999999999999999999999.999999999999999999.99999999999999999----RC-SNAPSHOT.12.09.1--------------------------------..12',
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
            'prerelease',
            'alpha',
            'beta',
            'alpha.beta',
            'alpha.beta.1',
            'alpha.1',
            'alpha0.valid',
            'alpha.0valid',
            'alpha-a.b-c-somethinglong',
            'rc.1',
            'DEV-SNAPSHOT',
            'SNAPSHOT-123',
            'alpha.1227',
            '---RC-SNAPSHOT.12.9.1--.12',
            '---R-S.12.9.1--.12',
            '0A.is.legal',
        ];

        foreach ($values as $value) {
            yield $value => [
                $value,
            ];
        }
    }

    /**
     * @see https://semver.org/#spec-item-11
     * @see https://semver.org/#is-there-a-suggested-regular-expression-regex-to-check-a-semver-string
     * @see https://regex101.com/r/Ly7O1x/3/
     * @see https://www.sfu.ca/sasdoc/sashtml/proc/z1epts.htm
     *
     * @return \Generator<string, array{0: string, 1: string, 2: int}>
     */
    public static function valueOtherValueAndResult(): \Generator
    {
        $values = [
            '---R-S.12.9.1--.12',
            '---RC-SNAPSHOT.12.9.1--.12',
            '0A.is.legal',
            'DEV-SNAPSHOT',
            'SNAPSHOT-123',
            'alpha',
            'alpha.1',
            'alpha.1227',
            'alpha.beta',
            'alpha.beta.1',
            'alpha-a.b-c-somethinglong',
            'alpha0.valid',
            'beta',
            'beta.2',
            'beta.11',
            'prerelease',
            'rc.1',
        ];

        $count = \count($values);

        for ($i = 0; $count - 1 > $i; ++$i) {
            $value = $values[$i];

            for ($j = $i + 1; $count > $j; ++$j) {
                $otherValue = $values[$j];

                $key = \sprintf(
                    '%s-smaller-than-%s',
                    $value,
                    $otherValue,
                );

                yield $key => [
                    $value,
                    $otherValue,
                    -1,
                ];
            }
        }

        foreach ($values as $value) {
            $key = \sprintf(
                '%s-equal-to-%s',
                $value,
                $value,
            );

            yield $key => [
                $value,
                $value,
                0,
            ];
        }

        $reverse = \array_reverse($values);

        for ($i = 0; $count - 1 > $i; ++$i) {
            $value = $reverse[$i];

            for ($j = $i + 1; $count > $j; ++$j) {
                $otherValue = $reverse[$j];

                $key = \sprintf(
                    '%s-greater-than-%s',
                    $value,
                    $otherValue,
                );

                yield $key => [
                    $value,
                    $otherValue,
                    1,
                ];
            }
        }
    }
}
