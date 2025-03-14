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

namespace Ergebnis\Version\Test\Unit;

use Ergebnis\Version\BuildMetaData;
use Ergebnis\Version\Exception;
use Ergebnis\Version\Test;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\Version\BuildMetaData
 *
 * @uses \Ergebnis\Version\Exception\InvalidBuildMetaData
 */
final class BuildMetaDataTest extends Framework\TestCase
{
    use Test\Util\Helper;

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\BuildMetaDataProvider::invalid
     */
    public function testFromStringRejectsInvalidValue(string $value): void
    {
        $this->expectException(Exception\InvalidBuildMetaData::class);

        BuildMetaData::fromString($value);
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\BuildMetaDataProvider::valid
     */
    public function testFromStringReturnsBuildMetaData(string $value): void
    {
        $buildMetaData = BuildMetaData::fromString($value);

        self::assertSame($value, $buildMetaData->toString());
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
