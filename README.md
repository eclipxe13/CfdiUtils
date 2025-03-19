# eclipxe/CfdiUtils

[![Source Code][badge-source]][source]
[![Discord][badge-discord]][discord]
[![Packagist PHP Version Support][badge-php-version]][php-version]
[![Latest Version][badge-release]][release]
[![Software License][badge-license]][license]
[![Build Status][badge-build]][build]
[![Reliability][badge-reliability]][reliability]
[![Maintainability][badge-maintainability]][maintainability]
[![Code Coverage][badge-coverage]][coverage]
[![Violations][badge-violations]][violations]
[![Source Code][badge-documentation]][documentation]
[![Total Downloads][badge-downloads]][downloads]

> PHP Common utilities for Mexican CFDI 3.2, 3.3 & 4.0.

This library provides helper objects to work with Mexican CFDI (Comprobante Fiscal Digital por Internet).

:mexico: Visita la **documentación en español** de esta librería en [Read the docs][documentation].
También te esperamos en el canal [#phpcfdi de discord](https://discord.gg/aFGYXvX).

The documentation related to this library and its API is on [Read the docs][documentation].
It is written in **spanish language** since is the language of the intended audience.

**Nota: Este proyecto será migrado a `phpcfdi/cfdiutils`, aún no tenemos fecha planeada**

No olvides visitar <https://www.phpcfdi.com> donde contamos con muchas más librerías relacionadas con
CFDI y herramientas del SAT. Y próximamente el lugar donde publicaremos la versión `4.x`.

## Main features

- Create CFDI version 3.3 & 4.0 based on a friendly extendable non XML objects (`nodes`).
- Read CFDI version 3.2, 3.3 & 4.0.
- Validate CFDI version 3.3 & 4.0 against schemas, CFDI signature (`Sello`) and custom rules.
- Validate that the Timbre Fiscal Digital signature match with the CFDI 3.3 & CFDI 4.0.
  If signature doesn't match, then the document has been modified after sealed.
- Helper objects to deal with:
    - `Cadena de origen` generation.
    - Extract information from CER files or `Certificado` attribute.
    - Calculate `Comprobante` sums based on the list of `Conceptos`.
    - Retrieve the CFDI version information.
- Keep a local copy of the tree of XSD and XSLT file dependencies from SAT.
- Keep a local copy of certificates to avoid download them each time.
- Check the SAT WebService to get the status of a CFDI (*`Estado`*, *`EsCancelable`*, *`EstatusCancelacion`* and *`EFOS`*) without WSDL.


## Installation

Use [composer](https://getcomposer.org/), so please run

```shell
composer require eclipxe/cfdiutils
```


## Major versions

- Version 1.x **deprecated** was deprecated time ago, that version didn't do much anyway.
- Version 2.x **deprecated** has a lot of features and helper objects.
- Version 3.x **current** is a maintenance release for compatibility with PHP 8.4.
- Version 4.x **future** will be released with backward compatibility breaks.
    - See [docs/CHANGELOG.md](docs/CHANGELOG.md) for backward compatibility breaks.
    - It may change to PHP 8.2.
    - It could be possible to migrate to `phpcfdi/cfdi-utils` under [phpCfdi][] organization.


## PHP Support

This library is compatible with **PHP 8.0 and above**. Please, try to use the language's full potential.

The intended support is to be aligned with the oldest *Active support* PHP Branch.
See <https://www.php.net/supported-versions.php> for more details.

| CfdiUtils | PHP Supported versions       | Since      |
|-----------|------------------------------|------------|
| 1.0       | 7.0, 7.1                     | 2017-09-27 |
| 2.0       | 7.0, 7.1                     | 2018-01-01 |
| 2.0.1     | 7.0, 7.1, 7.2                | 2018-01-03 |
| 2.8.1     | 7.0, 7.1, 7.2, 7.3           | 2019-03-05 |
| 2.12.7    | 7.0, 7.1, 7.2, 7.3, 7.4      | 2019-12-04 |
| 2.15.0    | 7.3, 7.4, 8.0                | 2021-03-17 |
| 2.20.1    | 7.3, 7.4, 8.0, 8.1           | 2022-03-08 |
| 2.23.5    | 7.3, 7.4, 8.0, 8.1, 8.2, 8.3 | 2023-05-26 |
| 3.0.0     | 8.0, 8.1, 8.2, 8.3, 8.4      | 2025-03-18 |


## Contributing

Contributions are welcome! Please read [CONTRIBUTING][] for details
and don't forget to take a look in the [TODO][] and [CHANGELOG][] files.


## Copyright and License

The `eclipxe/CfdiUtils` library is copyright © [Carlos C Soto](http://eclipxe.com.mx/)
and licensed for use under the MIT License (MIT). Please see [LICENSE][] for more information.


[contributing]: https://github.com/eclipxe13/CfdiUtils/blob/master/CONTRIBUTING.md
[changelog]: https://github.com/eclipxe13/CfdiUtils/blob/master/docs/CHANGELOG.md
[todo]: https://github.com/eclipxe13/CfdiUtils/blob/master/docs/TODO.md
[phpcfdi]: https://github.com/phpCfdi

[source]: https://github.com/eclipxe13/CfdiUtils
[php-version]: https://packagist.org/packages/eclipxe/cfdiutils
[documentation]: https://cfdiutils.readthedocs.io/
[discord]: https://discord.gg/aFGYXvX
[release]: https://github.com/eclipxe13/CfdiUtils/releases
[license]: https://github.com/eclipxe13/CfdiUtils/blob/master/LICENSE
[build]: https://github.com/eclipxe13/CfdiUtils/actions/workflows/build.yml?query=branch:master
[reliability]:https://sonarcloud.io/component_measures?id=eclipxe13_cfdiutils&metric=Reliability
[maintainability]: https://sonarcloud.io/component_measures?id=eclipxe13_cfdiutils&metric=Maintainability
[coverage]: https://sonarcloud.io/component_measures?id=eclipxe13_cfdiutils&metric=Coverage
[violations]: https://sonarcloud.io/project/issues?id=eclipxe13_cfdiutils&resolved=false
[downloads]: https://packagist.org/packages/eclipxe/CfdiUtils

[badge-source]: https://img.shields.io/badge/source-eclipxe13/CfdiUtils-blue?logo=github
[badge-php-version]: https://img.shields.io/packagist/php-v/eclipxe/cfdiutils?logo=php
[badge-documentation]: https://img.shields.io/readthedocs/cfdiutils/latest?logo=read-the-docs
[badge-discord]: https://img.shields.io/discord/459860554090283019?logo=discord
[badge-release]: https://img.shields.io/github/release/eclipxe13/CfdiUtils?logo=git
[badge-license]: https://img.shields.io/github/license/eclipxe13/CfdiUtils?logo=open-source-initiative
[badge-build]: https://img.shields.io/github/actions/workflow/status/eclipxe13/CfdiUtils/build.yml?branch=master&logo=github-actions
[badge-reliability]: https://sonarcloud.io/api/project_badges/measure?project=eclipxe13_cfdiutils&metric=reliability_rating
[badge-maintainability]: https://sonarcloud.io/api/project_badges/measure?project=eclipxe13_cfdiutils&metric=sqale_rating
[badge-coverage]: https://img.shields.io/sonar/coverage/eclipxe13_cfdiutils/master?logo=sonarcloud&server=https%3A%2F%2Fsonarcloud.io
[badge-violations]: https://img.shields.io/sonar/violations/eclipxe13_cfdiutils/master?format=long&logo=sonarcloud&server=https%3A%2F%2Fsonarcloud.io
[badge-downloads]: https://img.shields.io/packagist/dt/eclipxe/CfdiUtils?logo=composer
