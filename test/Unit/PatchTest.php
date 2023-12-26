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

    #[Framework\Attributes\DataProvider('provideInvalidStringValue')]
    public function testFromStringRejectsInvalidStringValue(string $value): void
    {
        $this->expectException(Exception\InvalidPatch::class);

        Patch::fromString($value);
    }

    /**
     * @see https://semver.org/#is-there-a-suggested-regular-expression-regex-to-check-a-semver-string
     * @see https://regex101.com/r/Ly7O1x/3/
     *
     * @return \Generator<string, array{0: string}>
     */
    public static function provideInvalidStringValue(): \Generator
    {
        $faker = self::faker();

        $values = [
            'leading-zero' => \sprintf(
                '0%d',
                $faker->numberBetween(1),
            ),
            'word' => $faker->word(),
        ];

        foreach ($values as $value) {
            yield $value => [
                $value,
            ];
        }
    }

    #[Framework\Attributes\DataProvider('provideValidStringValue')]
    public function testFromStringReturnsPatch(string $value): void
    {
        $patch = Patch::fromString($value);

        self::assertSame($value, $patch->toString());
    }

    /**
     * @see https://semver.org/#is-there-a-suggested-regular-expression-regex-to-check-a-semver-string
     * @see https://regex101.com/r/Ly7O1x/3/
     *
     * @return \Generator<string, array{0: string}>
     */
    public static function provideValidStringValue(): \Generator
    {
        $values = [
            'zero' => '0',
            'one' => '1',
            'greater-than-one' => (string) self::faker()->numberBetween(2),
        ];

        foreach ($values as $key => $value) {
            yield $key => [
                $value,
            ];
        }
    }

    #[Framework\Attributes\DataProvider('provideValueAndBumpedValue')]
    public function testBumpReturnsPatchWithIncrementedValue(
        string $value,
        string $bumpedValue,
    ): void {
        $one = Patch::fromString($value);

        $two = $one->bump();

        self::assertNotSame($one, $two);
        self::assertSame($bumpedValue, $two->toString());
    }

    /**
     * @return \Generator<string, array{0: string, 1: string}>
     */
    public static function provideValueAndBumpedValue(): \Generator
    {
        $values = [
            'zero' => [
                '0',
                '1',
            ],
            'one' => [
                '1',
                '2',
            ],
            'php-int-max' => [
                (string) \PHP_INT_MAX,
                \bcadd(
                    (string) \PHP_INT_MAX,
                    '1',
                ),
            ],
        ];

        foreach ($values as $key => [$value, $bumpedValue]) {
            yield $key => [
                $value,
                $bumpedValue,
            ];
        }
    }

    #[Framework\Attributes\DataProviderExternal(Test\DataProvider\IntProvider::class, 'valuesWhereFirstValueIsSmallerThanSecondValue')]
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

    #[Framework\Attributes\DataProviderExternal(Test\DataProvider\IntProvider::class, 'valuesWhereFirstValueIsGreaterThanSecondValue')]
    public function testCompareReturnsPlusOneWhenFirstValueIsGreaterThanSecondValue(
        string $firstValue,
        string $secondValue,
    ): void {
        $one = Patch::fromString($firstValue);
        $two = Patch::fromString($secondValue);

        self::assertSame(1, $one->compare($two));
    }
}
