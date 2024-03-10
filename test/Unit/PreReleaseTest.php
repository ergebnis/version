<?php

declare(strict_types=1);

/**
 * Copyright (c) 2023-2024 Andreas Möller
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

/**
 * @covers \Ergebnis\Version\PreRelease
 *
 * @uses \Ergebnis\Version\Exception\InvalidPreRelease
 */
final class PreReleaseTest extends Framework\TestCase
{
    use Test\Util\Helper;

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\PreReleaseProvider::invalid
     */
    public function testFromStringRejectsInvalidValue(string $value): void
    {
        $this->expectException(Exception\InvalidPreRelease::class);

        PreRelease::fromString($value);
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\PreReleaseProvider::valid
     */
    public function testFromStringReturnsPreRelease(string $value): void
    {
        $preRelease = PreRelease::fromString($value);

        self::assertSame($value, $preRelease->toString());
    }

    public function testEmptyReturnsPreRelease(): void
    {
        $preRelease = PreRelease::empty();

        self::assertSame('', $preRelease->toString());
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\PreReleaseProvider::valuesWhereFirstValueIsSmallerThanSecondValue
     */
    public function testCompareReturnsMinusOneWhenFirstValueIsSmallerThanSecondValue(
        string $value,
        string $otherValue
    ): void {
        $one = PreRelease::fromString($value);
        $two = PreRelease::fromString($otherValue);

        self::assertSame(-1, $one->compare($two));
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\PreReleaseProvider::valid
     */
    public function testCompareReturnsZeroWhenFirstValueIsIdenticalToSecondValue(string $value): void
    {
        $one = PreRelease::fromString($value);
        $two = PreRelease::fromString($value);

        self::assertSame(0, $one->compare($two));
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\PreReleaseProvider::valuesWhereFirstValueIsGreaterThanSecondValue
     */
    public function testCompareReturnsPlusOneWhenFirstValueIsGreaterThanSecondValue(
        string $value,
        string $otherValue
    ): void {
        $one = PreRelease::fromString($value);
        $two = PreRelease::fromString($otherValue);

        self::assertSame(1, $one->compare($two));
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\PreReleaseProvider::valid
     */
    public function testIsSmallerThanReturnsFalseWhenFirstValueIsIdenticalToSecondValue(string $value): void
    {
        $one = PreRelease::fromString($value);
        $two = PreRelease::fromString($value);

        self::assertFalse($one->isSmallerThan($two));
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\PreReleaseProvider::valuesWhereFirstValueIsGreaterThanSecondValue
     */
    public function testIsSmallerThanReturnsFalseWhenFirstValueIsGreaterThanSecondValue(
        string $value,
        string $otherValue
    ): void {
        $one = PreRelease::fromString($value);
        $two = PreRelease::fromString($otherValue);

        self::assertFalse($one->isSmallerThan($two));
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\PreReleaseProvider::valuesWhereFirstValueIsSmallerThanSecondValue
     */
    public function testIsSmallerThanReturnsTrueWhenFirstValueIsSmallerThanSecondValue(
        string $value,
        string $otherValue
    ): void {
        $one = PreRelease::fromString($value);
        $two = PreRelease::fromString($otherValue);

        self::assertTrue($one->isSmallerThan($two));
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

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\PreReleaseProvider::valuesWhereFirstValueIsSmallerThanSecondValue
     */
    public function testIsGreaterThanReturnsFalseWhenFirstValueIsSmallerThanSecondValue(
        string $value,
        string $otherValue
    ): void {
        $one = PreRelease::fromString($value);
        $two = PreRelease::fromString($otherValue);

        self::assertFalse($one->isGreaterThan($two));
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\PreReleaseProvider::valid
     */
    public function testIsGreaterThanReturnsFalseWhenFirstValueIsIdenticalToSecondValue(string $value): void
    {
        $one = PreRelease::fromString($value);
        $two = PreRelease::fromString($value);

        self::assertFalse($one->isGreaterThan($two));
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\PreReleaseProvider::valuesWhereFirstValueIsGreaterThanSecondValue
     */
    public function testIsGreaterThanReturnsTrueWhenFirstValueIsGreaterThanSecondValue(
        string $value,
        string $otherValue
    ): void {
        $one = PreRelease::fromString($value);
        $two = PreRelease::fromString($otherValue);

        self::assertTrue($one->isGreaterThan($two));
    }
}
