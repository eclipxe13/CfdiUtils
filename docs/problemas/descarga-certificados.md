# Descarga de certificados desde el SAT

Es necesario descargar los certificados de los PAC para poder validar el atributo `SelloCFDI`
del nodo `tfd:TimbreFiscalDigital` porque no viene incluido en la estructura.

Siendo así, se utilizan las clases `CfdiUtils\Certificado\SatCertificateNumber` y la clase `CfdiUtils\Certificado\CerRetriever`.

`SatCertificateNumber` contiene el método `remoteUrl(): string` que devuelve una URL que apunta a `https://rdc.sat.gob.mx/rccf/`.

El problema es que a partir de 2018-09-12 en el servidor `rdc.sat.gob.mx` el servidor web retorna certificados
para el protocolo `https` vigentes y expirados:

- Expirado: `4D:CE:6C:8E:0D:C6:4C:E3` vigente hasta `2018-09-22 16:07:04 GMT`
- Vigente: `00:A7:06:AA:42:44:4E:E4:E9:00:00:00:00:58:08:91:5B` vigente hasta `2020-09-12 16:41:28 GMT`

No hay una tendencia, de una muestra de 1,000 descargas realizada el `2018-10-17 17:30 GMT-5` el resultado fue
incorrectas 2,853 (28.53%) y correctas 7,147 (71.47%) por lo que la posibilidad de obtener un certificado incorrecto
es cercana al 30%.

Como resultado, la conexión segura al servidor no se puede establecer y esto generará que falle
el proceso de descarga del certificado.

Al fallar la descarga del certificado entonces fallará la validación `TFDSELLO01` que verifica que
*El Sello SAT del Timbre Fiscal Digital corresponde al certificado SAT*. Por lo tanto, si se está usando
la librería `CfdiUtils` para comprobar un CFDI con Timbre Fiscal Digital entonces constantemente se tendrá
el problema de validación `TFDSELLO01` hasta que el SAT solucione el problema de configuración.

Como una posible solución, si al validar un CFDI se obtiene un error `TFDSELLO01` se podría reintentar validar.

```php
<?php
$maxAttempts = 10;
$sleepTime = 1 * 1000;
$currentAttempt = 1;
do {
    /** @var \CfdiUtils\CfdiValidator33 $validator */
    $asserts = $validator->validateXml(file_get_contents('cfdi.xml'));
    if ($asserts->exists('TFDSELLO01')) {
        $assert = $asserts->get('TFDSELLO01');
        if ($assert->getStatus()->isError()) {
            if ($currentAttempt <= $maxAttempts) {
                $currentAttempt = $currentAttempt + 1;
                usleep($sleepTime);
                continue;
            }
        }
    }
} while (false);
// $asserts podría tener el código 'TFDSELLO01' con estado de error después de haberlo intentado 10 veces
```

Personalmente no recomiendo desabilitar la seguridad del protocolo HTTPS, pero es una posible solución.

Se puede desactivar usando el downloader genérico `XmlResourceRetriever\Downloader\PhpDownloader`
y estableciendo un contexto que desactive la verificación de la siguiente manera.

```php
<?php
$context = stream_context_create([
    'ssl' => ['verify_peer' => false],
]);
$downloader = new \XmlResourceRetriever\Downloader\PhpDownloader($context);

/* Establecer en un objeto de tipo xmlResolver */
/** @var \CfdiUtils\XmlResolver\XmlResolver $xmlResolver */
$xmlResolver->setDownloader($downloader);

/* Establecer directamente en un objeto validador */
/** @var \CfdiUtils\CfdiValidator33 $validator */
$validator->getXmlResolver()->setDownloader($downloader);

/* Establecer directamente en un objeto creador de CFDI 3.3 */
/** @var \CfdiUtils\CfdiCreator33 $creator */
$creator->getXmlResolver()->setDownloader($downloader);
```

También se puede desactivar la verificación de SSL creando un descargador que implemente
`XmlResourceRetriever\Downloader\DownloaderInterface` y en el método `downloadTo`
hacer caso omiso de las validaciones.
