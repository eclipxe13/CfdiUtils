# Actualizar de versión 2.x a 3.x

!!! note ""
    Este documento aún no está terminado, conforme se realicen los cambios es necesario actualizar este documento.

## Separación del limpiador a `phpcfdi/cfdi-cleaner`

La funcionalidad de limpieza de CFDI se movió a [`phpcfdi/cfdi-cleaner`](https://github.com/phpcfdi/cfdi-cleaner).

```diff
- $xmlContent = \CfdiUtils\Cleaner\Cleaner::staticClean($xmlContent);
+ $xmlContent = \PhpCfdi\CfdiCleaner\Cleaner::staticClean($xmlContent);
```

## Uso de `XmlResourceRetriever`

La librería `eclipxe/XmlResourceRetriever` se cambió a la versión `3.0.0`.
Si en tu implementación estás usando algún objeto bajo el espacio de nombres `XmlResourceRetriever`
(como `DownloaderInterface`) deberás cambiarlo por `Eclipxe\XmlResourceRetriever`, por ejemplo:

```diff
- use XmlResourceRetriever\Downloader\DownloaderInterface;
+ use Eclipxe\XmlResourceRetriever\Downloader\DownloaderInterface;
```

## Separación del manejo de clases para manejar certificados y llaves privadas

Este proyecto ofrecía el objeto `CfdiUtils\Certificado\Certificado` y `CfdiUtils\PemPrivateKey\PemPrivateKey`
para manejar certificados y llaves privadas respectivamente. Sin embargo, hay una versión mucho mejor hecha,
con mejores capacidades y más estable en [`phpcfdi/credentials`](https://github.com/phpcfdi/credentials).

## Uso de `ConsultaCfdiSat`

En este espacio de nombres se podía consultar el estado de un CFDI así como formar la *expresión impresa*,
utilizada para generar los códigos QR. Ambas funcionalidades están solventadas en proyectos de PhpCfdi,
por lo que te recomiendo implementarlos:

[`phpcfdi/cfdi-expresiones`](https://github.com/phpcfdi/cfdi-expresiones) para generar la representación impresa.

[`phpcfdi/sat-estado-cfdi`](https://github.com/phpcfdi/sat-estado-cfdi) para obtener el estado de un CFDI regular,
con [`phpcfdi/sat-estado-cfdi-soap`](https://github.com/phpcfdi/sat-estado-cfdi-soap) para usar SOAP,
o [`phpcfdi/sat-estado-cfdi-http-psr`](https://github.com/phpcfdi/sat-estado-cfdi-http-psr) para usar
un cliente HTTP (PSR compatible).

[`phpcfdi/sat-estado-retenciones`](https://github.com/phpcfdi/sat-estado-retenciones) para obtener el estado
de un CFDI de Retenciones e información de pagos.

## Ejecución de deprecaciones

En la versión `2.x` existían varias clases y métodos que estaban deprecados.
En esta versión se han eliminado por completo.

Clases removidas:

- `CfdiUtils\CadenaOrigen\CadenaOrigenBuilder`.
- `CfdiUtils\CadenaOrigen\CadenaOrigenLocations`.
- `CfdiUtils\CadenaOrigen\DefaultLocations`.

Rasgos removidos:

- `CfdiUtils\VersionDiscovery\StaticMethodsCompatTrait`

Métodos removidos:

- `CfdiUtils\XmlResolver\XmlResolver::resolveCadenaOrigenLocation`.
