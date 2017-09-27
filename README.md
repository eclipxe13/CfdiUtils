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

This library provides helper objects to work with cfdi. Currently it has:

- `CadenaOrigen`: Class to build the "cadena de origen" that ensures that the information
  has not been modified. It works using Xslt.
- `Certificado`: Class to read and obtain information about a CER file.
  Allows verify if a signature or a private key corresponds to the certificate.
- `Cfdi`: Class to read and check the version (3.2 or 3.3) of a Cfdi.
- `CfdiCertificado`: Class to extract a certificate, store to a file or return a `Certificado` object.

Take a look in docs folder to know about this classes (spanish information).

Also, take a look in other helper libraries that can be used in combination with this library:
- `eclipxe/xmlresourceretriever`: XSD and XLST resource downloader for local storage
- `eclipxe/xmlschemavalidator`: PHP Library for XML Schema Validations
- `eclipxe/buzoncfdi-cfdireader`: Library to read and validate a Mexican CFDI 3.2 and 3.3 (Comprobante Fiscal por Internet)

## Installation

Use [composer](https://getcomposer.org/), so please run
```shell
composer require eclipxe/cfdiutils
```


## PHP Support

This library is compatible with PHP versions 7.0 and above.
Please, try to use the full potential of the language.


## Contributing

Contributions are welcome! Please read [CONTRIBUTING][] for details
and don't forget to take a look in the [TODO][] and [CHANGELOG][] files.


## Copyright and License

The eclipxe/CfdiUtils library is copyright © [Carlos C Soto](http://eclipxe.com.mx/)
and licensed for use under the MIT License (MIT). Please see [LICENSE][] for more information.


[contributing]: https://github.com/eclipxe13/CfdiUtils/blob/master/CONTRIBUTING.md
[changelog]: https://github.com/eclipxe13/CfdiUtils/blob/master/docs/CHANGELOG.md
[todo]: https://github.com/eclipxe13/CfdiUtils/blob/master/docs/TODO.md

[source]: https://github.com/eclipxe13/CfdiUtils
[release]: https://github.com/eclipxe13/CfdiUtils/releases
[license]: https://github.com/eclipxe13/CfdiUtils/blob/master/LICENSE
[build]: https://travis-ci.org/eclipxe13/CfdiUtils?branch=master
[quality]: https://scrutinizer-ci.com/g/eclipxe13/CfdiUtils/
[sensiolabs]: https://insight.sensiolabs.com/projects/:INSIGHT_UUID
[coverage]: https://scrutinizer-ci.com/g/eclipxe13/CfdiUtils/code-structure/master
[downloads]: https://packagist.org/packages/eclipxe/CfdiUtils

[badge-source]: http://img.shields.io/badge/source-eclipxe13/CfdiUtils-blue.svg?style=flat-square
[badge-release]: https://img.shields.io/github/release/eclipxe13/CfdiUtils.svg?style=flat-square
[badge-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[badge-build]: https://img.shields.io/travis/eclipxe13/CfdiUtils/master.svg?style=flat-square
[badge-quality]: https://img.shields.io/scrutinizer/g/eclipxe13/CfdiUtils/master.svg?style=flat-square
[badge-sensiolabs]: https://insight.sensiolabs.com/projects/:INSIGHT_UUID/mini.png
[badge-coverage]: https://img.shields.io/scrutinizer/coverage/g/eclipxe13/CfdiUtils/master.svg?style=flat-square
[badge-downloads]: https://img.shields.io/packagist/dt/eclipxe/CfdiUtils.svg?style=flat-square
