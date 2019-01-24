# CfdiUtils

[![Source Code][badge-source]][source]
[![Gitter][badge-gitter]][gitter]
[![Latest Version][badge-release]][release]
[![Software License][badge-license]][license]
[![Build Status][badge-build]][build]
[![AppVeyor Status][badge-appveyor]][appveyor]
[![Source Code][badge-documentation]][documentation]
[![Scrutinizer][badge-quality]][quality]
[![Coverage Status][badge-coverage]][coverage]
[![Total Downloads][badge-downloads]][downloads]
[![SensioLabsInsight][badge-sensiolabs]][sensiolabs]

[`eclipxe/CfdiUtils`](https://github.com/eclipxe13/CfdiUtils)
es una librería de PHP para leer, validar y crear CFDI 3.3.

Mira el archivo [README][] para información rápida (en inglés).

!!! note ""
    Este proyecto se migrará eventualmente a `phpcfdi/cfdiutils`, aun no hay fecha planeada.

La motivación de crear esta librería es contar con una herramienta flexible, rápida y
confiable para trabajar con CFDI. Se pretende que sea utilizada por la comunidad de PHP
México, en proyectos privados o proyectos libres como el futuro "BuzonCFDI".

Esta librería se ha liberado como software libre para ayudar a otros desarrolladores a
trabajar con CFDI y también para obtener su ayuda, todo lo que la comunidad pueda
contribuir será bien apreciado. Tenemos una comunidad activa y dinámica, nos puedes
encontrar en [el canal de gitter](https://gitter.im/eclipxe13/php-cfdi).

## Lectura de CFDI

La librería ofrece métodos para leer CFDI versión 3.2 y 3.3.

- [Lectura formal de un CFDI](leer/leer-cfdi.md)
- [Lectura rápida de un CFDI](leer/quickreader.md)
- [Limpieza de un CFDI](leer/limpieza-cfdi.md)


## Validación de CFDI

Solo hay validadores para CFDI 3.3.

- [Validar un CFDI 3.3](validar/validacion-cfdi.md)
- [Validaciones estándar](validar/validaciones-estandar.md)


## Escritura de CFDI

Solo hay métodos específicos para CFDI 3.3.

- [Crear un CFDI 3.3](crear/crear-cfdi.md)
- [Elementos de CFDI](crear/elements-cfdi33.md)
- [Agregar complementos](crear/complementos-aun-no-implementados.md)
- [CFDI Retenciones](crear/cfdi-de-retenciones-e-informacion-de-pagos.md)

## Componentes comunes

- [Estructura de datos `Nodes`](componentes/nodes.md)
- [Estructura de datos `Elements`](componentes/elements.md)
- [Almacenamiento local de recursos del SAT](componentes/xmlresolver.md)
- [Certificados](componentes/certificado.md)
- [Consultar estado de un CFDI](componentes/estado-sat.md)
- [Generación de cadena original](componentes/cadena-de-origen.md)


## Contribuciones

- [Listado de tareas pendientes e ideas](TODO.md)
- [Guía de contribución para desarrolladores](contribuir/guia-desarrollador.md)
- [Guía de contribución para documentadores](contribuir/guia-documentador.md)
- [Guía de contribución para MS Windows](contribuir/guia-windows.md)
- Reportar un problema


## Recursos útiles

- [Listado de cambios](CHANGELOG.md) (en inglés)
- [Página del SAT de CFDI](http://omawww.sat.gob.mx/informacion_fiscal/factura_electronica/Paginas/Anexo_20_version3.3.aspx)


## Problemas conocidos

- [Contradicciones de CFDI de Pagos](problemas/contradicciones-pagos.md)
- [Descarga de certificados](problemas/descarga-certificados.md)


## Copyright and License

The `eclipxe/CfdiUtils` library is copyright © [Carlos C Soto](http://eclipxe.com.mx/) and
licensed for use under the MIT License (MIT). Please see [LICENSE][] for more information.

La librería  `eclipxe/CfdiUtils` tiene copyright © [Carlos C Soto](http://eclipxe.com.mx/)
y se encuentra amparada por la Licencia MIT (MIT).
Consulte el archivo [LICENSE][] para más información.


[readme]: https://github.com/eclipxe13/CfdiUtils/blob/master/README.md

[source]: https://github.com/eclipxe13/CfdiUtils
[documentation]: https://cfdiutils.readthedocs.io/
[gitter]: https://gitter.im/eclipxe13/php-cfdi
[release]: https://github.com/eclipxe13/CfdiUtils/releases
[license]: https://github.com/eclipxe13/CfdiUtils/blob/master/LICENSE
[build]: https://travis-ci.org/eclipxe13/CfdiUtils?branch=master
[appveyor]: https://ci.appveyor.com/project/eclipxe13/cfdiutils
[quality]: https://scrutinizer-ci.com/g/eclipxe13/CfdiUtils/
[sensiolabs]: https://insight.sensiolabs.com/projects/87975c73-2f3b-480a-8cce-e78b15986d7b
[coverage]: https://scrutinizer-ci.com/g/eclipxe13/CfdiUtils/code-structure/master/code-coverage
[downloads]: https://packagist.org/packages/eclipxe/CfdiUtils

[badge-source]: http://img.shields.io/badge/source-eclipxe13/CfdiUtils-blue.svg?logo=github&style=flat-square
[badge-documentation]: https://img.shields.io/readthedocs/cfdiutils/stable.svg?style=flat-square
[badge-gitter]: https://img.shields.io/gitter/room/nwjs/nw.js.svg?style=flat-square
[badge-release]: https://img.shields.io/github/release/eclipxe13/CfdiUtils.svg?style=flat-square
[badge-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[badge-build]: https://img.shields.io/travis/eclipxe13/CfdiUtils/master.svg?logo=travis&style=flat-square
[badge-appveyor]: https://img.shields.io/appveyor/ci/eclipxe13/cfdiutils/master.svg?logo=appveyor&style=flat-square
[badge-quality]: https://img.shields.io/scrutinizer/g/eclipxe13/CfdiUtils/master.svg?logo=scrutinizer&style=flat-square
[badge-sensiolabs]: https://insight.sensiolabs.com/projects/87975c73-2f3b-480a-8cce-e78b15986d7b/mini.png
[badge-coverage]: https://img.shields.io/scrutinizer/coverage/g/eclipxe13/CfdiUtils/master.svg?style=flat-square
[badge-downloads]: https://img.shields.io/packagist/dt/eclipxe/CfdiUtils.svg?style=flat-square
