# Lista de tareas pendientes e ideas

- Incrementar la cobertura de PHPStan al nivel máximo.

## Documentar Carta Porte 1.0

Se agregaron los elementos de ayuda a `CfdiUtils\Elements\CartaPorte10` pero no se agregó la documentación,
es necesario agregarla tomando como ejemplo la documentación del *Complemento de Nómina 1.2b*.

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

