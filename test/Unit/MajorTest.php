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

use Ergebnis\DataProvider;
use Ergebnis\Version;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\Version\Major
 *
 * @uses \Ergebnis\Version\Exception\InvalidMajor
 */
final class MajorTest extends Framework\TestCase
{
    use Version\Test\Util\Helper;

    /**
     * @dataProvider \Ergebnis\DataProvider\IntProvider::lessThanZero
     */
    public function testFromIntRejectsInvalidIntValue(int $value): void
    {
        $this->expectException(Version\Exception\InvalidMajor::class);

        Version\Major::fromInt($value);
    }

    /**
     * @dataProvider \Ergebnis\DataProvider\IntProvider::greaterThanZero
     * @dataProvider \Ergebnis\DataProvider\IntProvider::zero
     */
    public function testFromIntReturnsMajor(int $value): void
    {
        $major = Version\Major::fromInt($value);

        self::assertSame((string) $value, $major->toString());
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\NumberProvider::invalid
     */
    public function testFromStringRejectsInvalidStringValue(string $value): void
    {
        $this->expectException(Version\Exception\InvalidMajor::class);

        Version\Major::fromString($value);
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\NumberProvider::valid
     */
    public function testFromStringReturnsMajor(string $value): void
    {
        $major = Version\Major::fromString($value);

        self::assertSame($value, $major->toString());
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\NumberProvider::valueAndBumpedValue
     */
    public function testBumpReturnsMajorWithIncrementedValue(
        string $value,
        string $bumpedValue
    ): void {
        $one = Version\Major::fromString($value);

        $two = $one->bump();

        self::assertNotSame($one, $two);
        self::assertSame($bumpedValue, $two->toString());
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\NumberProvider::valuesWhereFirstValueIsLessThanSecondValue
     */
    public function testCompareReturnsMinusOneWhenFirstValueIsLessThanSecondValue(
        string $valueOne,
        string $valueTwo
    ): void {
        $one = Version\Major::fromString($valueOne);
        $two = Version\Major::fromString($valueTwo);

        self::assertSame(-1, $one->compare($two));
    }

    /**
     * @dataProvider \Ergebnis\DataProvider\IntProvider::greaterThanZero
     * @dataProvider \Ergebnis\DataProvider\IntProvider::zero
     */
    public function testCompareReturnsZeroWhenFirstValueIsIdenticalToSecondValue(int $value): void
    {
        $one = Version\Major::fromInt($value);
        $two = Version\Major::fromInt($value);

        self::assertSame(0, $one->compare($two));
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\NumberProvider::valuesWhereFirstValueIsGreaterThanSecondValue
     */
    public function testCompareReturnsPlusOneWhenFirstValueIsGreaterThanSecondValue(
        string $valueOne,
        string $valueTwo
    ): void {
        $one = Version\Major::fromString($valueOne);
        $two = Version\Major::fromString($valueTwo);

        self::assertSame(1, $one->compare($two));
    }

    /**
     * @dataProvider \Ergebnis\DataProvider\IntProvider::greaterThanZero
     * @dataProvider \Ergebnis\DataProvider\IntProvider::zero
     */
    public function testIsLessThanReturnsFalseWhenFirstValueIsIdenticalToSecondValue(int $value): void
    {
        $one = Version\Major::fromInt($value);
        $two = Version\Major::fromInt($value);

        self::assertFalse($one->isLessThan($two));
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\NumberProvider::valuesWhereFirstValueIsGreaterThanSecondValue
     */
    public function testIsLessThanReturnsFalseWhenFirstValueIsGreaterThanSecondValue(
        string $valueOne,
        string $valueTwo
    ): void {
        $one = Version\Major::fromString($valueOne);
        $two = Version\Major::fromString($valueTwo);

        self::assertFalse($one->isLessThan($two));
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\NumberProvider::valuesWhereFirstValueIsLessThanSecondValue
     */
    public function testIsLessThanReturnsTrueWhenFirstValueIsLessThanSecondValue(
        string $valueOne,
        string $valueTwo
    ): void {
        $one = Version\Major::fromString($valueOne);
        $two = Version\Major::fromString($valueTwo);

        self::assertTrue($one->isLessThan($two));
    }

    /**
     * @dataProvider \Ergebnis\DataProvider\IntProvider::greaterThanZero
     * @dataProvider \Ergebnis\DataProvider\IntProvider::zero
     */
    public function testIsSmallerThanReturnsFalseWhenFirstValueIsIdenticalToSecondValue(int $value): void
    {
        $one = Version\Major::fromInt($value);
        $two = Version\Major::fromInt($value);

        self::assertFalse($one->isSmallerThan($two));
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\NumberProvider::valuesWhereFirstValueIsGreaterThanSecondValue
     */
    public function testIsSmallerThanReturnsFalseWhenFirstValueIsGreaterThanSecondValue(
        string $valueOne,
        string $valueTwo
    ): void {
        $one = Version\Major::fromString($valueOne);
        $two = Version\Major::fromString($valueTwo);

        self::assertFalse($one->isSmallerThan($two));
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\NumberProvider::valuesWhereFirstValueIsLessThanSecondValue
     */
    public function testIsSmallerThanReturnsTrueWhenFirstValueIsSmallerThanSecondValue(
        string $valueOne,
        string $valueTwo
    ): void {
        $one = Version\Major::fromString($valueOne);
        $two = Version\Major::fromString($valueTwo);

        self::assertTrue($one->isSmallerThan($two));
    }

    public function testEqualsReturnsFalseWhenValuesAreDifferent(): void
    {
        $faker = self::faker()->unique();

        $one = Version\Major::fromInt($faker->numberBetween(0));
        $two = Version\Major::fromInt($faker->numberBetween(0));

        self::assertFalse($one->equals($two));
    }

    public function testEqualsReturnsFalseWhenValuesAreSame(): void
    {
        $value = self::faker()->numberBetween(0);

        $one = Version\Major::fromInt($value);
        $two = Version\Major::fromInt($value);

        self::assertTrue($one->equals($two));
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\NumberProvider::valuesWhereFirstValueIsLessThanSecondValue
     */
    public function testIsGreaterThanReturnsFalseWhenFirstValueIsLessThanSecondValue(
        string $valueOne,
        string $valueTwo
    ): void {
        $one = Version\Major::fromString($valueOne);
        $two = Version\Major::fromString($valueTwo);

        self::assertFalse($one->isGreaterThan($two));
    }

    /**
     * @dataProvider \Ergebnis\DataProvider\IntProvider::greaterThanZero
     * @dataProvider \Ergebnis\DataProvider\IntProvider::zero
     */
    public function testIsGreaterThanReturnsFalseWhenFirstValueIsIdenticalToSecondValue(int $value): void
    {
        $one = Version\Major::fromInt($value);
        $two = Version\Major::fromInt($value);

        self::assertFalse($one->isGreaterThan($two));
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\NumberProvider::valuesWhereFirstValueIsGreaterThanSecondValue
     */
    public function testIsGreaterThanReturnsTrueWhenFirstValueIsGreaterThanSecondValue(
        string $valueOne,
        string $valueTwo
    ): void {
        $one = Version\Major::fromString($valueOne);
        $two = Version\Major::fromString($valueTwo);

        self::assertTrue($one->isGreaterThan($two));
    }
}
