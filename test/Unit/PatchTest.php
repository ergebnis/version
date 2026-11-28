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
 * @covers \Ergebnis\Version\Patch
 *
 * @uses \Ergebnis\Version\Exception\InvalidPatch
 */
final class PatchTest extends Framework\TestCase
{
    use Version\Test\Util\Helper;

    /**
     * @dataProvider \Ergebnis\DataProvider\IntProvider::lessThanZero
     */
    public function testFromIntRejectsInvalidIntValue(int $value): void
    {
        $this->expectException(Version\Exception\InvalidPatch::class);

        Version\Patch::fromInt($value);
    }

    /**
     * @dataProvider \Ergebnis\DataProvider\IntProvider::greaterThanZero
     * @dataProvider \Ergebnis\DataProvider\IntProvider::zero
     */
    public function testFromIntReturnsPatch(int $value): void
    {
        $patch = Version\Patch::fromInt($value);

        self::assertSame((string) $value, $patch->toString());
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\NumberProvider::invalid
     */
    public function testFromStringRejectsInvalidStringValue(string $value): void
    {
        $this->expectException(Version\Exception\InvalidPatch::class);

        Version\Patch::fromString($value);
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\NumberProvider::valid
     */
    public function testFromStringReturnsPatch(string $value): void
    {
        $patch = Version\Patch::fromString($value);

        self::assertSame($value, $patch->toString());
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\NumberProvider::valueAndBumpedValue
     */
    public function testBumpReturnsPatchWithIncrementedValue(
        string $value,
        string $bumpedValue
    ): void {
        $one = Version\Patch::fromString($value);

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
        $one = Version\Patch::fromString($valueOne);
        $two = Version\Patch::fromString($valueTwo);

        self::assertSame(-1, $one->compare($two));
    }

    /**
     * @dataProvider \Ergebnis\DataProvider\IntProvider::greaterThanZero
     * @dataProvider \Ergebnis\DataProvider\IntProvider::zero
     */
    public function testCompareReturnsZeroWhenFirstValueIsIdenticalToSecondValue(int $value): void
    {
        $one = Version\Patch::fromInt($value);
        $two = Version\Patch::fromInt($value);

        self::assertSame(0, $one->compare($two));
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\NumberProvider::valuesWhereFirstValueIsGreaterThanSecondValue
     */
    public function testCompareReturnsPlusOneWhenFirstValueIsGreaterThanSecondValue(
        string $valueOne,
        string $valueTwo
    ): void {
        $one = Version\Patch::fromString($valueOne);
        $two = Version\Patch::fromString($valueTwo);

        self::assertSame(1, $one->compare($two));
    }

    /**
     * @dataProvider \Ergebnis\DataProvider\IntProvider::greaterThanZero
     * @dataProvider \Ergebnis\DataProvider\IntProvider::zero
     */
    public function testIsLessThanReturnsFalseWhenFirstValueIsIdenticalToSecondValue(int $value): void
    {
        $one = Version\Patch::fromInt($value);
        $two = Version\Patch::fromInt($value);

        self::assertFalse($one->isLessThan($two));
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\NumberProvider::valuesWhereFirstValueIsGreaterThanSecondValue
     */
    public function testIsLessThanReturnsFalseWhenFirstValueIsGreaterThanSecondValue(
        string $valueOne,
        string $valueTwo
    ): void {
        $one = Version\Patch::fromString($valueOne);
        $two = Version\Patch::fromString($valueTwo);

        self::assertFalse($one->isLessThan($two));
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\NumberProvider::valuesWhereFirstValueIsLessThanSecondValue
     */
    public function testIsLessThanReturnsTrueWhenFirstValueIsLessThanSecondValue(
        string $valueOne,
        string $valueTwo
    ): void {
        $one = Version\Patch::fromString($valueOne);
        $two = Version\Patch::fromString($valueTwo);

        self::assertTrue($one->isLessThan($two));
    }

    /**
     * @dataProvider \Ergebnis\DataProvider\IntProvider::greaterThanZero
     * @dataProvider \Ergebnis\DataProvider\IntProvider::zero
     */
    public function testIsSmallerThanReturnsFalseWhenFirstValueIsIdenticalToSecondValue(int $value): void
    {
        $one = Version\Patch::fromInt($value);
        $two = Version\Patch::fromInt($value);

        self::assertFalse($one->isSmallerThan($two));
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\NumberProvider::valuesWhereFirstValueIsGreaterThanSecondValue
     */
    public function testIsSmallerThanReturnsFalseWhenFirstValueIsGreaterThanSecondValue(
        string $valueOne,
        string $valueTwo
    ): void {
        $one = Version\Patch::fromString($valueOne);
        $two = Version\Patch::fromString($valueTwo);

        self::assertFalse($one->isSmallerThan($two));
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\NumberProvider::valuesWhereFirstValueIsLessThanSecondValue
     */
    public function testIsSmallerThanReturnsTrueWhenFirstValueIsSmallerThanSecondValue(
        string $valueOne,
        string $valueTwo
    ): void {
        $one = Version\Patch::fromString($valueOne);
        $two = Version\Patch::fromString($valueTwo);

        self::assertTrue($one->isSmallerThan($two));
    }

    public function testEqualsReturnsFalseWhenValuesAreDifferent(): void
    {
        $faker = self::faker()->unique();

        $one = Version\Patch::fromInt($faker->numberBetween(0));
        $two = Version\Patch::fromInt($faker->numberBetween(0));

        self::assertFalse($one->equals($two));
    }

    public function testEqualsReturnsFalseWhenValuesAreSame(): void
    {
        $value = self::faker()->numberBetween(0);

        $one = Version\Patch::fromInt($value);
        $two = Version\Patch::fromInt($value);

        self::assertTrue($one->equals($two));
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\NumberProvider::valuesWhereFirstValueIsLessThanSecondValue
     */
    public function testIsGreaterThanReturnsFalseWhenFirstValueIsLessThanSecondValue(
        string $valueOne,
        string $valueTwo
    ): void {
        $one = Version\Patch::fromString($valueOne);
        $two = Version\Patch::fromString($valueTwo);

        self::assertFalse($one->isGreaterThan($two));
    }

    /**
     * @dataProvider \Ergebnis\DataProvider\IntProvider::greaterThanZero
     * @dataProvider \Ergebnis\DataProvider\IntProvider::zero
     */
    public function testIsGreaterThanReturnsFalseWhenFirstValueIsIdenticalToSecondValue(int $value): void
    {
        $one = Version\Patch::fromInt($value);
        $two = Version\Patch::fromInt($value);

        self::assertFalse($one->isGreaterThan($two));
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\NumberProvider::valuesWhereFirstValueIsGreaterThanSecondValue
     */
    public function testIsGreaterThanReturnsTrueWhenFirstValueIsGreaterThanSecondValue(
        string $valueOne,
        string $valueTwo
    ): void {
        $one = Version\Patch::fromString($valueOne);
        $two = Version\Patch::fromString($valueTwo);

        self::assertTrue($one->isGreaterThan($two));
    }
}
