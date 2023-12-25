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

final class Minor
{
    /**
     * @see https://semver.org/#is-there-a-suggested-regular-expression-regex-to-check-a-semver-string
     * @see https://regex101.com/r/Ly7O1x/3/
     */
    private const REGEX = '/^(?P<minor>0|[1-9]\d*)$/';

    private function __construct(private readonly int $value)
    {
    }

    /**
     * @throws Exception\InvalidMinor
     */
    public static function fromInt(int $value): self
    {
        if (0 > $value) {
            throw Exception\InvalidMinor::fromInt($value);
        }

        return new self($value);
    }

    /**
     * @throws Exception\InvalidMinor
     */
    public static function fromString(string $value): self
    {
        if (1 !== \preg_match(self::REGEX, $value)) {
            throw Exception\InvalidMinor::fromString($value);
        }

        return new self((int) $value);
    }

    public function toInt(): int
    {
        return $this->value;
    }

    public function toString(): string
    {
        return (string) $this->value;
    }

    public function bump(): self
    {
        return new self($this->value + 1);
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }
}