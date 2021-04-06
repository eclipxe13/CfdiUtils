# Lista de tareas pendientes e ideas

- Incrementar la cobertura de PHPStan al nivel máximo.

## Tareas relacionadas con la versión 3.0

- Remove `static` methods from `\CfdiUtils\CfdiVersion`, instead create an instance of the class
- Remove `static` methods from `\CfdiUtils\TimbreFiscalDigital\TfdVersion`, instead create an instance of the class
- Remove `trigger_error` on `\CfdiUtils\Elements\Cfdi33\Comprobante::getCfdiRelacionados` when called with arguments.
- Change signature of `CfdiUtils\Elements\Cfdi33\CfdiRelacionados::multiCfdiRelacionado` to receive as parameters
  `array ...$elementAttributes` instead of `array $elementAttributes`.
- Add a method `NodeInderface::exists` as an alias of `NodeInderface::offsetExists`. Replace usages in code.
- Change visibility of `CfdiUtils\Cleaner\Cleaner#removeIncompleteSchemaLocation()` to private.
- Add `attributes(): array` method to `QuickReader`

Tasks related to CFDI Status:

- Remove `CfdiUtils\ConsultaCfdiSat\Config::getWsdlUrl()`
- Add a method to execute `CfdiUtils\ConsultaCfdiSat\StatusResponse` using an expression instead of `RequestParameters`.
- Make `CfdiUtils\ConsultaCfdiSat\StatusResponse::__constructor()` third and fourth arguments non-optional.
  Now they are optional to avoid incompatibility changes.
- Remove `CfdiUtils\ConsultaCfdiSat\Config::DEFAULT_SERVICE_URL`
- Remove `CfdiUtils\ConsultaCfdiSat\Config::getWsdlLocation()`, `CfdiUtils\ConsultaCfdiSat\Config::getWsdlLocation()`
  and fix `CfdiUtils\ConsultaCfdiSat\Config::__construct()`.
- Remove file `ConsultaCFDIServiceSAT.svc.xml`.

Tasks related to certificate and private key:

- Depend on `PhpCfdi\Credentials` instead of local implementations.

- Remove `\CfdiUtils\PemPrivateKey\PemPrivateKey::isOpened` to `\CfdiUtils\PemPrivateKey\PemPrivateKey::isOpen`
- Refactor `\CfdiUtils\Certificado\SerialNumber` to be immutable, this change will remove `loadHexadecimal`,
  `loadDecimal` and `loadAscii`.
- Remove `CfdiUtils\Certificado\SerialNumber::baseConvert` method. Should be private or not exists at all.
- Remove static `CfdiUtils\PemPrivateKey\PemPrivateKey::isPEM` method.

Separar a un nuevo proyecto:

- Librerías de trabajo XML.
- Limpieza de CFDI.

## Verificar problemas conocidos

### Descarga de certificados desde <https://rdc.sat.gob.mx/rccf/> por certificados vencidos

Ver: <https://www.phpcfdi.com/sat/problemas-conocidos/descarga-certificados/#problemas-de-caducidad-de-certificados>

*Actualización 2020-10-08*: Este problema se vuelve a presentar.

*Actualización 2020-07-18*: Desde 2019-10-24 este problema parece solucionado.

La descarga de certificados desde `https://rdc.sat.gob.mx/rccf/` falla por un error de configuración
en el servidor web del SAT. Por ello se han puesto instancias especiales de descargadores `PhpDownloader`
que desactivan la verificación de SSL.

Hay que remover esta condición en cuando el sitio del SAT esté correctamente configurado.
Buscar en el código de pruebas el uso de `CfdiUtilsTests\TestCase::newInsecurePhpDownloader(): DownloaderInterface`
y remover el método.

Al correr el siguiente comando correrá 1000 peticiones secuenciales e imprimirá el resultado, si es `0` entonces
la petición se completó, si es `60` es el error de certificado expirado.

```shell
for i in {1..1000}; do curl --verbose "https://rdc.sat.gob.mx/rccf/" > /dev/null 2>&1 ; echo $? ; done | sort | uniq -c
    694 0
    306 60
```

## Documentación del proyecto

Documentar los otros helpers de `Elements`:

- Complemento de comercio exterior
- Impuestos locales
- Pagos

Documentar los validadores:

- Revisar todos los validadores documentados en CFDI
- Pagos


## Prepare for version 3

Version 3 will deprecate some classes and methods, it may be good point of start to migrate the project
to a new namespace `PhpCfdi\CfdiUtils`


## CfdiVersion & TfdVersion

The classes `CfdiUtils\CfdiVersion` and `CfdiUtils\TimbreFiscalDigital\CfdiVersion`
share the same logic and methods. They are detected as code smells, and it would be better
to have a single class to implement the logic and extend that class to provide configuration.


## Status of a Cfdi using the SAT webservice

This is already implemented in `CfdiUtils\ConsultaCfdiSat\WebService` but there are two
ideas than need a solution:

- Find a way to not depend on PHP SOAP but in something that can do async
  request and configure the connection like setting a proxy, maybe depending on guzzle.
- Create a cache of the WSDL page (?)


## Validation rules for Pagos

The validation rules for "Complemento de Recepción de pagos" are included since version 2.6, but
they require more cases of use, and a better understanding of the rules published by SAT.


## Validation rules for ComercioExterior

Create validation rules for "Complemento de Comercio Exterior"


## Ideas not to be implemented

### Add a pretty command line utility to validate cfdi files

This will be implemented on a different project, for testing proposes there is the file `tests/validate.php`


### Implement catalogs published by SAT

This will be implemented on a different project.

