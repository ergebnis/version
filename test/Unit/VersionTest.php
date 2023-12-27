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

use Ergebnis\Version\BuildMetaData;
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
#[Framework\Attributes\UsesClass(BuildMetaData::class)]
#[Framework\Attributes\UsesClass(Major::class)]
#[Framework\Attributes\UsesClass(Minor::class)]
#[Framework\Attributes\UsesClass(Patch::class)]
#[Framework\Attributes\UsesClass(PreRelease::class)]
final class VersionTest extends Framework\TestCase
{
    use Test\Util\Helper;

    #[Framework\Attributes\DataProviderExternal(Test\DataProvider\VersionProvider::class, 'invalid')]
    public function testFromStringRejectsInvalidValue(string $value): void
    {
        $this->expectException(Exception\InvalidVersion::class);

        Version::fromString($value);
    }

    #[Framework\Attributes\DataProviderExternal(Test\DataProvider\VersionProvider::class, 'valid')]
    public function testFromStringReturnsVersion(string $value): void
    {
        $version = Version::fromString($value);

        self::assertSame($value, $version->toString());
    }

    #[Framework\Attributes\DataProvider('provideValueMajorMinorPatchPreReleaseAndBuildMetaData')]
    public function testFromStringReturnsVersionWithMajorMinorPatchPreReleaseAndBuildMetaData(
        string $value,
        Major $major,
        Minor $minor,
        Patch $patch,
        PreRelease $preRelease,
        BuildMetaData $buildMetaData,
    ): void {
        $version = Version::fromString($value);

        self::assertEquals($major, $version->major());
        self::assertEquals($minor, $version->minor());
        self::assertEquals($patch, $version->patch());
        self::assertEquals($preRelease, $version->preRelease());
        self::assertEquals($buildMetaData, $version->buildMetaData());
    }

    /**
     * @see https://semver.org/#is-there-a-suggested-regular-expression-regex-to-check-a-semver-string
     * @see https://regex101.com/r/Ly7O1x/3/
     *
     * @return \Generator<string, array{0: string, 1: Major, 2: Minor, 3: Patch, 4: PreRelease, 5: BuildMetaData}>
     */
    public static function provideValueMajorMinorPatchPreReleaseAndBuildMetaData(): \Generator
    {
        $values = [
            '0.0.4' => [
                Major::fromString('0'),
                Minor::fromString('0'),
                Patch::fromString('4'),
                PreRelease::empty(),
                BuildMetaData::empty(),
            ],
            '1.2.3' => [
                Major::fromString('1'),
                Minor::fromString('2'),
                Patch::fromString('3'),
                PreRelease::empty(),
                BuildMetaData::empty(),
            ],
            '10.20.30' => [
                Major::fromString('10'),
                Minor::fromString('20'),
                Patch::fromString('30'),
                PreRelease::empty(),
                BuildMetaData::empty(),
            ],
            '1.1.2-prerelease+meta' => [
                Major::fromString('1'),
                Minor::fromString('1'),
                Patch::fromString('2'),
                PreRelease::fromString('prerelease'),
                BuildMetaData::fromString('meta'),
            ],
            '1.1.2+meta' => [
                Major::fromString('1'),
                Minor::fromString('1'),
                Patch::fromString('2'),
                PreRelease::empty(),
                BuildMetaData::fromString('meta'),
            ],
            '1.1.2+meta-valid' => [
                Major::fromString('1'),
                Minor::fromString('1'),
                Patch::fromString('2'),
                PreRelease::empty(),
                BuildMetaData::fromString('meta-valid'),
            ],
            '1.0.0-alpha' => [
                Major::fromString('1'),
                Minor::fromString('0'),
                Patch::fromString('0'),
                PreRelease::fromString('alpha'),
                BuildMetaData::empty(),
            ],
            '1.0.0-beta' => [
                Major::fromString('1'),
                Minor::fromString('0'),
                Patch::fromString('0'),
                PreRelease::fromString('beta'),
                BuildMetaData::empty(),
            ],
            '1.0.0-alpha.beta' => [
                Major::fromString('1'),
                Minor::fromString('0'),
                Patch::fromString('0'),
                PreRelease::fromString('alpha.beta'),
                BuildMetaData::empty(),
            ],
            '1.0.0-alpha.beta.1' => [
                Major::fromString('1'),
                Minor::fromString('0'),
                Patch::fromString('0'),
                PreRelease::fromString('alpha.beta.1'),
                BuildMetaData::empty(),
            ],
            '1.0.0-alpha.1' => [
                Major::fromString('1'),
                Minor::fromString('0'),
                Patch::fromString('0'),
                PreRelease::fromString('alpha.1'),
                BuildMetaData::empty(),
            ],
            '1.0.0-alpha0.valid' => [
                Major::fromString('1'),
                Minor::fromString('0'),
                Patch::fromString('0'),
                PreRelease::fromString('alpha0.valid'),
                BuildMetaData::empty(),
            ],
            '1.0.0-alpha.0valid' => [
                Major::fromString('1'),
                Minor::fromString('0'),
                Patch::fromString('0'),
                PreRelease::fromString('alpha.0valid'),
                BuildMetaData::empty(),
            ],
            '1.0.0-alpha-a.b-c-somethinglong+build.1-aef.1-its-okay' => [
                Major::fromString('1'),
                Minor::fromString('0'),
                Patch::fromString('0'),
                PreRelease::fromString('alpha-a.b-c-somethinglong'),
                BuildMetaData::fromString('build.1-aef.1-its-okay'),
            ],
            '1.0.0-rc.1+build.1' => [
                Major::fromString('1'),
                Minor::fromString('0'),
                Patch::fromString('0'),
                PreRelease::fromString('rc.1'),
                BuildMetaData::fromString('build.1'),
            ],
            '2.0.0-rc.1+build.123' => [
                Major::fromString('2'),
                Minor::fromString('0'),
                Patch::fromString('0'),
                PreRelease::fromString('rc.1'),
                BuildMetaData::fromString('build.123'),
            ],
            '1.2.3-beta' => [
                Major::fromString('1'),
                Minor::fromString('2'),
                Patch::fromString('3'),
                PreRelease::fromString('beta'),
                BuildMetaData::empty(),
            ],
            '10.2.3-DEV-SNAPSHOT' => [
                Major::fromString('10'),
                Minor::fromString('2'),
                Patch::fromString('3'),
                PreRelease::fromString('DEV-SNAPSHOT'),
                BuildMetaData::empty(),
            ],
            '1.2.3-SNAPSHOT-123' => [
                Major::fromString('1'),
                Minor::fromString('2'),
                Patch::fromString('3'),
                PreRelease::fromString('SNAPSHOT-123'),
                BuildMetaData::empty(),
            ],
            '1.0.0' => [
                Major::fromString('1'),
                Minor::fromString('0'),
                Patch::fromString('0'),
                PreRelease::empty(),
                BuildMetaData::empty(),
            ],
            '2.0.0' => [
                Major::fromString('2'),
                Minor::fromString('0'),
                Patch::fromString('0'),
                PreRelease::empty(),
                BuildMetaData::empty(),
            ],
            '1.1.7' => [
                Major::fromString('1'),
                Minor::fromString('1'),
                Patch::fromString('7'),
                PreRelease::empty(),
                BuildMetaData::empty(),
            ],
            '2.0.0+build.1848' => [
                Major::fromString('2'),
                Minor::fromString('0'),
                Patch::fromString('0'),
                PreRelease::empty(),
                BuildMetaData::fromString('build.1848'),
            ],
            '2.0.1-alpha.1227' => [
                Major::fromString('2'),
                Minor::fromString('0'),
                Patch::fromString('1'),
                PreRelease::fromString('alpha.1227'),
                BuildMetaData::empty(),
            ],
            '1.0.0-alpha+beta' => [
                Major::fromString('1'),
                Minor::fromString('0'),
                Patch::fromString('0'),
                PreRelease::fromString('alpha'),
                BuildMetaData::fromString('beta'),
            ],
            '1.2.3----RC-SNAPSHOT.12.9.1--.12+788' => [
                Major::fromString('1'),
                Minor::fromString('2'),
                Patch::fromString('3'),
                PreRelease::fromString('---RC-SNAPSHOT.12.9.1--.12'),
                BuildMetaData::fromString('788'),
            ],
            '1.2.3----R-S.12.9.1--.12+meta' => [
                Major::fromString('1'),
                Minor::fromString('2'),
                Patch::fromString('3'),
                PreRelease::fromString('---R-S.12.9.1--.12'),
                BuildMetaData::fromString('meta'),
            ],
            '1.2.3----RC-SNAPSHOT.12.9.1--.12' => [
                Major::fromString('1'),
                Minor::fromString('2'),
                Patch::fromString('3'),
                PreRelease::fromString('---RC-SNAPSHOT.12.9.1--.12'),
                BuildMetaData::empty(),
            ],
            '1.0.0+0.build.1-rc.10000aaa-kk-0.1' => [
                Major::fromString('1'),
                Minor::fromString('0'),
                Patch::fromString('0'),
                PreRelease::empty(),
                BuildMetaData::fromString('0.build.1-rc.10000aaa-kk-0.1'),
            ],
            '99999999999999999999999.999999999999999999.99999999999999999' => [
                Major::fromString('99999999999999999999999'),
                Minor::fromString('999999999999999999'),
                Patch::fromString('99999999999999999'),
                PreRelease::empty(),
                BuildMetaData::empty(),
            ],
            '1.0.0-0A.is.legal' => [
                Major::fromString('1'),
                Minor::fromString('0'),
                Patch::fromString('0'),
                PreRelease::fromString('0A.is.legal'),
                BuildMetaData::empty(),
            ],
        ];

        foreach ($values as $value => [$major, $minor, $patch, $preRelease, $buildMetaData]) {
            yield $value => [
                $value,
                $major,
                $minor,
                $patch,
                $preRelease,
                $buildMetaData,
            ];
        }
    }

    #[Framework\Attributes\DataProviderExternal(Test\DataProvider\VersionProvider::class, 'valueAndValueWithBumpedMajor')]
    public function testBumpMajorReturnsVersionWithBumpedMajor(
        string $value,
        string $valueWithBumpedMajor,
    ): void {
        $one = Version::fromString($value);

        $two = $one->bumpMajor();

        self::assertNotSame($one, $two);
        self::assertSame($valueWithBumpedMajor, $two->toString());
    }

    #[Framework\Attributes\DataProviderExternal(Test\DataProvider\VersionProvider::class, 'valueAndValueWithBumpedMinor')]
    public function testBumpMinorReturnsVersionWithBumpedMinor(
        string $value,
        string $valueWithBumpedMinor,
    ): void {
        $one = Version::fromString($value);

        $two = $one->bumpMinor();

        self::assertNotSame($one, $two);
        self::assertSame($valueWithBumpedMinor, $two->toString());
    }

    #[Framework\Attributes\DataProviderExternal(Test\DataProvider\VersionProvider::class, 'valueAndValueWithBumpedPatch')]
    public function testBumpPatchReturnsVersionWithBumpedPatch(
        string $value,
        string $valueWithBumpedPatch,
    ): void {
        $one = Version::fromString($value);

        $two = $one->bumpPatch();

        self::assertNotSame($one, $two);
        self::assertSame($valueWithBumpedPatch, $two->toString());
    }

    #[Framework\Attributes\DataProviderExternal(Test\DataProvider\VersionProvider::class, 'valuesWhereFirstValueIsSmallerThanSecondValue')]
    public function testCompareReturnsMinusOneWhenFirstValueIsSmallerThanSecondValue(
        string $firstValue,
        string $secondValue,
    ): void {
        $one = Version::fromString($firstValue);
        $two = Version::fromString($secondValue);

        self::assertSame(-1, $one->compare($two));
    }

    #[Framework\Attributes\DataProviderExternal(Test\DataProvider\VersionProvider::class, 'valid')]
    public function testCompareReturnsZeroWhenFirstValueIsIdenticalToSecondValue(string $value): void
    {
        $one = Version::fromString($value);
        $two = Version::fromString($value);

        self::assertSame(0, $one->compare($two));
    }

    #[Framework\Attributes\DataProviderExternal(Test\DataProvider\VersionProvider::class, 'valuesWhereFirstValueIsEqualToSecondValue')]
    public function testCompareReturnsZeroWhenFirstValueIsEqualToSecondValue(
        string $firstValue,
        string $secondValue,
    ): void {
        $one = Version::fromString($firstValue);
        $two = Version::fromString($secondValue);

        self::assertSame(0, $one->compare($two));
    }

    #[Framework\Attributes\DataProviderExternal(Test\DataProvider\VersionProvider::class, 'valuesWhereFirstValueIsGreaterThanSecondValue')]
    public function testCompareReturnsMinusOneWhenFirstValueIsGreaterThanSecondValue(
        string $firstValue,
        string $secondValue,
    ): void {
        $one = Version::fromString($firstValue);
        $two = Version::fromString($secondValue);

        self::assertSame(1, $one->compare($two));
    }

    #[Framework\Attributes\DataProviderExternal(Test\DataProvider\VersionProvider::class, 'valid')]
    public function testIsSmallerThanReturnsFalseWhenFirstValueIsIdenticalToSecondValue(string $value): void
    {
        $one = Version::fromString($value);
        $two = Version::fromString($value);

        self::assertFalse($one->isSmallerThan($two));
    }

    #[Framework\Attributes\DataProviderExternal(Test\DataProvider\VersionProvider::class, 'valuesWhereFirstValueIsEqualToSecondValue')]
    public function testIsSmallerThanReturnsFalseWhenFirstValueIsEqualToSecondValue(
        string $firstValue,
        string $secondValue,
    ): void {
        $one = Version::fromString($firstValue);
        $two = Version::fromString($secondValue);

        self::assertFalse($one->isSmallerThan($two));
    }

    #[Framework\Attributes\DataProviderExternal(Test\DataProvider\VersionProvider::class, 'valuesWhereFirstValueIsGreaterThanSecondValue')]
    public function testIsSmallerThanReturnsFalseWhenFirstValueIsGreaterThanSecondValue(
        string $firstValue,
        string $secondValue,
    ): void {
        $one = Version::fromString($firstValue);
        $two = Version::fromString($secondValue);

        self::assertFalse($one->isSmallerThan($two));
    }

    #[Framework\Attributes\DataProviderExternal(Test\DataProvider\VersionProvider::class, 'valuesWhereFirstValueIsSmallerThanSecondValue')]
    public function testIsSmallerThanReturnsTrueWhenFirstValueIsSmallerThanSecondValue(
        string $firstValue,
        string $secondValue,
    ): void {
        $one = Version::fromString($firstValue);
        $two = Version::fromString($secondValue);

        self::assertTrue($one->isSmallerThan($two));
    }

    #[Framework\Attributes\DataProviderExternal(Test\DataProvider\VersionProvider::class, 'valuesWhereFirstValueIsGreaterThanSecondValue')]
    public function testEqualsReturnsFalseWhenValuesAreDifferent(
        string $firstValue,
        string $secondValue,
    ): void {
        $one = Version::fromString($firstValue);
        $two = Version::fromString($secondValue);

        self::assertFalse($one->equals($two));
    }

    #[Framework\Attributes\DataProviderExternal(Test\DataProvider\VersionProvider::class, 'valuesWhereFirstValueIsEqualToSecondValue')]
    public function testEqualsReturnsTrueWhenValuesAreEqual(
        string $firstValue,
        string $secondValue,
    ): void {
        $one = Version::fromString($firstValue);
        $two = Version::fromString($secondValue);

        self::assertTrue($one->equals($two));
    }

    #[Framework\Attributes\DataProviderExternal(Test\DataProvider\VersionProvider::class, 'valid')]
    public function testEqualsReturnsTrueWhenValuesAreIdentical(string $value): void
    {
        $one = Version::fromString($value);
        $two = Version::fromString($value);

        self::assertTrue($one->equals($two));
    }

    #[Framework\Attributes\DataProviderExternal(Test\DataProvider\VersionProvider::class, 'valuesWhereFirstValueIsSmallerThanSecondValue')]
    public function testIsGreaterThanReturnsFalseWhenFirstValueIsSmallerThanSecondValue(
        string $firstValue,
        string $secondValue,
    ): void {
        $one = Version::fromString($firstValue);
        $two = Version::fromString($secondValue);

        self::assertFalse($one->isGreaterThan($two));
    }

    #[Framework\Attributes\DataProviderExternal(Test\DataProvider\VersionProvider::class, 'valid')]
    public function testIsGreaterThanReturnsFalseWhenFirstValueIsIdenticalToSecondValue(string $value): void
    {
        $one = Version::fromString($value);
        $two = Version::fromString($value);

        self::assertFalse($one->isGreaterThan($two));
    }

    #[Framework\Attributes\DataProviderExternal(Test\DataProvider\VersionProvider::class, 'valuesWhereFirstValueIsEqualToSecondValue')]
    public function testIsGreaterThanReturnsFalseWhenFirstValueIsEqualToSecondValue(
        string $firstValue,
        string $secondValue,
    ): void {
        $one = Version::fromString($firstValue);
        $two = Version::fromString($secondValue);

        self::assertFalse($one->isGreaterThan($two));
    }

    #[Framework\Attributes\DataProviderExternal(Test\DataProvider\VersionProvider::class, 'valuesWhereFirstValueIsGreaterThanSecondValue')]
    public function testIsGreaterThanReturnsTrueWhenFirstValueIsGreaterThanSecondValue(
        string $firstValue,
        string $secondValue,
    ): void {
        $one = Version::fromString($firstValue);
        $two = Version::fromString($secondValue);

        self::assertTrue($one->isGreaterThan($two));
    }
}
