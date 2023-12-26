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

namespace Ergebnis\Version\Test\Unit;

use Ergebnis\Version\Exception;
use Ergebnis\Version\PreRelease;
use Ergebnis\Version\Test;
use PHPUnit\Framework;

#[Framework\Attributes\CoversClass(PreRelease::class)]
#[Framework\Attributes\UsesClass(Exception\InvalidPreRelease::class)]
final class PreReleaseTest extends Framework\TestCase
{
    use Test\Util\Helper;

    #[Framework\Attributes\DataProvider('provideInvalidValue')]
    public function testFromStringRejectsInvalidValue(string $value): void
    {
        $this->expectException(Exception\InvalidPreRelease::class);

        PreRelease::fromString($value);
    }

    /**
     * @see https://semver.org/#is-there-a-suggested-regular-expression-regex-to-check-a-semver-string
     * @see https://regex101.com/r/Ly7O1x/3/
     *
     * @return \Generator<string, array{0: string}>
     */
    public static function provideInvalidValue(): \Generator
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

    #[Framework\Attributes\DataProvider('provideValidValue')]
    public function testFromStringReturnsPreRelease(string $value): void
    {
        $preRelease = PreRelease::fromString($value);

        self::assertSame($value, $preRelease->toString());
    }

    /**
     * @see https://semver.org/#is-there-a-suggested-regular-expression-regex-to-check-a-semver-string
     * @see https://regex101.com/r/Ly7O1x/3/
     *
     * @return \Generator<string, array{0: string}>
     */
    public static function provideValidValue(): \Generator
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

    public function testEmptyReturnsPreRelease(): void
    {
        $preRelease = PreRelease::empty();

        self::assertSame('', $preRelease->toString());
    }

    #[Framework\Attributes\DataProviderExternal(Test\DataProvider\PreReleaseProvider::class, 'valueOtherValueAndResult')]
    public function testCompareReturnsResultOfComparingValues(
        string $value,
        string $otherValue,
        int $result,
    ): void {
        $one = PreRelease::fromString($value);
        $two = PreRelease::fromString($otherValue);

        self::assertSame($result, $one->compare($two));
    }

    public function testEqualsReturnsFalseWhenValuesAreDifferent(): void
    {
        $faker = self::faker()->unique();

        $one = PreRelease::fromString($faker->word());
        $two = PreRelease::fromString($faker->word());

        self::assertFalse($one->equals($two));
    }

    public function testEqualsReturnsTrueWhenValueIsSame(): void
    {
        $value = self::faker()->word();

        $one = PreRelease::fromString($value);
        $two = PreRelease::fromString($value);

        self::assertTrue($one->equals($two));
    }
}
