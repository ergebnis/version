<?php

declare(strict_types=1);

/**
 * Copyright (c) 2023-2026 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/version
 */

namespace Ergebnis\Version\Test\DataProvider;

final class VersionProvider
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
            '1',
            '1.2',
            '1.2.3-0123',
            '1.2.3-0123.0123',
            '1.1.2+.123',
            '+invalid',
            '-invalid',
            '-invalid+invalid',
            '-invalid.01',
            'alpha',
            'alpha.beta',
            'alpha.beta.1',
            'alpha.1',
            'alpha+beta',
            'alpha_beta',
            'alpha.',
            'alpha..',
            'beta',
            '1.0.0-alpha_beta',
            '-alpha.',
            '1.0.0-alpha..',
            '1.0.0-alpha..1',
            '1.0.0-alpha...1',
            '1.0.0-alpha....1',
            '1.0.0-alpha.....1',
            '1.0.0-alpha......1',
            '1.0.0-alpha.......1',
            '01.1.1',
            '1.01.1',
            '1.1.01',
            '1.2.3.DEV',
            '1.2-SNAPSHOT',
            '1.2.31.2.3----RC-SNAPSHOT.12.09.1--..12+788',
            '1.2-RC-SNAPSHOT',
            '-1.0.3-gamma+b7718',
            '+justmeta',
            '9.8.7+meta+meta',
            '9.8.7-whatever+meta+meta',
            '99999999999999999999999.999999999999999999.99999999999999999----RC-SNAPSHOT.12.09.1--------------------------------..12',
            /**
             * @see https://github.com/ergebnis/version/issues/14
             */
            '1.2.3-.+b',
        ];

        foreach ($values as $value) {
            yield $value => [
                $value,
            ];
        }

        foreach (self::valuesOrderedByPrecedence() as $key => $value) {
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

        foreach (self::valuesOrderedByPrecedence() as $key => $value) {
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

        foreach (self::valuesOrderedByPrecedence() as $key => $value) {
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
        $values = [
            '0.0.4',
            '1.2.3',
            '10.20.30',
            '1.1.2-prerelease+meta',
            '1.1.2+meta',
            '1.1.2+meta-valid',
            '1.0.0-alpha',
            '1.0.0-beta',
            '1.0.0-alpha.beta',
            '1.0.0-alpha.beta.1',
            '1.0.0-alpha.1',
            '1.0.0-alpha0.valid',
            '1.0.0-alpha.0valid',
            '1.0.0-alpha-a.b-c-somethinglong+build.1-aef.1-its-okay',
            '1.0.0-rc.1+build.1',
            '2.0.0-rc.1+build.123',
            '1.2.3-beta',
            '10.2.3-DEV-SNAPSHOT',
            '1.2.3-SNAPSHOT-123',
            '1.0.0',
            '2.0.0',
            '1.1.7',
            '2.0.0+build.1848',
            '2.0.1-alpha.1227',
            '1.0.0-alpha+beta',
            '1.2.3----RC-SNAPSHOT.12.9.1--.12+788',
            '1.2.3----R-S.12.9.1--.12+meta',
            '1.2.3----RC-SNAPSHOT.12.9.1--.12',
            '1.0.0+0.build.1-rc.10000aaa-kk-0.1',
            '99999999999999999999999.999999999999999999.99999999999999999',
            '1.0.0-0A.is.legal',
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
     * @return \Generator<string, array{0: string, 1: string}>
     */
    public static function valueAndValueWithBumpedMajor(): \Generator
    {
        $values = [
            '0.0.4' => '1.0.0',
            '1.2.3' => '2.0.0',
            '10.20.30' => '11.0.0',
            '1.1.2-prerelease+meta' => '2.0.0',
            '1.1.2+meta' => '2.0.0',
            '1.0.0-alpha' => '2.0.0',
        ];

        foreach ($values as $value => $valueWithBumpedMajor) {
            yield $value => [
                $value,
                $valueWithBumpedMajor,
            ];
        }
    }

    /**
     * @see https://semver.org/#is-there-a-suggested-regular-expression-regex-to-check-a-semver-string
     * @see https://regex101.com/r/Ly7O1x/3/
     *
     * @return \Generator<string, array{0: string, 1: string}>
     */
    public static function valueAndValueWithBumpedMinor(): \Generator
    {
        $values = [
            '0.0.4' => '0.1.0',
            '1.2.3' => '1.3.0',
            '10.20.30' => '10.21.0',
            '1.1.2-prerelease+meta' => '1.2.0',
            '1.1.2+meta' => '1.2.0',
            '1.0.0-alpha' => '1.1.0',
        ];

        foreach ($values as $value => $valueWithBumpedMinor) {
            yield $value => [
                $value,
                $valueWithBumpedMinor,
            ];
        }
    }

    /**
     * @see https://semver.org/#is-there-a-suggested-regular-expression-regex-to-check-a-semver-string
     * @see https://regex101.com/r/Ly7O1x/3/
     *
     * @return \Generator<string, array{0: string, 1: string}>
     */
    public static function valueAndValueWithBumpedPatch(): \Generator
    {
        $values = [
            '0.0.4' => '0.0.5',
            '1.2.3' => '1.2.4',
            '10.20.30' => '10.20.31',
            '1.1.2-prerelease+meta' => '1.1.3',
            '1.1.2+meta' => '1.1.3',
            '1.0.0-alpha' => '1.0.1',
        ];

        foreach ($values as $value => $valueWithBumpedPatch) {
            yield $value => [
                $value,
                $valueWithBumpedPatch,
            ];
        }
    }

    /**
     * @return \Generator<string, array{0: string, 1: string, 2: int}>
     */
    public static function valuesWhereFirstValueIsSmallerThanSecondValue(): \Generator
    {
        $values = self::valuesOrderedByPrecedence();

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
    }

    /**
     * @return \Generator<string, array{0: string, 1: string, 2: int}>
     */
    public static function valuesWhereFirstValueIsGreaterThanSecondValue(): \Generator
    {
        $values = \array_reverse(self::valuesOrderedByPrecedence());

        $count = \count($values);

        for ($i = 0; $count - 1 > $i; ++$i) {
            $value = $values[$i];

            for ($j = $i + 1; $count > $j; ++$j) {
                $otherValue = $values[$j];

                $key = \sprintf(
                    '%s-greater-than-%s',
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
    }

    /**
     * @see https://semver.org/#spec-item-11
     * @see https://semver.org/#is-there-a-suggested-regular-expression-regex-to-check-a-semver-string
     * @see https://regex101.com/r/Ly7O1x/3/
     *
     * @return \Generator<string, array{0: string, 1: string, 2: int}>
     */
    public static function valuesWhereFirstValueIsEqualToSecondValue(): \Generator
    {
        $values = [
            [
                '1.0.0',
                '1.0.0+0.build.1-rc.10000aaa-kk-0.1',
            ],
            [
                '1.0.0-rc.1',
                '1.0.0-rc.1+build.1',
                '1.0.0-rc.1+build.9000',
            ],
            [
                '1.1.2',
                '1.1.2+meta',
                '1.1.2+meta-valid',
            ],
            [
                '1.2.3----RC-SNAPSHOT.12.9.1--.12',
                '1.2.3----RC-SNAPSHOT.12.9.1--.12+788',
            ],
            [
                '2.0.0',
                '2.0.0+build.1848',
            ],
        ];

        foreach ($values as $valuesWithBuildMetaData) {
            $count = \count($valuesWithBuildMetaData);

            for ($i = 0; $count - 1 > $i; ++$i) {
                $value = $valuesWithBuildMetaData[$i];

                for ($j = $i + 1; $j < $count; ++$j) {
                    $otherValue = $valuesWithBuildMetaData[$j];

                    $key = \sprintf(
                        '%s-equal-to-%s',
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
        }
    }

    /**
     * @see https://semver.org/#spec-item-11
     * @see https://semver.org/#is-there-a-suggested-regular-expression-regex-to-check-a-semver-string
     * @see https://regex101.com/r/Ly7O1x/3/
     * @see https://www.sfu.ca/sasdoc/sashtml/proc/z1epts.htm
     *
     * @return list<string>
     */
    private static function valuesOrderedByPrecedence(): array
    {
        return [
            '0.0.4',
            '1.0.0-0A.is.legal',
            '1.0.0-alpha',
            '1.0.0-alpha.1',
            '1.0.0-alpha.0valid',
            '1.0.0-alpha.beta',
            '1.0.0-alpha.beta.1',
            '1.0.0-alpha-a.b-c-somethinglong+build.1-aef.1-its-okay',
            '1.0.0-alpha0.valid',
            '1.0.0-beta',
            '1.0.0-rc.1+build.1',
            '1.0.0',
            '1.1.2-prerelease+meta',
            '1.1.2+meta',
            '1.1.7',
            '1.2.3----R-S.12.9.1--.12',
            '1.2.3----RC-SNAPSHOT.12.9.1--.12',
            '1.2.3-SNAPSHOT-123',
            '1.2.3-beta',
            '1.2.3',
            '2.0.0-rc.1+build.123',
            '2.0.0',
            '2.0.1-alpha.1227',
            '10.2.3-DEV-SNAPSHOT',
            '10.20.30',
            '99999999999999999999999.999999999999999999.99999999999999999',
        ];
    }
}
