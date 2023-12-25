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

$version = Version\Version::fromString('1.2.3');

echo $version->toString(); // 1.2.3

echo $version->major()->toString(); // 1
echo $version->minor()->toString(); // 2
echo $version->patch()->toString(); // 3
```

### Compare a `Version` with another `Version`

```php
<?php

declare(strict_types=1);

use Ergebnis\Version;

$one = Version\Version::fromString('1.2.3');
$two = Version\Version::fromString('1.2.3');
$three = Version\Version::fromString('1.2.4');

$one->equals($two); // true
$one->equals($three); // false
```

### Create a `Major` from a `string`

```php
<?php

declare(strict_types=1);

use Ergebnis\Version;

$major = Version\Major::fromString('1');

echo $major->toString(); // 1
```

### Compare a `Major` with another `Major`

```php
<?php

declare(strict_types=1);

use Ergebnis\Version;

$one = Version\Major::::fromString('1');
$two = Version\Major::fromString('1');
$three = Version\Major::fromString('2');

$one->equals($two); // true
$one->equals($three); // false

$one->equals($two); // true
```

### Create a `Minor` from a `string`

```php
<?php

declare(strict_types=1);

use Ergebnis\Version;

$minor = Version\Minor::fromString('1');

echo $minor->toString(); // 1
```

### Compare a `Minor` with another `Minor`

```php
<?php

declare(strict_types=1);

use Ergebnis\Version;

$one = Version\Minor::::fromString('1');
$two = Version\Minor::fromString('1');
$three = Version\Minor::fromString('2');

$one->equals($two); // true
$one->equals($three); // false

$one->equals($two); // true
```

### Create a `Patch` from a `string`

```php
<?php

declare(strict_types=1);

use Ergebnis\Version;

$patch = Version\Patch::fromString('1');

echo $patch->toString(); // 1
```

### Compare a `Patch` with another `Patch`

```php
<?php

declare(strict_types=1);

use Ergebnis\Version;

$one = Version\Patch::::fromString('1');
$two = Version\Patch::fromString('1');
$three = Version\Patch::fromString('2');

$one->equals($two); // true
$one->equals($three); // false

$one->equals($two); // true
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
