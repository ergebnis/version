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

use Ergebnis\DataProvider;
use Ergebnis\Version\Exception;
use Ergebnis\Version\Patch;
use Ergebnis\Version\Test;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\Version\Patch
 *
 * @uses \Ergebnis\Version\Exception\InvalidPatch
 */
final class PatchTest extends Framework\TestCase
{
    use Test\Util\Helper;

    /**
     * @dataProvider \Ergebnis\DataProvider\IntProvider::lessThanZero
     */
    public function testFromIntRejectsInvalidIntValue(int $value): void
    {
        $this->expectException(Exception\InvalidPatch::class);

        Patch::fromInt($value);
    }

    /**
     * @dataProvider \Ergebnis\DataProvider\IntProvider::greaterThanZero
     * @dataProvider \Ergebnis\DataProvider\IntProvider::zero
     */
    public function testFromIntReturnsPatch(int $value): void
    {
        $patch = Patch::fromInt($value);

        self::assertSame((string) $value, $patch->toString());
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\NumberProvider::invalid
     */
    public function testFromStringRejectsInvalidStringValue(string $value): void
    {
        $this->expectException(Exception\InvalidPatch::class);

        Patch::fromString($value);
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\NumberProvider::valid
     */
    public function testFromStringReturnsPatch(string $value): void
    {
        $patch = Patch::fromString($value);

        self::assertSame($value, $patch->toString());
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\NumberProvider::valueAndBumpedValue
     */
    public function testBumpReturnsPatchWithIncrementedValue(
        string $value,
        string $bumpedValue
    ): void {
        $one = Patch::fromString($value);

        $two = $one->bump();

        self::assertNotSame($one, $two);
        self::assertSame($bumpedValue, $two->toString());
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\NumberProvider::valuesWhereFirstValueIsSmallerThanSecondValue
     */
    public function testCompareReturnsMinusOneWhenFirstValueIsSmallerThanSecondValue(
        string $firstValue,
        string $secondValue
    ): void {
        $one = Patch::fromString($firstValue);
        $two = Patch::fromString($secondValue);

        self::assertSame(-1, $one->compare($two));
    }

    /**
     * @dataProvider \Ergebnis\DataProvider\IntProvider::greaterThanZero
     * @dataProvider \Ergebnis\DataProvider\IntProvider::zero
     */
    public function testCompareReturnsZeroWhenFirstValueIsIdenticalToSecondValue(int $value): void
    {
        $one = Patch::fromInt($value);
        $two = Patch::fromInt($value);

        self::assertSame(0, $one->compare($two));
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\NumberProvider::valuesWhereFirstValueIsGreaterThanSecondValue
     */
    public function testCompareReturnsPlusOneWhenFirstValueIsGreaterThanSecondValue(
        string $firstValue,
        string $secondValue
    ): void {
        $one = Patch::fromString($firstValue);
        $two = Patch::fromString($secondValue);

        self::assertSame(1, $one->compare($two));
    }

    /**
     * @dataProvider \Ergebnis\DataProvider\IntProvider::greaterThanZero
     * @dataProvider \Ergebnis\DataProvider\IntProvider::zero
     */
    public function testIsSmallerThanReturnsFalseWhenFirstValueIsIdenticalToSecondValue(int $value): void
    {
        $one = Patch::fromInt($value);
        $two = Patch::fromInt($value);

        self::assertFalse($one->isSmallerThan($two));
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\NumberProvider::valuesWhereFirstValueIsGreaterThanSecondValue
     */
    public function testIsSmallerThanReturnsFalseWhenFirstValueIsGreaterThanSecondValue(
        string $firstValue,
        string $secondValue
    ): void {
        $one = Patch::fromString($firstValue);
        $two = Patch::fromString($secondValue);

        self::assertFalse($one->isSmallerThan($two));
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\NumberProvider::valuesWhereFirstValueIsSmallerThanSecondValue
     */
    public function testIsSmallerThanReturnsTrueWhenFirstValueIsSmallerThanSecondValue(
        string $firstValue,
        string $secondValue
    ): void {
        $one = Patch::fromString($firstValue);
        $two = Patch::fromString($secondValue);

        self::assertTrue($one->isSmallerThan($two));
    }

    public function testEqualsReturnsFalseWhenValuesAreDifferent(): void
    {
        $faker = self::faker()->unique();

        $one = Patch::fromInt($faker->numberBetween(0));
        $two = Patch::fromInt($faker->numberBetween(0));

        self::assertFalse($one->equals($two));
    }

    public function testEqualsReturnsFalseWhenValuesAreSame(): void
    {
        $value = self::faker()->numberBetween(0);

        $one = Patch::fromInt($value);
        $two = Patch::fromInt($value);

        self::assertTrue($one->equals($two));
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\NumberProvider::valuesWhereFirstValueIsSmallerThanSecondValue
     */
    public function testIsGreaterThanReturnsFalseWhenFirstValueIsSmallerThanSecondValue(
        string $firstValue,
        string $secondValue
    ): void {
        $one = Patch::fromString($firstValue);
        $two = Patch::fromString($secondValue);

        self::assertFalse($one->isGreaterThan($two));
    }

    /**
     * @dataProvider \Ergebnis\DataProvider\IntProvider::greaterThanZero
     * @dataProvider \Ergebnis\DataProvider\IntProvider::zero
     */
    public function testIsGreaterThanReturnsFalseWhenFirstValueIsIdenticalToSecondValue(int $value): void
    {
        $one = Patch::fromInt($value);
        $two = Patch::fromInt($value);

        self::assertFalse($one->isGreaterThan($two));
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\NumberProvider::valuesWhereFirstValueIsGreaterThanSecondValue
     */
    public function testIsGreaterThanReturnsTrueWhenFirstValueIsGreaterThanSecondValue(
        string $firstValue,
        string $secondValue
    ): void {
        $one = Patch::fromString($firstValue);
        $two = Patch::fromString($secondValue);

        self::assertTrue($one->isGreaterThan($two));
    }
}
