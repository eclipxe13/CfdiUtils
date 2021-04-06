# Actualizar de versión 2.x a 3.x

!!! note ""
    Este documento aún no está terminado, conforme se realicen los cambios es necesario actualizar este documento.

## Uso de `XmlResourceRetriever`

La librería `eclipxe/XmlResourceRetriever` se cambió a la versión `3.0.0`.
Si en tu implementación estás usando algún objeto bajo el espacio de nombres `XmlResourceRetriever`
(como `DownloaderInterface`) deberás cambiarlo por `Eclipxe\XmlResourceRetriever`

```diff
- use XmlResourceRetriever\Downloader\DownloaderInterface;
+ use Eclipxe\XmlResourceRetriever\Downloader\DownloaderInterface;
```
