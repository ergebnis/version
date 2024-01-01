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
use Ergebnis\Version\Major;
use Ergebnis\Version\Test;
use PHPUnit\Framework;

#[Framework\Attributes\CoversClass(Major::class)]
#[Framework\Attributes\UsesClass(Exception\InvalidMajor::class)]
final class MajorTest extends Framework\TestCase
{
    use Test\Util\Helper;

    #[Framework\Attributes\DataProviderExternal(DataProvider\IntProvider::class, 'lessThanZero')]
    public function testFromIntRejectsInvalidIntValue(int $value): void
    {
        $this->expectException(Exception\InvalidMajor::class);

        Major::fromInt($value);
    }

    #[Framework\Attributes\DataProviderExternal(DataProvider\IntProvider::class, 'zero')]
    #[Framework\Attributes\DataProviderExternal(DataProvider\IntProvider::class, 'greaterThanZero')]
    public function testFromIntReturnsMajor(int $value): void
    {
        $major = Major::fromInt($value);

        self::assertSame((string) $value, $major->toString());
    }

    #[Framework\Attributes\DataProviderExternal(Test\DataProvider\NumberProvider::class, 'invalid')]
    public function testFromStringRejectsInvalidStringValue(string $value): void
    {
        $this->expectException(Exception\InvalidMajor::class);

        Major::fromString($value);
    }

    #[Framework\Attributes\DataProviderExternal(Test\DataProvider\NumberProvider::class, 'valid')]
    public function testFromStringReturnsMajor(string $value): void
    {
        $major = Major::fromString($value);

        self::assertSame($value, $major->toString());
    }

    #[Framework\Attributes\DataProviderExternal(Test\DataProvider\NumberProvider::class, 'valueAndBumpedValue')]
    public function testBumpReturnsMajorWithIncrementedValue(
        string $value,
        string $bumpedValue,
    ): void {
        $one = Major::fromString($value);

        $two = $one->bump();

        self::assertNotSame($one, $two);
        self::assertSame($bumpedValue, $two->toString());
    }

    #[Framework\Attributes\DataProviderExternal(Test\DataProvider\NumberProvider::class, 'valuesWhereFirstValueIsSmallerThanSecondValue')]
    public function testCompareReturnsMinusOneWhenFirstValueIsSmallerThanSecondValue(
        string $firstValue,
        string $secondValue,
    ): void {
        $one = Major::fromString($firstValue);
        $two = Major::fromString($secondValue);

        self::assertSame(-1, $one->compare($two));
    }

    #[Framework\Attributes\DataProviderExternal(DataProvider\IntProvider::class, 'zero')]
    #[Framework\Attributes\DataProviderExternal(DataProvider\IntProvider::class, 'greaterThanZero')]
    public function testCompareReturnsZeroWhenFirstValueIsIdenticalToSecondValue(int $value): void
    {
        $one = Major::fromInt($value);
        $two = Major::fromInt($value);

        self::assertSame(0, $one->compare($two));
    }

    #[Framework\Attributes\DataProviderExternal(Test\DataProvider\NumberProvider::class, 'valuesWhereFirstValueIsGreaterThanSecondValue')]
    public function testCompareReturnsPlusOneWhenFirstValueIsGreaterThanSecondValue(
        string $firstValue,
        string $secondValue,
    ): void {
        $one = Major::fromString($firstValue);
        $two = Major::fromString($secondValue);

        self::assertSame(1, $one->compare($two));
    }

    #[Framework\Attributes\DataProviderExternal(DataProvider\IntProvider::class, 'zero')]
    #[Framework\Attributes\DataProviderExternal(DataProvider\IntProvider::class, 'greaterThanZero')]
    public function testIsSmallerThanReturnsFalseWhenFirstValueIsIdenticalToSecondValue(int $value): void
    {
        $one = Major::fromInt($value);
        $two = Major::fromInt($value);

        self::assertFalse($one->isSmallerThan($two));
    }

    #[Framework\Attributes\DataProviderExternal(Test\DataProvider\NumberProvider::class, 'valuesWhereFirstValueIsGreaterThanSecondValue')]
    public function testIsSmallerThanReturnsFalseWhenFirstValueIsGreaterThanSecondValue(
        string $firstValue,
        string $secondValue,
    ): void {
        $one = Major::fromString($firstValue);
        $two = Major::fromString($secondValue);

        self::assertFalse($one->isSmallerThan($two));
    }

    #[Framework\Attributes\DataProviderExternal(Test\DataProvider\NumberProvider::class, 'valuesWhereFirstValueIsSmallerThanSecondValue')]
    public function testIsSmallerThanReturnsTrueWhenFirstValueIsSmallerThanSecondValue(
        string $firstValue,
        string $secondValue,
    ): void {
        $one = Major::fromString($firstValue);
        $two = Major::fromString($secondValue);

        self::assertTrue($one->isSmallerThan($two));
    }

    public function testEqualsReturnsFalseWhenValuesAreDifferent(): void
    {
        $faker = self::faker()->unique();

        $one = Major::fromInt($faker->numberBetween(0));
        $two = Major::fromInt($faker->numberBetween(0));

        self::assertFalse($one->equals($two));
    }

    public function testEqualsReturnsFalseWhenValuesAreSame(): void
    {
        $value = self::faker()->numberBetween(0);

        $one = Major::fromInt($value);
        $two = Major::fromInt($value);

        self::assertTrue($one->equals($two));
    }

    #[Framework\Attributes\DataProviderExternal(Test\DataProvider\NumberProvider::class, 'valuesWhereFirstValueIsSmallerThanSecondValue')]
    public function testIsGreaterThanReturnsFalseWhenFirstValueIsSmallerThanSecondValue(
        string $firstValue,
        string $secondValue,
    ): void {
        $one = Major::fromString($firstValue);
        $two = Major::fromString($secondValue);

        self::assertFalse($one->isGreaterThan($two));
    }

    #[Framework\Attributes\DataProviderExternal(DataProvider\IntProvider::class, 'zero')]
    #[Framework\Attributes\DataProviderExternal(DataProvider\IntProvider::class, 'greaterThanZero')]
    public function testIsGreaterThanReturnsFalseWhenFirstValueIsIdenticalToSecondValue(int $value): void
    {
        $one = Major::fromInt($value);
        $two = Major::fromInt($value);

        self::assertFalse($one->isGreaterThan($two));
    }

    #[Framework\Attributes\DataProviderExternal(Test\DataProvider\NumberProvider::class, 'valuesWhereFirstValueIsGreaterThanSecondValue')]
    public function testIsGreaterThanReturnsTrueWhenFirstValueIsGreaterThanSecondValue(
        string $firstValue,
        string $secondValue,
    ): void {
        $one = Major::fromString($firstValue);
        $two = Major::fromString($secondValue);

        self::assertTrue($one->isGreaterThan($two));
    }
}
