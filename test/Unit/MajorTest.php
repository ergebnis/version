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

use Ergebnis\Version\Exception;
use Ergebnis\Version\Major;
use Ergebnis\Version\Test;
use Ergebnis\Version\Version;
use PHPUnit\Framework;

#[Framework\Attributes\CoversClass(Major::class)]
#[Framework\Attributes\UsesClass(Exception\InvalidMajor::class)]
final class MajorTest extends Framework\TestCase
{
    use Test\Util\Helper;

    #[Framework\Attributes\DataProvider('provideInvalidValue')]
    public function testFromStringRejectsInvalidValue(string $value): void
    {
        $this->expectException(Exception\InvalidMajor::class);

        Major::fromString($value);
    }

    /**
     * @see https://semver.org/#is-there-a-suggested-regular-expression-regex-to-check-a-semver-string
     * @see https://regex101.com/r/Ly7O1x/3/
     *
     * @return Generator<string, array{0: string}>
     */
    public static function provideInvalidValue(): Generator
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

    #[Framework\Attributes\DataProvider('provideValidValue')]
    public function testFromStringReturnsMajor(string $value): void
    {
        $major = Major::fromString($value);

        self::assertSame($value, $major->toString());
    }

    /**
     * @see https://semver.org/#is-there-a-suggested-regular-expression-regex-to-check-a-semver-string
     * @see https://regex101.com/r/Ly7O1x/3/
     *
     * @return Generator<string, array{0: string}>
     */
    public static function provideValidValue(): Generator
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

    public function testEqualsReturnsFalseWhenValuesAreDifferent(): void
    {
        $faker = self::faker()->unique();

        $one = Major::fromString((string) $faker->numberBetween(0));
        $two = Major::fromString((string) $faker->numberBetween(0));

        self::assertFalse($one->equals($two));
    }

    public function testEqualsReturnsTrueWhenValueIsSame(): void
    {
        $value = (string) self::faker()->numberBetween(0);

        $one = Major::fromString($value);
        $two = Major::fromString($value);

        self::assertTrue($one->equals($two));
    }
}
