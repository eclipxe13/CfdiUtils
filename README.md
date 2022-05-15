# eclipxe/CfdiUtils

[![Source Code][badge-source]][source]
[![Discord][badge-discord]][discord]
[![Latest Version][badge-release]][release]
[![Software License][badge-license]][license]
[![Build Status][badge-build]][build]
[![Source Code][badge-documentation]][documentation]
[![Scrutinizer][badge-quality]][quality]
[![Coverage Status][badge-coverage]][coverage]
[![Total Downloads][badge-downloads]][downloads]

> PHP Common utilities for Mexican CFDI 3.2, 3.3 & 4.0.

This library provides helper objects to work with Mexican CFDI (Comprobante Fiscal Digital por Internet).

:mexico: Visita la **documentación en español** de esta librería en [Read the docs][documentation].
También te esperamos en el canal [#phpcfdi de discord](https://discord.gg/aFGYXvX).

The documentation related to this library and its API is on [Read the docs][documentation].
It is written in **spanish language** since is the language of the intended audience.

**Nota: Este proyecto será migrado a `phpcfdi/cfdiutils`, aún no tenemos fecha planeada**

No olvides visitar <https://www.phpcfdi.com> donde contamos con muchas más librerías relacionadas con
CFDI y herramientas del SAT. Y próximamente el lugar donde publicaremos la versión `3.y.z`.

## Main features

- Create CFDI version 3.3 & 4.0 based on a friendly extendable non-xml objects (`nodes`).
- Read CFDI version 3.2, 3.3 & 4.0.
- Validate CFDI version 3.3 & 4.0 against schemas, cfdi signature (`Sello`) and custom rules.
- Validate that the Timbre Fiscal Digital signature match with the CFDI 3.3 & CFDI 4.0,
  if not then the document has been modified after signature.
- Validates the "Complemento de recepción de pagos".
- Helper objects to deal with:
    - `Cadena de origen` generation.
    - Extract information from CER files or `Certificado` attribute.
    - Calculate `Comprobante` sums based on the list of `Conceptos`.
    - Retrieve the CFDI version information.
- Keep a local copy of the tree of XSD and XSLT file dependencies from SAT.
- Keep a local copy of certificates to avoid downloads them each time.
- Check the SAT WebService to get the status of a CFDI (*Estado*, *EsCancelable* and *EstatusCancelacion*) without WSDL.


## Installation

Use [composer](https://getcomposer.org/), so please run

```shell
composer require eclipxe/cfdiutils
```


## Major versions

- Version 1.x **deprecated** was deprecated time ago, that version didn't do much anyway.
- Version 2.x **current** has a lot of features and helper objects.
- Version 3.x **future** will be released with backward compatibility breaks.
    - See [docs/CHANGELOG.md](docs/CHANGELOG.md) for backward compatibility breaks.
    - It may change to PHP 8.0.
    - It could be possible to migrate to `phpcfdi/cfdi-utils` under [phpCfdi][] organization.


## PHP Support

This library is compatible with **PHP 7.3 and above**. Please, try to use the language's full potential.

The intended support is to be aligned with the oldest *Active support* PHP Branch.
See <https://www.php.net/supported-versions.php> for more details.

| CfdiUtils | PHP Supported versions  | Since      |
|-----------|-------------------------|------------|
| 1.0       | 7.0, 7.1                | 2017-09-27 |
| 2.0       | 7.0, 7.1                | 2018-01-01 |
| 2.0.1     | 7.0, 7.1, 7.2           | 2018-01-03 |
| 2.8.1     | 7.0, 7.1, 7.2, 7.3      | 2019-03-05 |
| 2.12.7    | 7.0, 7.1, 7.2, 7.3, 7.4 | 2019-12-04 |
| 2.15.0    | 7.3, 7.4, 8.0           | 2021-03-17 |
| 2.20.1    | 7.3, 7.4, 8.0, 8.1      | 2022-03-08 |

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
[documentation]: https://cfdiutils.readthedocs.io/
[discord]: https://discord.gg/aFGYXvX
[release]: https://github.com/eclipxe13/CfdiUtils/releases
[license]: https://github.com/eclipxe13/CfdiUtils/blob/master/LICENSE
[build]: https://github.com/eclipxe13/CfdiUtils/actions/workflows/build.yml?query=branch:master
[quality]: https://scrutinizer-ci.com/g/eclipxe13/CfdiUtils/?branch=master
[coverage]: https://scrutinizer-ci.com/g/eclipxe13/CfdiUtils/code-structure/master/code-coverage/src/CfdiUtils/
[downloads]: https://packagist.org/packages/eclipxe/CfdiUtils

[badge-source]: https://img.shields.io/badge/source-eclipxe13/CfdiUtils-blue?logo=github&style=flat-square
[badge-documentation]: https://img.shields.io/readthedocs/cfdiutils/latest?logo=read-the-docs&style=flat-square
[badge-discord]: https://img.shields.io/discord/459860554090283019?logo=discord&style=flat-square
[badge-release]: https://img.shields.io/github/release/eclipxe13/CfdiUtils?logo=git&style=flat-square
[badge-license]: https://img.shields.io/github/license/eclipxe13/CfdiUtils?logo=open-source-initiative&style=flat-square
[badge-build]: https://img.shields.io/github/workflow/status/eclipxe13/CfdiUtils/build/master?logo=github-actions&style=flat-square
[badge-quality]: https://img.shields.io/scrutinizer/g/eclipxe13/CfdiUtils/master?logo=scrutinizer-ci&style=flat-square
[badge-coverage]: https://img.shields.io/scrutinizer/coverage/g/eclipxe13/CfdiUtils/master?logo=scrutinizer-ci&style=flat-square
[badge-downloads]: https://img.shields.io/packagist/dt/eclipxe/CfdiUtils?logo=composer&style=flat-square
