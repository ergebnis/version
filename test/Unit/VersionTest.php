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

use Ergebnis\Version\Exception;
use Ergebnis\Version\Major;
use Ergebnis\Version\Minor;
use Ergebnis\Version\Patch;
use Ergebnis\Version\PreRelease;
use Ergebnis\Version\Test;
use Ergebnis\Version\Version;
use PHPUnit\Framework;

#[Framework\Attributes\CoversClass(Version::class)]
#[Framework\Attributes\UsesClass(Exception\InvalidVersion::class)]
#[Framework\Attributes\UsesClass(Major::class)]
#[Framework\Attributes\UsesClass(Minor::class)]
#[Framework\Attributes\UsesClass(Patch::class)]
#[Framework\Attributes\UsesClass(PreRelease::class)]
final class VersionTest extends Framework\TestCase
{
    use Test\Util\Helper;

    #[Framework\Attributes\DataProvider('provideInvalidValue')]
    public function testFromStringRejectsInvalidValue(string $value): void
    {
        $this->expectException(Exception\InvalidVersion::class);

        Version::fromString($value);
    }

    /**
     * @see https://semver.org/#is-there-a-suggested-regular-expression-regex-to-check-a-semver-string
     * @see https://regex101.com/r/Ly7O1x/3/
     *
     * @return \Generator<string, array{0: string}>
     */
    public static function provideInvalidValue(): \Generator
    {
        $values = [
            '1',
            '1.2',
            '1.2.3-0123',
            '1.2.3-0123.0123',
            '1.1.2+.123',
            '+invalid',
            '-invalid',
            '-invalid+invalid',
            '-invalid.01',
            'alpha',
            'alpha.beta',
            'alpha.beta.1',
            'alpha.1',
            'alpha+beta',
            'alpha_beta',
            'alpha.',
            'alpha..',
            'beta',
            '1.0.0-alpha_beta',
            '-alpha.',
            '1.0.0-alpha..',
            '1.0.0-alpha..1',
            '1.0.0-alpha...1',
            '1.0.0-alpha....1',
            '1.0.0-alpha.....1',
            '1.0.0-alpha......1',
            '1.0.0-alpha.......1',
            '01.1.1',
            '1.01.1',
            '1.1.01',
            '1.2.3.DEV',
            '1.2-SNAPSHOT',
            '1.2.31.2.3----RC-SNAPSHOT.12.09.1--..12+788',
            '1.2-RC-SNAPSHOT',
            '-1.0.3-gamma+b7718',
            '+justmeta',
            '9.8.7+meta+meta',
            '9.8.7-whatever+meta+meta',
            '99999999999999999999999.999999999999999999.99999999999999999----RC-SNAPSHOT.12.09.1--------------------------------..12',
        ];

        foreach ($values as $value) {
            yield $value => [
                $value,
            ];
        }
    }

    #[Framework\Attributes\DataProvider('provideValidValue')]
    public function testFromStringReturnsVersion(
        string $value,
        Major $major,
        Minor $minor,
        Patch $patch,
        PreRelease $preRelease,
    ): void {
        $version = Version::fromString($value);

        self::assertSame($value, $version->toString());

        self::assertEquals($major, $version->major());
        self::assertEquals($minor, $version->minor());
        self::assertEquals($patch, $version->patch());
        self::assertEquals($preRelease, $version->preRelease());
    }

    /**
     * @see https://semver.org/#is-there-a-suggested-regular-expression-regex-to-check-a-semver-string
     * @see https://regex101.com/r/Ly7O1x/3/
     *
     * @return \Generator<string, array{0: string, 1: Major, 2: Minor, 3: Patch, 4: PreRelease}>
     */
    public static function provideValidValue(): \Generator
    {
        $values = [
            '0.0.4' => [
                Major::fromString('0'),
                Minor::fromString('0'),
                Patch::fromString('4'),
                PreRelease::empty(),
            ],
            '1.2.3' => [
                Major::fromString('1'),
                Minor::fromString('2'),
                Patch::fromString('3'),
                PreRelease::empty(),
            ],
            '10.20.30' => [
                Major::fromString('10'),
                Minor::fromString('20'),
                Patch::fromString('30'),
                PreRelease::empty(),
            ],
            '1.1.2-prerelease+meta' => [
                Major::fromString('1'),
                Minor::fromString('1'),
                Patch::fromString('2'),
                PreRelease::fromString('prerelease'),
            ],
            '1.1.2+meta' => [
                Major::fromString('1'),
                Minor::fromString('1'),
                Patch::fromString('2'),
                PreRelease::empty(),
            ],
            '1.1.2+meta-valid' => [
                Major::fromString('1'),
                Minor::fromString('1'),
                Patch::fromString('2'),
                PreRelease::empty(),
            ],
            '1.0.0-alpha' => [
                Major::fromString('1'),
                Minor::fromString('0'),
                Patch::fromString('0'),
                PreRelease::fromString('alpha'),
            ],
            '1.0.0-beta' => [
                Major::fromString('1'),
                Minor::fromString('0'),
                Patch::fromString('0'),
                PreRelease::fromString('beta'),
            ],
            '1.0.0-alpha.beta' => [
                Major::fromString('1'),
                Minor::fromString('0'),
                Patch::fromString('0'),
                PreRelease::fromString('alpha.beta'),
            ],
            '1.0.0-alpha.beta.1' => [
                Major::fromString('1'),
                Minor::fromString('0'),
                Patch::fromString('0'),
                PreRelease::fromString('alpha.beta.1'),
            ],
            '1.0.0-alpha.1' => [
                Major::fromString('1'),
                Minor::fromString('0'),
                Patch::fromString('0'),
                PreRelease::fromString('alpha.1'),
            ],
            '1.0.0-alpha0.valid' => [
                Major::fromString('1'),
                Minor::fromString('0'),
                Patch::fromString('0'),
                PreRelease::fromString('alpha0.valid'),
            ],
            '1.0.0-alpha.0valid' => [
                Major::fromString('1'),
                Minor::fromString('0'),
                Patch::fromString('0'),
                PreRelease::fromString('alpha.0valid'),
            ],
            '1.0.0-alpha-a.b-c-somethinglong+build.1-aef.1-its-okay' => [
                Major::fromString('1'),
                Minor::fromString('0'),
                Patch::fromString('0'),
                PreRelease::fromString('alpha-a.b-c-somethinglong'),
            ],
            '1.0.0-rc.1+build.1' => [
                Major::fromString('1'),
                Minor::fromString('0'),
                Patch::fromString('0'),
                PreRelease::fromString('rc.1'),
            ],
            '2.0.0-rc.1+build.123' => [
                Major::fromString('2'),
                Minor::fromString('0'),
                Patch::fromString('0'),
                PreRelease::fromString('rc.1'),
            ],
            '1.2.3-beta' => [
                Major::fromString('1'),
                Minor::fromString('2'),
                Patch::fromString('3'),
                PreRelease::fromString('beta'),
            ],
            '10.2.3-DEV-SNAPSHOT' => [
                Major::fromString('10'),
                Minor::fromString('2'),
                Patch::fromString('3'),
                PreRelease::fromString('DEV-SNAPSHOT'),
            ],
            '1.2.3-SNAPSHOT-123' => [
                Major::fromString('1'),
                Minor::fromString('2'),
                Patch::fromString('3'),
                PreRelease::fromString('SNAPSHOT-123'),
            ],
            '1.0.0' => [
                Major::fromString('1'),
                Minor::fromString('0'),
                Patch::fromString('0'),
                PreRelease::empty(),
            ],
            '2.0.0' => [
                Major::fromString('2'),
                Minor::fromString('0'),
                Patch::fromString('0'),
                PreRelease::empty(),
            ],
            '1.1.7' => [
                Major::fromString('1'),
                Minor::fromString('1'),
                Patch::fromString('7'),
                PreRelease::empty(),
            ],
            '2.0.0+build.1848' => [
                Major::fromString('2'),
                Minor::fromString('0'),
                Patch::fromString('0'),
                PreRelease::empty(),
            ],
            '2.0.1-alpha.1227' => [
                Major::fromString('2'),
                Minor::fromString('0'),
                Patch::fromString('1'),
                PreRelease::fromString('alpha.1227'),
            ],
            '1.0.0-alpha+beta' => [
                Major::fromString('1'),
                Minor::fromString('0'),
                Patch::fromString('0'),
                PreRelease::fromString('alpha'),
            ],
            '1.2.3----RC-SNAPSHOT.12.9.1--.12+788' => [
                Major::fromString('1'),
                Minor::fromString('2'),
                Patch::fromString('3'),
                PreRelease::fromString('---RC-SNAPSHOT.12.9.1--.12'),
            ],
            '1.2.3----R-S.12.9.1--.12+meta' => [
                Major::fromString('1'),
                Minor::fromString('2'),
                Patch::fromString('3'),
                PreRelease::fromString('---R-S.12.9.1--.12'),
            ],
            '1.2.3----RC-SNAPSHOT.12.9.1--.12' => [
                Major::fromString('1'),
                Minor::fromString('2'),
                Patch::fromString('3'),
                PreRelease::fromString('---RC-SNAPSHOT.12.9.1--.12'),
            ],
            '1.0.0+0.build.1-rc.10000aaa-kk-0.1' => [
                Major::fromString('1'),
                Minor::fromString('0'),
                Patch::fromString('0'),
                PreRelease::empty(),
            ],
            '99999999999999999999999.999999999999999999.99999999999999999' => [
                Major::fromString('99999999999999999999999'),
                Minor::fromString('999999999999999999'),
                Patch::fromString('99999999999999999'),
                PreRelease::empty(),
            ],
            '1.0.0-0A.is.legal' => [
                Major::fromString('1'),
                Minor::fromString('0'),
                Patch::fromString('0'),
                PreRelease::fromString('0A.is.legal'),
            ],
        ];

        foreach ($values as $value => [$major, $minor, $patch, $preRelease]) {
            yield $value => [
                $value,
                $major,
                $minor,
                $patch,
                $preRelease,
            ];
        }
    }

    public function testEqualsReturnsFalseWhenValuesAreDifferent(): void
    {
        $faker = self::faker()->unique();

        $one = Version::fromString($faker->regexify('(0|[1-9]+)\.(0|[1-9]+)\.(0|[1-9]+)'));
        $two = Version::fromString($faker->regexify('(0|[1-9]+)\.(0|[1-9]+)\.(0|[1-9]+)'));

        self::assertFalse($one->equals($two));
    }

    public function testEqualsReturnsTrueWhenValueIsSame(): void
    {
        $value = self::faker()->regexify('(0|[1-9]+)\.(0|[1-9]+)\.(0|[1-9]+)');

        $one = Version::fromString($value);
        $two = Version::fromString($value);

        self::assertTrue($one->equals($two));
    }
}
