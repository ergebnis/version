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

use Ergebnis\DataProvider;
use Ergebnis\Version\Exception;
use Ergebnis\Version\Patch;
use Ergebnis\Version\Test;
use PHPUnit\Framework;

#[Framework\Attributes\CoversClass(Patch::class)]
#[Framework\Attributes\UsesClass(Exception\InvalidPatch::class)]
final class PatchTest extends Framework\TestCase
{
    use Test\Util\Helper;

    #[Framework\Attributes\DataProviderExternal(DataProvider\IntProvider::class, 'lessThanZero')]
    public function testFromIntRejectsInvalidIntValue(int $value): void
    {
        $this->expectException(Exception\InvalidPatch::class);

        Patch::fromInt($value);
    }

    #[Framework\Attributes\DataProviderExternal(DataProvider\IntProvider::class, 'zero')]
    #[Framework\Attributes\DataProviderExternal(DataProvider\IntProvider::class, 'greaterThanZero')]
    public function testFromIntReturnsPatch(int $value): void
    {
        $patch = Patch::fromInt($value);

        self::assertSame((string) $value, $patch->toString());
    }

    #[Framework\Attributes\DataProviderExternal(Test\DataProvider\NumberProvider::class, 'invalid')]
    public function testFromStringRejectsInvalidStringValue(string $value): void
    {
        $this->expectException(Exception\InvalidPatch::class);

        Patch::fromString($value);
    }

    #[Framework\Attributes\DataProviderExternal(Test\DataProvider\NumberProvider::class, 'valid')]
    public function testFromStringReturnsPatch(string $value): void
    {
        $patch = Patch::fromString($value);

        self::assertSame($value, $patch->toString());
    }

    #[Framework\Attributes\DataProviderExternal(Test\DataProvider\NumberProvider::class, 'valueAndBumpedValue')]
    public function testBumpReturnsPatchWithIncrementedValue(
        string $value,
        string $bumpedValue,
    ): void {
        $one = Patch::fromString($value);

        $two = $one->bump();

        self::assertNotSame($one, $two);
        self::assertSame($bumpedValue, $two->toString());
    }

    #[Framework\Attributes\DataProviderExternal(Test\DataProvider\NumberProvider::class, 'valuesWhereFirstValueIsSmallerThanSecondValue')]
    public function testCompareReturnsMinusOneWhenFirstValueIsSmallerThanSecondValue(
        string $firstValue,
        string $secondValue,
    ): void {
        $one = Patch::fromString($firstValue);
        $two = Patch::fromString($secondValue);

        self::assertSame(-1, $one->compare($two));
    }

    #[Framework\Attributes\DataProviderExternal(DataProvider\IntProvider::class, 'zero')]
    #[Framework\Attributes\DataProviderExternal(DataProvider\IntProvider::class, 'greaterThanZero')]
    public function testCompareReturnsZeroWhenFirstValueIsIdenticalToSecondValue(int $value): void
    {
        $one = Patch::fromInt($value);
        $two = Patch::fromInt($value);

        self::assertSame(0, $one->compare($two));
    }

    #[Framework\Attributes\DataProviderExternal(Test\DataProvider\NumberProvider::class, 'valuesWhereFirstValueIsGreaterThanSecondValue')]
    public function testCompareReturnsPlusOneWhenFirstValueIsGreaterThanSecondValue(
        string $firstValue,
        string $secondValue,
    ): void {
        $one = Patch::fromString($firstValue);
        $two = Patch::fromString($secondValue);

        self::assertSame(1, $one->compare($two));
    }

    #[Framework\Attributes\DataProviderExternal(DataProvider\IntProvider::class, 'zero')]
    #[Framework\Attributes\DataProviderExternal(DataProvider\IntProvider::class, 'greaterThanZero')]
    public function testIsSmallerThanReturnsFalseWhenFirstValueIsIdenticalToSecondValue(int $value): void
    {
        $one = Patch::fromInt($value);
        $two = Patch::fromInt($value);

        self::assertFalse($one->isSmallerThan($two));
    }

    #[Framework\Attributes\DataProviderExternal(Test\DataProvider\NumberProvider::class, 'valuesWhereFirstValueIsGreaterThanSecondValue')]
    public function testIsSmallerThanReturnsFalseWhenFirstValueIsGreaterThanSecondValue(
        string $firstValue,
        string $secondValue,
    ): void {
        $one = Patch::fromString($firstValue);
        $two = Patch::fromString($secondValue);

        self::assertFalse($one->isSmallerThan($two));
    }

    #[Framework\Attributes\DataProviderExternal(Test\DataProvider\NumberProvider::class, 'valuesWhereFirstValueIsSmallerThanSecondValue')]
    public function testIsSmallerThanReturnsTrueWhenFirstValueIsSmallerThanSecondValue(
        string $firstValue,
        string $secondValue,
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

    #[Framework\Attributes\DataProviderExternal(Test\DataProvider\NumberProvider::class, 'valuesWhereFirstValueIsSmallerThanSecondValue')]
    public function testIsGreaterThanReturnsFalseWhenFirstValueIsSmallerThanSecondValue(
        string $firstValue,
        string $secondValue,
    ): void {
        $one = Patch::fromString($firstValue);
        $two = Patch::fromString($secondValue);

        self::assertFalse($one->isGreaterThan($two));
    }

    #[Framework\Attributes\DataProviderExternal(DataProvider\IntProvider::class, 'zero')]
    #[Framework\Attributes\DataProviderExternal(DataProvider\IntProvider::class, 'greaterThanZero')]
    public function testIsGreaterThanReturnsFalseWhenFirstValueIsIdenticalToSecondValue(int $value): void
    {
        $one = Patch::fromInt($value);
        $two = Patch::fromInt($value);

        self::assertFalse($one->isGreaterThan($two));
    }

    #[Framework\Attributes\DataProviderExternal(Test\DataProvider\NumberProvider::class, 'valuesWhereFirstValueIsGreaterThanSecondValue')]
    public function testIsGreaterThanReturnsTrueWhenFirstValueIsGreaterThanSecondValue(
        string $firstValue,
        string $secondValue,
    ): void {
        $one = Patch::fromString($firstValue);
        $two = Patch::fromString($secondValue);

        self::assertTrue($one->isGreaterThan($two));
    }
}
