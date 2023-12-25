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

use Ergebnis\Version\BuildMetaData;
use Ergebnis\Version\Exception;
use Ergebnis\Version\Test;
use PHPUnit\Framework;

#[Framework\Attributes\CoversClass(BuildMetaData::class)]
#[Framework\Attributes\UsesClass(Exception\InvalidBuildMetaData::class)]
final class BuildMetaDataTest extends Framework\TestCase
{
    use Test\Util\Helper;

    #[Framework\Attributes\DataProvider('provideInvalidValue')]
    public function testFromStringRejectsInvalidValue(string $value): void
    {
        $this->expectException(Exception\InvalidBuildMetaData::class);

        BuildMetaData::fromString($value);
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
            'meta+meta',
            'whatever+meta+meta',
        ];

        foreach ($values as $value) {
            yield $value => [
                $value,
            ];
        }
    }

    #[Framework\Attributes\DataProvider('provideValidValue')]
    public function testFromStringReturnsBuildMetaData(string $value): void
    {
        $buildMetaData = BuildMetaData::fromString($value);

        self::assertSame($value, $buildMetaData->toString());
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

        foreach ($values as $value) {
            yield $value => [
                $value,
            ];
        }
    }

    public function testEmptyReturnsBuildMetaData(): void
    {
        $buildMetaData = BuildMetaData::empty();

        self::assertSame('', $buildMetaData->toString());
    }

    public function testEqualsReturnsFalseWhenValuesAreDifferent(): void
    {
        $faker = self::faker()->unique();

        $one = BuildMetaData::fromString($faker->word());
        $two = BuildMetaData::fromString($faker->word());

        self::assertFalse($one->equals($two));
    }

    public function testEqualsReturnsTrueWhenValueIsSame(): void
    {
        $value = self::faker()->word();

        $one = BuildMetaData::fromString($value);
        $two = BuildMetaData::fromString($value);

        self::assertTrue($one->equals($two));
    }
}
