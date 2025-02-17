# Actualizaciones a la versión 3

Esta versión mayor es creada primordialmente para asegurar la compatibilidad con PHP 8.4.
Sin embargo, esta compatibilidad requiere que cambien firmas de diferentes objetos, por lo que,
en respeto a las reglas de versionado semántico es necesario actualizar la versión mayor.

También se ha aprovechado la oportunidad para quitar código deprecado y actualizar dependencias.

## Cambios en métodos de llaves privadas

Estos cambios aplican para la clase `CfdiUtils\PemPrivateKey\PemPrivateKey`.

Se elimina el método `isOpened(): bool`, use `isOpen(): bool`.

Se elimina el método `isPEM(string $text): bool`, puede usar el siguiente código:

```php
$openSSL = new \CfdiUtils\OpenSSL\OpenSSL();
return $openSSL->readPemContents($text)->hasPrivateKey();
```

## Cambios en la consulta del estado de CFDI

Se recomienda usar el proyecto [`phpcfdi/sat-estado-cfdi`](https://github.com/phpcfdi/sat-estado-cfdi) en lugar de este componente.

- Se elimina la constante `CfdiUtils\ConsultaCfdiSat\Config::DEFAULT_WSDL_URL`, use `DEFAULT_SERVICE_URL` concatenado con `?singleWsdl`.
- En el constructor de la clase `CfdiUtils\ConsultaCfdiSat\Config` se elimina el parámetro `$wsdlLocation`.
- En el constructor de la clase `CfdiUtils\ConsultaCfdiSat\StatusResponse` ninguno de los parámetros son opcionales.
- Se elimina el método `CfdiUtils\ConsultaCfdiSat\Config::getWsdlUrl()`, use `getServiceUrl()`.
- Se elimina el método `CfdiUtils\ConsultaCfdiSat\Config::getWsdlLocation()`.
- Se elimina el método `CfdiUtils\ConsultaCfdiSat\Config::getLocalWsdlLocation()`.
- Se agrega el método `CfdiUtils\ConsultaCfdiSat\WebService::requestExpression(string $expression)` para poder consultar directamente
  una expresión en lugar de tener que pasar un objeto `RequestParameters` al método `request()`.

## Cambios en la generación de la cadena de origen

- Se eliminó la clase `CfdiUtils\CadenaOrigen\CadenaOrigenBuilder` en favor de `CfdiUtils\CadenaOrigen\DOMBuilder`.
- Se eliminó la clase `CfdiUtils\CadenaOrigen\CadenaOrigenLocations`, use `CfdiUtils\CadenaOrigen\CfdiDefaultLocations`.
- Se eliminó la clase `CfdiUtils\CadenaOrigen\DefaultLocations`, en favor de `CfdiUtils\CadenaOrigen\CfdiDefaultLocations`.

## `Crp20277Fixer`

Se removió `CfdiUtils\Utils\Crp20277Fixer`, la clase era inútil y no debía utilizarse.

## Otros elementos removidos

- Se elimina la constante `CfdiUtils\Retenciones\Retenciones::RET_NAMESPACE`, use `RET_NAMESPACE['1.0']`.
- Se removió el método `CfdiUtils\Certificado\SerialNumber::baseConvert`.
