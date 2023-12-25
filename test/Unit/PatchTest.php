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

        self::assertSame($value, $patch->toInt());
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

    public function testBumpReturnsPatchWithIncrementedValue(): void
    {
        $one = Patch::fromInt(self::faker()->numberBetween(0, \PHP_INT_MAX - 1));

        $two = $one->bump();

        self::assertNotSame($one, $two);
        self::assertSame($one->toInt() + 1, $two->toInt());
    }

    public function testEqualsReturnsFalseWhenValuesAreDifferent(): void
    {
        $faker = self::faker()->unique();

        $one = Patch::fromString((string) $faker->numberBetween(0));
        $two = Patch::fromString((string) $faker->numberBetween(0));

        self::assertFalse($one->equals($two));
    }

    public function testEqualsReturnsTrueWhenValueIsSame(): void
    {
        $value = (string) self::faker()->numberBetween(0);

        $one = Patch::fromString($value);
        $two = Patch::fromString($value);

        self::assertTrue($one->equals($two));
    }
}