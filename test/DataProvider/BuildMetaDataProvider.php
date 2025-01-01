<?php

declare(strict_types=1);

/**
 * Copyright (c) 2023-2025 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/version
 */

namespace Ergebnis\Version\Test\DataProvider;

final class BuildMetaDataProvider
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
            'meta+meta',
            'whatever+meta+meta',
        ];

        foreach ($values as $value) {
            yield $value => [
                $value,
            ];
        }

        foreach (self::values() as $value) {
            $key = \sprintf(
                '%s-with-leading-space',
                $value,
            );

            yield $key => [
                \sprintf(
                    ' %s',
                    $value,
                ),
            ];
        }

        foreach (self::values() as $value) {
            $key = \sprintf(
                '%s-with-trailing-space',
                $value,
            );

            yield $key => [
                \sprintf(
                    '%s ',
                    $value,
                ),
            ];
        }

        foreach (self::values() as $value) {
            $key = \sprintf(
                '%s-with-leading-and-trailing-space',
                $value,
            );

            yield $key => [
                \sprintf(
                    ' %s ',
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
        foreach (self::values() as $value) {
            yield $value => [
                $value,
            ];
        }
    }

    /**
     * @return list<string>
     */
    private static function values(): array
    {
        return [
            'meta',
            'meta-valid',
            'build.1-aef.1-its-okay',
            'build.1',
            'build.123',
            'build.1848',
            'beta',
            '788',
            '0.build.1-rc.10000aaa-kk-0.1',
        ];
    }
}
