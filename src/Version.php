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

namespace Ergebnis\Version;

final class Version
{
    /**
     * @see https://semver.org/#is-there-a-suggested-regular-expression-regex-to-check-a-semver-string
     * @see https://regex101.com/r/Ly7O1x/3/
     */
    private const REGEX = '/^(?P<major>0|[1-9]\d*)\.(?P<minor>0|[1-9]\d*)\.(?P<patch>0|[1-9]\d*)(?:-(?P<prerelease>(?:0|[1-9]\d*|\d*[a-zA-Z-][0-9a-zA-Z-]*)(?:\.(?:0|[1-9]\d*|\d*[a-zA-Z-][0-9a-zA-Z-]*))*))?(?:\+(?P<buildmetadata>[0-9a-zA-Z-]+(?:\.[0-9a-zA-Z-]+)*))?$/';

    private function __construct(
        private readonly string $value,
        private readonly Major $major,
        private readonly Minor $minor,
        private readonly Patch $patch,
        private readonly PreRelease $preRelease,
    ) {
    }

    /**
     * @throws Exception\InvalidVersion
     */
    public static function fromString(string $value): self
    {
        if (1 !== \preg_match(self::REGEX, $value, $matches)) {
            throw Exception\InvalidVersion::fromString($value);
        }

        $preRelease = PreRelease::empty();

        if (
            \array_key_exists('prerelease', $matches)
            && '' !== $matches['prerelease']
        ) {
            $preRelease = PreRelease::fromString($matches['prerelease']);
        }

        return new self(
            $value,
            Major::fromString($matches['major']),
            Minor::fromString($matches['minor']),
            Patch::fromString($matches['patch']),
            $preRelease,
        );
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function major(): Major
    {
        return $this->major;
    }

    public function minor(): Minor
    {
        return $this->minor;
    }

    public function patch(): Patch
    {
        return $this->patch;
    }

    public function preRelease(): PreRelease
    {
        return $this->preRelease;
    }
}
