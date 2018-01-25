# eclipxe/CfdiUtils

[![Source Code][badge-source]][source]
[![Latest Version][badge-release]][release]
[![Software License][badge-license]][license]
[![Build Status][badge-build]][build]
[![Scrutinizer][badge-quality]][quality]
[![Coverage Status][badge-coverage]][coverage]
[![Total Downloads][badge-downloads]][downloads]
[![SensioLabsInsight][badge-sensiolabs]][sensiolabs]

> PHP Common utilities for Mexican CFDI 3.2 & 3.3

This library provides helper objects to work with Mexican CFDI (Comprobante Fiscal Digital por Internet).

The [documentation] related to this library and its API is documented inside the [GutHub Wiki][documentation]
and is written in **spanish language** since is the language if the user.

Main features:
- Create CFDI version 3.3 based on a friendly extendable non-xml objects (`nodes`)
- Read CFDI version 3.2 and 3.3
- Validate CFDI version 3.3 against schemas, cfdi signature (`Sello`) and custom rules
- Validate that the Timbre Fiscal Digital signature match with the CFDI 3.3,
  if not then the document was modified after signature. 
- Helper objects to deal with:
    - `Cadena de origen` generation
    - Extract information from CER files or `Certificado` attribute
    - Calculate `Comprobante` sums based on the list of `Conceptos`
    - Retrieve the CFDI version information
- Keep a local copy of the three of XSD and XSLT file dependences from SAT


## Installation

Use [composer](https://getcomposer.org/), so please run
```shell
composer require eclipxe/cfdiutils
```


## Major versions

- Version 1.x **deprecated** was deprecated time ago, that version didn't do much anyway
- Version 2.x **current** has a lot of features and helper objects
- Version 3.x **future** will be released with the following backward compatibility breaks:
    - Rename `\CfdiUtils\CadenaOrigen\CadenaOrigenBuilder` to `\CfdiUtils\CadenaOrigen\DOMBuilder`
    - Rename `\CfdiUtils\CadenaOrigen\DefaultLocations` to `\CfdiUtils\CadenaOrigen\CfdiDefaultLocations`
    - Remove `\CfdiUtils\CadenaOrigen\CadenaOrigenLocations`


## PHP Support

This library is compatible with PHP versions 7.0 and above.
Please, try to use the full potential of the language like type declarations.


## Contributing

Contributions are welcome! Please read [CONTRIBUTING][] for details
and don't forget to take a look in the [TODO][] and [CHANGELOG][] files.


## Copyright and License

The `eclipxe/CfdiUtils` library is copyright © [Carlos C Soto](http://eclipxe.com.mx/)
and licensed for use under the MIT License (MIT). Please see [LICENSE][] for more information.


[contributing]: https://github.com/eclipxe13/CfdiUtils/blob/master/CONTRIBUTING.md
[changelog]: https://github.com/eclipxe13/CfdiUtils/blob/master/docs/CHANGELOG.md
[todo]: https://github.com/eclipxe13/CfdiUtils/blob/master/docs/TODO.md
[documentation]: https://github.com/eclipxe13/CfdiUtils/wiki

[source]: https://github.com/eclipxe13/CfdiUtils
[release]: https://github.com/eclipxe13/CfdiUtils/releases
[license]: https://github.com/eclipxe13/CfdiUtils/blob/master/LICENSE
[build]: https://travis-ci.org/eclipxe13/CfdiUtils?branch=master
[quality]: https://scrutinizer-ci.com/g/eclipxe13/CfdiUtils/
[sensiolabs]: https://insight.sensiolabs.com/projects/87975c73-2f3b-480a-8cce-e78b15986d7b
[coverage]: https://scrutinizer-ci.com/g/eclipxe13/CfdiUtils/code-structure/master/code-coverage
[downloads]: https://packagist.org/packages/eclipxe/CfdiUtils

[badge-source]: http://img.shields.io/badge/source-eclipxe13/CfdiUtils-blue.svg?style=flat-square
[badge-release]: https://img.shields.io/github/release/eclipxe13/CfdiUtils.svg?style=flat-square
[badge-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[badge-build]: https://img.shields.io/travis/eclipxe13/CfdiUtils/master.svg?style=flat-square
[badge-quality]: https://img.shields.io/scrutinizer/g/eclipxe13/CfdiUtils/master.svg?style=flat-square
[badge-sensiolabs]: https://insight.sensiolabs.com/projects/87975c73-2f3b-480a-8cce-e78b15986d7b/mini.png
[badge-coverage]: https://img.shields.io/scrutinizer/coverage/g/eclipxe13/CfdiUtils/master.svg?style=flat-square
[badge-downloads]: https://img.shields.io/packagist/dt/eclipxe/CfdiUtils.svg?style=flat-square
