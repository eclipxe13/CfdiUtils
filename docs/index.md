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

El proyecto [`eclipxe/CfdiUtils`](https://github.com/eclipxe13/CfdiUtils)
es una librería de PHP para leer, validar y crear CFDI 3.3 & CFDI 4.0.

Mira el archivo [README][] para información rápida (en inglés).

!!! note ""
    Este proyecto se migrará eventualmente a `phpcfdi/cfdiutils`, aún no hay fecha planeada.

La motivación de crear esta librería es contar con una herramienta flexible, rápida y
confiable para trabajar con CFDI. Se pretende que sea utilizada por la comunidad de PHP
México, en proyectos privados o proyectos libres como `BuzonCFDI`.

Esta librería se ha liberado como software libre para ayudar a otros desarrolladores a
trabajar con CFDI y también para obtener su ayuda, todo lo que la comunidad pueda
contribuir será bien apreciado. Tenemos una comunidad activa y dinámica, nos puedes
encontrar en el canal [#phpcfdi de discord][discord].

No olvides visitar <https://www.phpcfdi.com> donde contamos con muchas más librerías relacionadas con
CFDI y herramientas del SAT. Y próximamente el lugar donde publicaremos la versión `3.y.z`.

## Instalación

- [Instalación de CfdiUtils](instalar/instalacion.md)

## Lectura de CFDI

La librería ofrece métodos para leer CFDI versión 3.2, 3.3 & 4.0.

- [Lectura formal de un CFDI](leer/leer-cfdi.md)
- [Lectura formal de un CFDI de Retenciones](leer/leer-cfdi-retenciones.md)
- [Lectura rápida de un CFDI](leer/quickreader.md)
- [Limpieza de un CFDI](leer/limpieza-cfdi.md)


## Validación de CFDI

Validadores para CFDI 3.3 y CFDI 4.0.

- [Validar un CFDI 3.3](validar/validacion-cfdi.md)
- [Validaciones estándar](validar/validaciones-estandar.md)
- [Validar un CFDI 4.0](validar/validacion-cfdi-40.md)
- [Validaciones 4.0](validar/validaciones-40.md)


## Escritura de CFDI

Solo hay métodos específicos para CFDI 3.3 y CFDI 4.0.

- [Crear un CFDI 3.3](crear/crear-cfdi.md)
- [Elementos de CFDI 3.3](crear/elements-cfdi33.md)
- [Crear un CFDI 4.0](crear/crear-cfdi-40.md)
- [Elementos de Nómina 1.2 revisión B](crear/complemento-nomina12b.md)
- [Agregar complementos](crear/complementos-aun-no-implementados.md)
- [CFDI Retenciones](crear/cfdi-de-retenciones-e-informacion-de-pagos.md)


## Componentes comunes

- [Estructura de datos `Nodes`](componentes/nodes.md)
- [Estructura de datos `Elements`](componentes/elements.md)
- [Almacenamiento local de recursos del SAT](componentes/xmlresolver.md)
- [Certificados](componentes/certificado.md)
- [Consultar estado de un CFDI](componentes/estado-sat.md)
- [Generación de cadena original](componentes/cadena-de-origen.md)


## Utilerías

- [OpenSSL](utilerias/openssl.md)


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
- [Múltiples complementos](problemas/multiples-complementos.md)
- [Descarga de recursos XSD y XSLT](problemas/descarga-recursos.md)


## Copyright and License

The `eclipxe/CfdiUtils` library is copyright © [Carlos C Soto](http://eclipxe.com.mx/)
and licensed for use under the MIT License (MIT). Please see [LICENSE][] for more information.

La librería  `eclipxe/CfdiUtils` tiene copyright © [Carlos C Soto](http://eclipxe.com.mx/)
y se encuentra amparada por la Licencia MIT (MIT). Consulte el archivo [LICENSE][] para más información.


[readme]: https://github.com/eclipxe13/CfdiUtils/blob/master/README.md

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
