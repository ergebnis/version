# version

[![Integrate](https://github.com/ergebnis/version/workflows/Integrate/badge.svg)](https://github.com/ergebnis/version/actions)
[![Merge](https://github.com/ergebnis/version/workflows/Merge/badge.svg)](https://github.com/ergebnis/version/actions)
[![Release](https://github.com/ergebnis/version/workflows/Release/badge.svg)](https://github.com/ergebnis/version/actions)
[![Renew](https://github.com/ergebnis/version/workflows/Renew/badge.svg)](https://github.com/ergebnis/version/actions)

[![Code Coverage](https://codecov.io/gh/ergebnis/version/branch/main/graph/badge.svg)](https://codecov.io/gh/ergebnis/version)
[![Type Coverage](https://shepherd.dev/github/ergebnis/version/coverage.svg)](https://shepherd.dev/github/ergebnis/version)

[![Latest Stable Version](https://poser.pugx.org/ergebnis/version/v/stable)](https://packagist.org/packages/ergebnis/version)
[![Total Downloads](https://poser.pugx.org/ergebnis/version/downloads)](https://packagist.org/packages/ergebnis/version)
[![Monthly Downloads](http://poser.pugx.org/ergebnis/version/d/monthly)](https://packagist.org/packages/ergebnis/version)

This project provides a [`composer`](https://getcomposer.org) package with an abstraction of a [semantic version](https://semver.org).

## Installation

Run

```sh
composer require ergebnis/version
```

## Usage

### Create a `Version` from a `string`

```php
<?php

declare(strict_types=1);

use Ergebnis\Version;

$version = Version\Version::fromString('1.2.3-alpha+build.9001');

echo $version->toString(); // 1.2.3

echo $version->major()->toString(); // 1
echo $version->minor()->toString(); // 2
echo $version->patch()->toString(); // 3
echo $version->preRelease()->toString(); // alpha
echo $version->buildMetaData()->toString(); // build.9001
```

### Bump a `Version`

```php
<?php

declare(strict_types=1);

use Ergebnis\Version;

$version = Version\Version::fromString('1.2.3');

$one = $version->bumpMajor();

echo $one->toString(); // 2.0.0

$two = $version->bumpMinor();

echo $two->toString(); // 1.3.0

$three = $version->bumpPatch();

echo $three->toString(); // 1.2.4
```

### Bump a `Version` with `PreRelease`

```php
<?php

declare(strict_types=1);

use Ergebnis\Version;

$version = Version\Version::fromString('1.2.3-alpha');

$one = $version->bumpMajor();

echo $one->toString(); // 2.0.0

$two = $version->bumpMinor();

echo $two->toString(); // 1.3.0

$three = $version->bumpPatch();

echo $three->toString(); // 1.2.3
```

### Bump a `Version` with `BuildMetaData`

```php
<?php

declare(strict_types=1);

use Ergebnis\Version;

$version = Version\Version::fromString('1.2.3+build.9001');

$one = $version->bumpMajor();

echo $one->toString(); // 2.0.0

$two = $version->bumpMinor();

echo $two->toString(); // 1.3.0

$three = $version->bumpPatch();

echo $three->toString(); // 1.2.4
```

### Compare a `Version` with another `Version`

```php
<?php

declare(strict_types=1);

use Ergebnis\Version;

$one = Version\Version::fromString('1.2.3-alpha');
$two = Version\Version::fromString('1.2.3');
$three = Version\Version::fromString('1.2.4');
$four = Version\Version::fromString('1.2.4+build.9001');

$one->compare($two); // -1
$one->compare($one); // 0
$three->compare($one); // 1

$one->equals($two); // false
$one->equals($one); // true
$one->equals($three); // false
$three->equals($four); // true
```

### Create a `Major` from an `int`

```php
<?php

declare(strict_types=1);

use Ergebnis\Version;

$major = Version\Major::fromInt(1);

echo $major->toString(); // 1
```

### Create a `Major` from a `string`

```php
<?php

declare(strict_types=1);

use Ergebnis\Version;

$major = Version\Major::fromString('1');

echo $major->toString(); // 1
```

### Bump a `Major`

```php
<?php

declare(strict_types=1);

use Ergebnis\Version;

$one = Version\Major::fromString('1');

$two = $one->bump();

echo $two->toString(); // 2
```

### Compare a `Major` with another `Major`

```php
<?php

declare(strict_types=1);

use Ergebnis\Version;

$one = Version\Major::fromString('1');
$two = Version\Major::fromString('2');

$one->compare($two); // -1
$one->compare($one); // 0
$two->compare($one); // 1

$one->equals($two); // false
$one->equals($one); // true
```

### Create a `Minor` from an `int`

```php
<?php

declare(strict_types=1);

use Ergebnis\Version;

$minor = Version\Minor::fromInt(1);

echo $minor->toString(); // 1
```

### Create a `Minor` from a `string`

```php
<?php

declare(strict_types=1);

use Ergebnis\Version;

$minor = Version\Minor::fromString('1');

echo $minor->toString(); // 1
```

### Bump a `Minor`

```php
<?php

declare(strict_types=1);

use Ergebnis\Version;

$one = Version\Minor::fromString('1');

$two = $one->bump();

echo $two->toString(); // 2
```

### Compare a `Minor` with another `Minor`

```php
<?php

declare(strict_types=1);

use Ergebnis\Version;

$one = Version\Minor::fromString('1');
$two = Version\Minor::fromString('2');

$one->compare($two); // -1
$one->compare($one); // 0
$two->compare($one); // 1

$one->equals($two); // false
$one->equals($one); // true
```

### Create a `Patch` from an `int`

```php
<?php

declare(strict_types=1);

use Ergebnis\Version;

$patch = Version\Patch::fromInt(1);

echo $patch->toString(); // 1
```

### Create a `Patch` from a `string`

```php
<?php

declare(strict_types=1);

use Ergebnis\Version;

$patch = Version\Patch::fromString('1');

echo $patch->toString(); // 1
```

### Bump a `Patch`

```php
<?php

declare(strict_types=1);

use Ergebnis\Version;

$one = Version\Patch::fromString('1');

$two = $one->bump();

echo $two->toString(); // 2
```

### Compare a `Patch` with another `Patch`

```php
<?php

declare(strict_types=1);

use Ergebnis\Version;

$one = Version\Patch::fromString('1');
$two = Version\Patch::fromString('2');

$one->compare($two); // -1
$one->compare($one); // 0
$two->compare($one); // 1

$one->equals($two); // false
$one->equals($one); // true
```

### Create a `PreRelease` from a `string`

```php
<?php

declare(strict_types=1);

use Ergebnis\Version;

$preRelease = Version\PreRelease::fromString('alpha');

echo $preRelease->toString(); // alpha
```

### Create an empty `PreRelease`

```php
<?php

declare(strict_types=1);

use Ergebnis\Version;

$preRelease = Version\PreRelease::empty();

echo $preRelease->toString(); // empty
```

### Compare a `PreRelease` with another `PreRelease`

```php
<?php

declare(strict_types=1);

use Ergebnis\Version;

$one = Version\PreRelease::fromString('alpha');
$two = Version\PreRelease::fromString('rc.1');

$one->compare($two); // -1
$one->compare($one); // 0
$two->compare($one); // 1

$one->equals($two); // false
$one->equals($one); // true

```

### Create a `BuildMetaData` from a `string`

```php
<?php

declare(strict_types=1);

use Ergebnis\Version;

$buildMetaData = Version\BuildMetaData::fromString('build.9001');

echo $buildMetaData->toString(); // build.9001
```

### Create an empty `BuildMetaData`

```php
<?php

declare(strict_types=1);

use Ergebnis\Version;

$buildMetaData = Version\BuildMetaData::empty();

echo $buildMetaData->toString(); // empty
```

### Compare a `BuildMetaData` with another `BuildMetaData`

```php
<?php

declare(strict_types=1);

use Ergebnis\Version;

$one = Version\BuildMetaData::fromString('build.9001');
$two = Version\BuildMetaData::fromString('build.9000');

$one->equals($two); // false
$one->equals($one); // true
```

## Changelog

The maintainers of this project record notable changes to this project in a [changelog](CHANGELOG.md).

## Contributing

The maintainers of this project suggest following the [contribution guide](.github/CONTRIBUTING.md).

## Code of Conduct

The maintainers of this project ask contributors to follow the [code of conduct](https://github.com/ergebnis/.github/blob/main/CODE_OF_CONDUCT.md).

## General Support Policy

The maintainers of this project provide limited support.

You can support the maintenance of this project by [sponsoring @localheinz](https://github.com/sponsors/localheinz) or [requesting an invoice for services related to this project](mailto:am@localheinz.com?subject=ergebnis/version:%20Requesting%20invoice%20for%20services).

## PHP Version Support Policy

This project supports PHP versions with [active and security support](https://www.php.net/supported-versions.php).

The maintainers of this project add support for a PHP version following its initial release and drop support for a PHP version when it has reached the end of security support.

## Security Policy

This project has a [security policy](.github/SECURITY.md).

## License

This project uses the [MIT license](LICENSE.md).

## Social

Follow [@localheinz](https://twitter.com/intent/follow?screen_name=localheinz) and [@ergebnis](https://twitter.com/intent/follow?screen_name=ergebnis) on Twitter.
