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

namespace Ergebnis\Version\Test\Unit;

use Ergebnis\Version;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\Version\PreRelease
 *
 * @uses \Ergebnis\Version\Exception\InvalidPreRelease
 */
final class PreReleaseTest extends Framework\TestCase
{
    use Version\Test\Util\Helper;

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\PreReleaseProvider::invalid
     */
    public function testFromStringRejectsInvalidValue(string $value): void
    {
        $this->expectException(Version\Exception\InvalidPreRelease::class);

        Version\PreRelease::fromString($value);
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\PreReleaseProvider::valid
     */
    public function testFromStringReturnsPreRelease(string $value): void
    {
        $preRelease = Version\PreRelease::fromString($value);

        self::assertSame($value, $preRelease->toString());
    }

    public function testEmptyReturnsPreRelease(): void
    {
        $preRelease = Version\PreRelease::empty();

        self::assertSame('', $preRelease->toString());
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\PreReleaseProvider::valuesWhereFirstValueIsLessThanSecondValue
     */
    public function testCompareReturnsMinusOneWhenFirstValueIsLessThanSecondValue(
        string $value,
        string $otherValue
    ): void {
        $one = Version\PreRelease::fromString($value);
        $two = Version\PreRelease::fromString($otherValue);

        self::assertSame(-1, $one->compare($two));
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\PreReleaseProvider::valid
     */
    public function testCompareReturnsZeroWhenFirstValueIsIdenticalToSecondValue(string $value): void
    {
        $one = Version\PreRelease::fromString($value);
        $two = Version\PreRelease::fromString($value);

        self::assertSame(0, $one->compare($two));
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\PreReleaseProvider::valuesWhereFirstValueIsGreaterThanSecondValue
     */
    public function testCompareReturnsPlusOneWhenFirstValueIsGreaterThanSecondValue(
        string $value,
        string $otherValue
    ): void {
        $one = Version\PreRelease::fromString($value);
        $two = Version\PreRelease::fromString($otherValue);

        self::assertSame(1, $one->compare($two));
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\PreReleaseProvider::valid
     */
    public function testIsLessThanReturnsFalseWhenFirstValueIsIdenticalToSecondValue(string $value): void
    {
        $one = Version\PreRelease::fromString($value);
        $two = Version\PreRelease::fromString($value);

        self::assertFalse($one->isLessThan($two));
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\PreReleaseProvider::valuesWhereFirstValueIsGreaterThanSecondValue
     */
    public function testIsLessThanReturnsFalseWhenFirstValueIsGreaterThanSecondValue(
        string $value,
        string $otherValue
    ): void {
        $one = Version\PreRelease::fromString($value);
        $two = Version\PreRelease::fromString($otherValue);

        self::assertFalse($one->isLessThan($two));
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\PreReleaseProvider::valuesWhereFirstValueIsLessThanSecondValue
     */
    public function testIsLessThanReturnsTrueWhenFirstValueIsLessThanSecondValue(
        string $value,
        string $otherValue
    ): void {
        $one = Version\PreRelease::fromString($value);
        $two = Version\PreRelease::fromString($otherValue);

        self::assertTrue($one->isLessThan($two));
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\PreReleaseProvider::valid
     */
    public function testIsSmallerThanReturnsFalseWhenFirstValueIsIdenticalToSecondValue(string $value): void
    {
        $one = Version\PreRelease::fromString($value);
        $two = Version\PreRelease::fromString($value);

        self::assertFalse($one->isSmallerThan($two));
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\PreReleaseProvider::valuesWhereFirstValueIsGreaterThanSecondValue
     */
    public function testIsSmallerThanReturnsFalseWhenFirstValueIsGreaterThanSecondValue(
        string $value,
        string $otherValue
    ): void {
        $one = Version\PreRelease::fromString($value);
        $two = Version\PreRelease::fromString($otherValue);

        self::assertFalse($one->isSmallerThan($two));
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\PreReleaseProvider::valuesWhereFirstValueIsLessThanSecondValue
     */
    public function testIsSmallerThanReturnsTrueWhenFirstValueIsSmallerThanSecondValue(
        string $value,
        string $otherValue
    ): void {
        $one = Version\PreRelease::fromString($value);
        $two = Version\PreRelease::fromString($otherValue);

        self::assertTrue($one->isSmallerThan($two));
    }

    public function testEqualsReturnsFalseWhenValuesAreDifferent(): void
    {
        $faker = self::faker()->unique();

        $one = Version\PreRelease::fromString($faker->word());
        $two = Version\PreRelease::fromString($faker->word());

        self::assertFalse($one->equals($two));
    }

    public function testEqualsReturnsTrueWhenValueIsSame(): void
    {
        $value = self::faker()->word();

        $one = Version\PreRelease::fromString($value);
        $two = Version\PreRelease::fromString($value);

        self::assertTrue($one->equals($two));
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\PreReleaseProvider::valuesWhereFirstValueIsLessThanSecondValue
     */
    public function testIsGreaterThanReturnsFalseWhenFirstValueIsLessThanSecondValue(
        string $value,
        string $otherValue
    ): void {
        $one = Version\PreRelease::fromString($value);
        $two = Version\PreRelease::fromString($otherValue);

        self::assertFalse($one->isGreaterThan($two));
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\PreReleaseProvider::valid
     */
    public function testIsGreaterThanReturnsFalseWhenFirstValueIsIdenticalToSecondValue(string $value): void
    {
        $one = Version\PreRelease::fromString($value);
        $two = Version\PreRelease::fromString($value);

        self::assertFalse($one->isGreaterThan($two));
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\PreReleaseProvider::valuesWhereFirstValueIsGreaterThanSecondValue
     */
    public function testIsGreaterThanReturnsTrueWhenFirstValueIsGreaterThanSecondValue(
        string $value,
        string $otherValue
    ): void {
        $one = Version\PreRelease::fromString($value);
        $two = Version\PreRelease::fromString($otherValue);

        self::assertTrue($one->isGreaterThan($two));
    }
}
