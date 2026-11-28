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

use Ergebnis\Version;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\Version\Version
 *
 * @uses \Ergebnis\Version\BuildMetaData
 * @uses \Ergebnis\Version\Exception\InvalidVersion
 * @uses \Ergebnis\Version\Major
 * @uses \Ergebnis\Version\Minor
 * @uses \Ergebnis\Version\Patch
 * @uses \Ergebnis\Version\PreRelease
 */
final class VersionTest extends Framework\TestCase
{
    use Version\Test\Util\Helper;

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\VersionProvider::invalid
     */
    public function testFromStringRejectsInvalidValue(string $value): void
    {
        $this->expectException(Version\Exception\InvalidVersion::class);

        Version\Version::fromString($value);
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\VersionProvider::valid
     */
    public function testFromStringReturnsVersion(string $value): void
    {
        $version = Version\Version::fromString($value);

        self::assertSame($value, $version->toString());
    }

    /**
     * @dataProvider provideValueMajorMinorPatchPreReleaseAndBuildMetaData
     */
    public function testFromStringReturnsVersionWithMajorMinorPatchPreReleaseAndBuildMetaData(
        string $value,
        Version\Major $major,
        Version\Minor $minor,
        Version\Patch $patch,
        Version\PreRelease $preRelease,
        Version\BuildMetaData $buildMetaData
    ): void {
        $version = Version\Version::fromString($value);

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
     * @return \Generator<string, array{0: string, 1: Version\Major, 2: Version\Minor, 3: Version\Patch, 4: Version\PreRelease, 5: Version\BuildMetaData}>
     */
    public static function provideValueMajorMinorPatchPreReleaseAndBuildMetaData(): iterable
    {
        $values = [
            '0.0.4' => [
                Version\Major::fromString('0'),
                Version\Minor::fromString('0'),
                Version\Patch::fromString('4'),
                Version\PreRelease::empty(),
                Version\BuildMetaData::empty(),
            ],
            '1.0.0' => [
                Version\Major::fromString('1'),
                Version\Minor::fromString('0'),
                Version\Patch::fromString('0'),
                Version\PreRelease::empty(),
                Version\BuildMetaData::empty(),
            ],
            '1.0.0+0.build.1-rc.10000aaa-kk-0.1' => [
                Version\Major::fromString('1'),
                Version\Minor::fromString('0'),
                Version\Patch::fromString('0'),
                Version\PreRelease::empty(),
                Version\BuildMetaData::fromString('0.build.1-rc.10000aaa-kk-0.1'),
            ],
            '1.0.0-0A.is.legal' => [
                Version\Major::fromString('1'),
                Version\Minor::fromString('0'),
                Version\Patch::fromString('0'),
                Version\PreRelease::fromString('0A.is.legal'),
                Version\BuildMetaData::empty(),
            ],
            '1.0.0-alpha' => [
                Version\Major::fromString('1'),
                Version\Minor::fromString('0'),
                Version\Patch::fromString('0'),
                Version\PreRelease::fromString('alpha'),
                Version\BuildMetaData::empty(),
            ],
            '1.0.0-alpha+beta' => [
                Version\Major::fromString('1'),
                Version\Minor::fromString('0'),
                Version\Patch::fromString('0'),
                Version\PreRelease::fromString('alpha'),
                Version\BuildMetaData::fromString('beta'),
            ],
            '1.0.0-alpha-a.b-c-somethinglong+build.1-aef.1-its-okay' => [
                Version\Major::fromString('1'),
                Version\Minor::fromString('0'),
                Version\Patch::fromString('0'),
                Version\PreRelease::fromString('alpha-a.b-c-somethinglong'),
                Version\BuildMetaData::fromString('build.1-aef.1-its-okay'),
            ],
            '1.0.0-alpha.0valid' => [
                Version\Major::fromString('1'),
                Version\Minor::fromString('0'),
                Version\Patch::fromString('0'),
                Version\PreRelease::fromString('alpha.0valid'),
                Version\BuildMetaData::empty(),
            ],
            '1.0.0-alpha.1' => [
                Version\Major::fromString('1'),
                Version\Minor::fromString('0'),
                Version\Patch::fromString('0'),
                Version\PreRelease::fromString('alpha.1'),
                Version\BuildMetaData::empty(),
            ],
            '1.0.0-alpha.beta' => [
                Version\Major::fromString('1'),
                Version\Minor::fromString('0'),
                Version\Patch::fromString('0'),
                Version\PreRelease::fromString('alpha.beta'),
                Version\BuildMetaData::empty(),
            ],
            '1.0.0-alpha.beta.1' => [
                Version\Major::fromString('1'),
                Version\Minor::fromString('0'),
                Version\Patch::fromString('0'),
                Version\PreRelease::fromString('alpha.beta.1'),
                Version\BuildMetaData::empty(),
            ],
            '1.0.0-alpha0.valid' => [
                Version\Major::fromString('1'),
                Version\Minor::fromString('0'),
                Version\Patch::fromString('0'),
                Version\PreRelease::fromString('alpha0.valid'),
                Version\BuildMetaData::empty(),
            ],
            '1.0.0-beta' => [
                Version\Major::fromString('1'),
                Version\Minor::fromString('0'),
                Version\Patch::fromString('0'),
                Version\PreRelease::fromString('beta'),
                Version\BuildMetaData::empty(),
            ],
            '1.0.0-rc.1+build.1' => [
                Version\Major::fromString('1'),
                Version\Minor::fromString('0'),
                Version\Patch::fromString('0'),
                Version\PreRelease::fromString('rc.1'),
                Version\BuildMetaData::fromString('build.1'),
            ],
            '1.1.2+meta' => [
                Version\Major::fromString('1'),
                Version\Minor::fromString('1'),
                Version\Patch::fromString('2'),
                Version\PreRelease::empty(),
                Version\BuildMetaData::fromString('meta'),
            ],
            '1.1.2+meta-valid' => [
                Version\Major::fromString('1'),
                Version\Minor::fromString('1'),
                Version\Patch::fromString('2'),
                Version\PreRelease::empty(),
                Version\BuildMetaData::fromString('meta-valid'),
            ],
            '1.1.2-prerelease+meta' => [
                Version\Major::fromString('1'),
                Version\Minor::fromString('1'),
                Version\Patch::fromString('2'),
                Version\PreRelease::fromString('prerelease'),
                Version\BuildMetaData::fromString('meta'),
            ],
            '1.1.7' => [
                Version\Major::fromString('1'),
                Version\Minor::fromString('1'),
                Version\Patch::fromString('7'),
                Version\PreRelease::empty(),
                Version\BuildMetaData::empty(),
            ],
            '1.2.3' => [
                Version\Major::fromString('1'),
                Version\Minor::fromString('2'),
                Version\Patch::fromString('3'),
                Version\PreRelease::empty(),
                Version\BuildMetaData::empty(),
            ],
            '1.2.3----R-S.12.9.1--.12+meta' => [
                Version\Major::fromString('1'),
                Version\Minor::fromString('2'),
                Version\Patch::fromString('3'),
                Version\PreRelease::fromString('---R-S.12.9.1--.12'),
                Version\BuildMetaData::fromString('meta'),
            ],
            '1.2.3----RC-SNAPSHOT.12.9.1--.12' => [
                Version\Major::fromString('1'),
                Version\Minor::fromString('2'),
                Version\Patch::fromString('3'),
                Version\PreRelease::fromString('---RC-SNAPSHOT.12.9.1--.12'),
                Version\BuildMetaData::empty(),
            ],
            '1.2.3----RC-SNAPSHOT.12.9.1--.12+788' => [
                Version\Major::fromString('1'),
                Version\Minor::fromString('2'),
                Version\Patch::fromString('3'),
                Version\PreRelease::fromString('---RC-SNAPSHOT.12.9.1--.12'),
                Version\BuildMetaData::fromString('788'),
            ],
            '1.2.3-SNAPSHOT-123' => [
                Version\Major::fromString('1'),
                Version\Minor::fromString('2'),
                Version\Patch::fromString('3'),
                Version\PreRelease::fromString('SNAPSHOT-123'),
                Version\BuildMetaData::empty(),
            ],
            '1.2.3-beta' => [
                Version\Major::fromString('1'),
                Version\Minor::fromString('2'),
                Version\Patch::fromString('3'),
                Version\PreRelease::fromString('beta'),
                Version\BuildMetaData::empty(),
            ],
            '10.2.3-DEV-SNAPSHOT' => [
                Version\Major::fromString('10'),
                Version\Minor::fromString('2'),
                Version\Patch::fromString('3'),
                Version\PreRelease::fromString('DEV-SNAPSHOT'),
                Version\BuildMetaData::empty(),
            ],
            '10.20.30' => [
                Version\Major::fromString('10'),
                Version\Minor::fromString('20'),
                Version\Patch::fromString('30'),
                Version\PreRelease::empty(),
                Version\BuildMetaData::empty(),
            ],
            '2.0.0' => [
                Version\Major::fromString('2'),
                Version\Minor::fromString('0'),
                Version\Patch::fromString('0'),
                Version\PreRelease::empty(),
                Version\BuildMetaData::empty(),
            ],
            '2.0.0+build.1848' => [
                Version\Major::fromString('2'),
                Version\Minor::fromString('0'),
                Version\Patch::fromString('0'),
                Version\PreRelease::empty(),
                Version\BuildMetaData::fromString('build.1848'),
            ],
            '2.0.0-rc.1+build.123' => [
                Version\Major::fromString('2'),
                Version\Minor::fromString('0'),
                Version\Patch::fromString('0'),
                Version\PreRelease::fromString('rc.1'),
                Version\BuildMetaData::fromString('build.123'),
            ],
            '2.0.1-alpha.1227' => [
                Version\Major::fromString('2'),
                Version\Minor::fromString('0'),
                Version\Patch::fromString('1'),
                Version\PreRelease::fromString('alpha.1227'),
                Version\BuildMetaData::empty(),
            ],
            '99999999999999999999999.999999999999999999.99999999999999999' => [
                Version\Major::fromString('99999999999999999999999'),
                Version\Minor::fromString('999999999999999999'),
                Version\Patch::fromString('99999999999999999'),
                Version\PreRelease::empty(),
                Version\BuildMetaData::empty(),
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

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\VersionProvider::valueAndValueWithBumpedMajor
     */
    public function testBumpMajorReturnsVersionWithBumpedMajor(
        string $value,
        string $valueWithBumpedMajor
    ): void {
        $one = Version\Version::fromString($value);

        $two = $one->bumpMajor();

        self::assertNotSame($one, $two);
        self::assertSame($valueWithBumpedMajor, $two->toString());
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\VersionProvider::valueAndValueWithBumpedMinor
     */
    public function testBumpMinorReturnsVersionWithBumpedMinor(
        string $value,
        string $valueWithBumpedMinor
    ): void {
        $one = Version\Version::fromString($value);

        $two = $one->bumpMinor();

        self::assertNotSame($one, $two);
        self::assertSame($valueWithBumpedMinor, $two->toString());
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\VersionProvider::valueAndValueWithBumpedPatch
     */
    public function testBumpPatchReturnsVersionWithBumpedPatch(
        string $value,
        string $valueWithBumpedPatch
    ): void {
        $one = Version\Version::fromString($value);

        $two = $one->bumpPatch();

        self::assertNotSame($one, $two);
        self::assertSame($valueWithBumpedPatch, $two->toString());
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\VersionProvider::valuesWhereFirstValueIsLessThanSecondValue
     */
    public function testCompareReturnsMinusOneWhenFirstValueIsLessThanSecondValue(
        string $valueOne,
        string $valueTwo
    ): void {
        $one = Version\Version::fromString($valueOne);
        $two = Version\Version::fromString($valueTwo);

        self::assertSame(-1, $one->compare($two));
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\VersionProvider::valid
     */
    public function testCompareReturnsZeroWhenFirstValueIsIdenticalToSecondValue(string $value): void
    {
        $one = Version\Version::fromString($value);
        $two = Version\Version::fromString($value);

        self::assertSame(0, $one->compare($two));
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\VersionProvider::valuesWhereFirstValueIsEqualToSecondValue
     */
    public function testCompareReturnsZeroWhenFirstValueIsEqualToSecondValue(
        string $valueOne,
        string $valueTwo
    ): void {
        $one = Version\Version::fromString($valueOne);
        $two = Version\Version::fromString($valueTwo);

        self::assertSame(0, $one->compare($two));
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\VersionProvider::valuesWhereFirstValueIsGreaterThanSecondValue
     */
    public function testCompareReturnsMinusOneWhenFirstValueIsGreaterThanSecondValue(
        string $valueOne,
        string $valueTwo
    ): void {
        $one = Version\Version::fromString($valueOne);
        $two = Version\Version::fromString($valueTwo);

        self::assertSame(1, $one->compare($two));
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\VersionProvider::valid
     */
    public function testIsLessThanReturnsFalseWhenFirstValueIsIdenticalToSecondValue(string $value): void
    {
        $one = Version\Version::fromString($value);
        $two = Version\Version::fromString($value);

        self::assertFalse($one->isLessThan($two));
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\VersionProvider::valuesWhereFirstValueIsEqualToSecondValue
     */
    public function testIsLessThanReturnsFalseWhenFirstValueIsEqualToSecondValue(
        string $valueOne,
        string $valueTwo
    ): void {
        $one = Version\Version::fromString($valueOne);
        $two = Version\Version::fromString($valueTwo);

        self::assertFalse($one->isLessThan($two));
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\VersionProvider::valuesWhereFirstValueIsGreaterThanSecondValue
     */
    public function testIsLessThanReturnsFalseWhenFirstValueIsGreaterThanSecondValue(
        string $valueOne,
        string $valueTwo
    ): void {
        $one = Version\Version::fromString($valueOne);
        $two = Version\Version::fromString($valueTwo);

        self::assertFalse($one->isLessThan($two));
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\VersionProvider::valuesWhereFirstValueIsLessThanSecondValue
     */
    public function testIsLessThanReturnsTrueWhenFirstValueIsLessThanSecondValue(
        string $valueOne,
        string $valueTwo
    ): void {
        $one = Version\Version::fromString($valueOne);
        $two = Version\Version::fromString($valueTwo);

        self::assertTrue($one->isLessThan($two));
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\VersionProvider::valid
     */
    public function testIsSmallerThanReturnsFalseWhenFirstValueIsIdenticalToSecondValue(string $value): void
    {
        $one = Version\Version::fromString($value);
        $two = Version\Version::fromString($value);

        self::assertFalse($one->isSmallerThan($two));
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\VersionProvider::valuesWhereFirstValueIsEqualToSecondValue
     */
    public function testIsSmallerThanReturnsFalseWhenFirstValueIsEqualToSecondValue(
        string $valueOne,
        string $valueTwo
    ): void {
        $one = Version\Version::fromString($valueOne);
        $two = Version\Version::fromString($valueTwo);

        self::assertFalse($one->isSmallerThan($two));
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\VersionProvider::valuesWhereFirstValueIsGreaterThanSecondValue
     */
    public function testIsSmallerThanReturnsFalseWhenFirstValueIsGreaterThanSecondValue(
        string $valueOne,
        string $valueTwo
    ): void {
        $one = Version\Version::fromString($valueOne);
        $two = Version\Version::fromString($valueTwo);

        self::assertFalse($one->isSmallerThan($two));
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\VersionProvider::valuesWhereFirstValueIsLessThanSecondValue
     */
    public function testIsSmallerThanReturnsTrueWhenFirstValueIsSmallerThanSecondValue(
        string $valueOne,
        string $valueTwo
    ): void {
        $one = Version\Version::fromString($valueOne);
        $two = Version\Version::fromString($valueTwo);

        self::assertTrue($one->isSmallerThan($two));
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\VersionProvider::valuesWhereFirstValueIsGreaterThanSecondValue
     */
    public function testEqualsReturnsFalseWhenValuesAreDifferent(
        string $valueOne,
        string $valueTwo
    ): void {
        $one = Version\Version::fromString($valueOne);
        $two = Version\Version::fromString($valueTwo);

        self::assertFalse($one->equals($two));
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\VersionProvider::valuesWhereFirstValueIsEqualToSecondValue
     */
    public function testEqualsReturnsTrueWhenValuesAreEqual(
        string $valueOne,
        string $valueTwo
    ): void {
        $one = Version\Version::fromString($valueOne);
        $two = Version\Version::fromString($valueTwo);

        self::assertTrue($one->equals($two));
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\VersionProvider::valid
     */
    public function testEqualsReturnsTrueWhenValuesAreIdentical(string $value): void
    {
        $one = Version\Version::fromString($value);
        $two = Version\Version::fromString($value);

        self::assertTrue($one->equals($two));
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\VersionProvider::valuesWhereFirstValueIsLessThanSecondValue
     */
    public function testIsGreaterThanReturnsFalseWhenFirstValueIsLessThanSecondValue(
        string $valueOne,
        string $valueTwo
    ): void {
        $one = Version\Version::fromString($valueOne);
        $two = Version\Version::fromString($valueTwo);

        self::assertFalse($one->isGreaterThan($two));
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\VersionProvider::valid
     */
    public function testIsGreaterThanReturnsFalseWhenFirstValueIsIdenticalToSecondValue(string $value): void
    {
        $one = Version\Version::fromString($value);
        $two = Version\Version::fromString($value);

        self::assertFalse($one->isGreaterThan($two));
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\VersionProvider::valuesWhereFirstValueIsEqualToSecondValue
     */
    public function testIsGreaterThanReturnsFalseWhenFirstValueIsEqualToSecondValue(
        string $valueOne,
        string $valueTwo
    ): void {
        $one = Version\Version::fromString($valueOne);
        $two = Version\Version::fromString($valueTwo);

        self::assertFalse($one->isGreaterThan($two));
    }

    /**
     * @dataProvider \Ergebnis\Version\Test\DataProvider\VersionProvider::valuesWhereFirstValueIsGreaterThanSecondValue
     */
    public function testIsGreaterThanReturnsTrueWhenFirstValueIsGreaterThanSecondValue(
        string $valueOne,
        string $valueTwo
    ): void {
        $one = Version\Version::fromString($valueOne);
        $two = Version\Version::fromString($valueTwo);

        self::assertTrue($one->isGreaterThan($two));
    }
}
