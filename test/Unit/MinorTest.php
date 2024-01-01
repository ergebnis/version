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

use Ergebnis\DataProvider;
use Ergebnis\Version\Exception;
use Ergebnis\Version\Minor;
use Ergebnis\Version\Test;
use PHPUnit\Framework;

#[Framework\Attributes\CoversClass(Minor::class)]
#[Framework\Attributes\UsesClass(Exception\InvalidMinor::class)]
final class MinorTest extends Framework\TestCase
{
    use Test\Util\Helper;

    #[Framework\Attributes\DataProviderExternal(DataProvider\IntProvider::class, 'lessThanZero')]
    public function testFromIntRejectsInvalidIntValue(int $value): void
    {
        $this->expectException(Exception\InvalidMinor::class);

        Minor::fromInt($value);
    }

    #[Framework\Attributes\DataProviderExternal(DataProvider\IntProvider::class, 'zero')]
    #[Framework\Attributes\DataProviderExternal(DataProvider\IntProvider::class, 'greaterThanZero')]
    public function testFromIntReturnsMinor(int $value): void
    {
        $minor = Minor::fromInt($value);

        self::assertSame((string) $value, $minor->toString());
    }

    #[Framework\Attributes\DataProviderExternal(Test\DataProvider\NumberProvider::class, 'invalid')]
    public function testFromStringRejectsInvalidStringValue(string $value): void
    {
        $this->expectException(Exception\InvalidMinor::class);

        Minor::fromString($value);
    }

    #[Framework\Attributes\DataProviderExternal(Test\DataProvider\NumberProvider::class, 'valid')]
    public function testFromStringReturnsMinor(string $value): void
    {
        $minor = Minor::fromString($value);

        self::assertSame($value, $minor->toString());
    }

    #[Framework\Attributes\DataProviderExternal(Test\DataProvider\NumberProvider::class, 'valueAndBumpedValue')]
    public function testBumpReturnsMinorWithIncrementedValue(
        string $value,
        string $bumpedValue,
    ): void {
        $one = Minor::fromString($value);

        $two = $one->bump();

        self::assertNotSame($one, $two);
        self::assertSame($bumpedValue, $two->toString());
    }

    #[Framework\Attributes\DataProviderExternal(Test\DataProvider\NumberProvider::class, 'valuesWhereFirstValueIsSmallerThanSecondValue')]
    public function testCompareReturnsMinusOneWhenFirstValueIsSmallerThanSecondValue(
        string $firstValue,
        string $secondValue,
    ): void {
        $one = Minor::fromString($firstValue);
        $two = Minor::fromString($secondValue);

        self::assertSame(-1, $one->compare($two));
    }

    #[Framework\Attributes\DataProviderExternal(DataProvider\IntProvider::class, 'zero')]
    #[Framework\Attributes\DataProviderExternal(DataProvider\IntProvider::class, 'greaterThanZero')]
    public function testCompareReturnsZeroWhenFirstValueIsIdenticalToSecondValue(int $value): void
    {
        $one = Minor::fromInt($value);
        $two = Minor::fromInt($value);

        self::assertSame(0, $one->compare($two));
    }

    #[Framework\Attributes\DataProviderExternal(Test\DataProvider\NumberProvider::class, 'valuesWhereFirstValueIsGreaterThanSecondValue')]
    public function testCompareReturnsPlusOneWhenFirstValueIsGreaterThanSecondValue(
        string $firstValue,
        string $secondValue,
    ): void {
        $one = Minor::fromString($firstValue);
        $two = Minor::fromString($secondValue);

        self::assertSame(1, $one->compare($two));
    }

    #[Framework\Attributes\DataProviderExternal(DataProvider\IntProvider::class, 'zero')]
    #[Framework\Attributes\DataProviderExternal(DataProvider\IntProvider::class, 'greaterThanZero')]
    public function testIsSmallerThanReturnsFalseWhenFirstValueIsIdenticalToSecondValue(int $value): void
    {
        $one = Minor::fromInt($value);
        $two = Minor::fromInt($value);

        self::assertFalse($one->isSmallerThan($two));
    }

    #[Framework\Attributes\DataProviderExternal(Test\DataProvider\NumberProvider::class, 'valuesWhereFirstValueIsGreaterThanSecondValue')]
    public function testIsSmallerThanReturnsFalseWhenFirstValueIsGreaterThanSecondValue(
        string $firstValue,
        string $secondValue,
    ): void {
        $one = Minor::fromString($firstValue);
        $two = Minor::fromString($secondValue);

        self::assertFalse($one->isSmallerThan($two));
    }

    #[Framework\Attributes\DataProviderExternal(Test\DataProvider\NumberProvider::class, 'valuesWhereFirstValueIsSmallerThanSecondValue')]
    public function testIsSmallerThanReturnsTrueWhenFirstValueIsSmallerThanSecondValue(
        string $firstValue,
        string $secondValue,
    ): void {
        $one = Minor::fromString($firstValue);
        $two = Minor::fromString($secondValue);

        self::assertTrue($one->isSmallerThan($two));
    }

    public function testEqualsReturnsFalseWhenValuesAreDifferent(): void
    {
        $faker = self::faker()->unique();

        $one = Minor::fromInt($faker->numberBetween(0));
        $two = Minor::fromInt($faker->numberBetween(0));

        self::assertFalse($one->equals($two));
    }

    public function testEqualsReturnsFalseWhenValuesAreSame(): void
    {
        $value = self::faker()->numberBetween(0);

        $one = Minor::fromInt($value);
        $two = Minor::fromInt($value);

        self::assertTrue($one->equals($two));
    }

    #[Framework\Attributes\DataProviderExternal(Test\DataProvider\NumberProvider::class, 'valuesWhereFirstValueIsSmallerThanSecondValue')]
    public function testIsGreaterThanReturnsFalseWhenFirstValueIsSmallerThanSecondValue(
        string $firstValue,
        string $secondValue,
    ): void {
        $one = Minor::fromString($firstValue);
        $two = Minor::fromString($secondValue);

        self::assertFalse($one->isGreaterThan($two));
    }

    #[Framework\Attributes\DataProviderExternal(DataProvider\IntProvider::class, 'zero')]
    #[Framework\Attributes\DataProviderExternal(DataProvider\IntProvider::class, 'greaterThanZero')]
    public function testIsGreaterThanReturnsFalseWhenFirstValueIsIdenticalToSecondValue(int $value): void
    {
        $one = Minor::fromInt($value);
        $two = Minor::fromInt($value);

        self::assertFalse($one->isGreaterThan($two));
    }

    #[Framework\Attributes\DataProviderExternal(Test\DataProvider\NumberProvider::class, 'valuesWhereFirstValueIsGreaterThanSecondValue')]
    public function testIsGreaterThanReturnsTrueWhenFirstValueIsGreaterThanSecondValue(
        string $firstValue,
        string $secondValue,
    ): void {
        $one = Minor::fromString($firstValue);
        $two = Minor::fromString($secondValue);

        self::assertTrue($one->isGreaterThan($two));
    }
}
