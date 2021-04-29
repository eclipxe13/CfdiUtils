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

## Ejecución de deprecaciones

En la versión `2.x` existían varias clases y métodos que estaban deprecados.
En esta versión se han eliminado por completo.

Clases removidas:

- `CfdiUtils\CadenaOrigen\CadenaOrigenBuilder`.
- `CfdiUtils\CadenaOrigen\CadenaOrigenLocations`.
- `CfdiUtils\CadenaOrigen\DefaultLocations`.
