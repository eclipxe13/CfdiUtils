# Actualizaciones a la versión 3

Esta versión mayor es creada primordialmente para asegurar la compatibilidad con PHP 8.4.
Sin embargo, esta compatibilidad requiere que cambien firmas de diferentes objetos, por lo que,
en respeto a las reglas de versionado semántico es necesario actualizar la versión mayor.

También se ha aprovechado la oportunidad para quitar código deprecado y actualizar dependencias.

## Adiciones a `QuickReader`

La utilería `QuickReader` puede ser complicada por sus llamadas mágicas, por ello, se han agregado nuevos métodos.

### `getChildren(string $name = ''): QuickReader[]`

El método `getChildren()` devuelve siempre un arreglo con los elementos cuyo nombre coincida con el argumento.
Si no se especifica un argumento o es una cadena vacía, entonces devuelve un arreglo con todos los hijos.

### `getAttributes(): array<string, string>`

El método `getAttributes()` devuelve siempre un arreglo con el nombre los atributos,
donde la llave es el nombre del atributo y el valor es el valor del atributo.

## Actualización de `XmlResourceRetriever`

La librería [`eclipxe/xmlresourceretriever`](https://github.com/eclipxe13/xmlresourceretriever) se ha actualizado
de la versión 1.x a la versión 2.x. Con esto hay un cambio en el espacio de nombres.
Si tiene algún problema, es probable que deba cambiar `XmlResourceRetriever\` por `Eclipxe\XmlResourceRetriever`.

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
- Se elimina el archivo de soporte `ConsultaCFDIServiceSAT.svc.xml`.

## Cambios en la generación de la cadena de origen

- Se eliminó la clase `CfdiUtils\CadenaOrigen\CadenaOrigenBuilder` en favor de `CfdiUtils\CadenaOrigen\DOMBuilder`.
- Se eliminó la clase `CfdiUtils\CadenaOrigen\CadenaOrigenLocations`, use `CfdiUtils\CadenaOrigen\CfdiDefaultLocations`.
- Se eliminó la clase `CfdiUtils\CadenaOrigen\DefaultLocations`, en favor de `CfdiUtils\CadenaOrigen\CfdiDefaultLocations`.

## Cambios en CFDI 3.3

Estos cambios no deben ser significativos dado que ya no se deben crear CFDI versión 3.3.

En el objeto de ayuda para crear un CFDI 3.3 el método `getCfdiRelacionados()` admitía parámetros,
pero no es el comportamiento esperado, por lo que ya no es correcto llamarlo con parámetros.

Se estandariza el método `multiCfdiRelacionado()`.

## `Crp20277Fixer`

Se removió `CfdiUtils\Utils\Crp20277Fixer`, la clase era inútil y no debía utilizarse.

## Cambios en `NodeInterface`

Solo haga caso a este cambio si extendió o implementó la interface `CfdiUtils\Nodes\NodeInterface`.

- Se eliminó la interface `CfdiUtils\Nodes\NodeInterface\NodeHasValueInterface`, sus métodos se movieron a `NodeInterface`.
- Se agregó el método `exists(string $attribute): bool` que determina si un atributo existe.
- Se agregaron los métodos `value()` y `setValue()` a la clase `NodeInterface`.
- El método `searchNode` cambió el valor de retorno de *no definido* a `null|self`.

## Otros elementos removidos

- Se elimina el método `CfdiUtils\Cleaner\Cleaner::removeIncompleteSchemaLocation()`.
- Se elimina la constante `CfdiUtils\Cfdi::CFDI_NAMESPACE`, use `CFDI_SPECS['3.3']`.
- Se elimina la constante `CfdiUtils\Retenciones\Retenciones::RET_NAMESPACE`, use `RET_SPECS['1.0']`.
- Se removió el método `CfdiUtils\Certificado\SerialNumber::baseConvert`.
- Se elimina la clase `CfdiUtils\Validate\Cfdi33\Xml\XmlFollowSchema`, use `CfdiUtils\Validate\Xml\XmlFollowSchema`.
- Se elimina la clase `CfdiUtils\Elements\Cfdi33\Helpers\SumasConceptosWriter`, use `CfdiUtils\SumasConceptos\SumasConceptosWriter`.
- Se elimina la clase `CfdiUtils\Elements\Cfdi40\Helpers\SumasConceptosWriter`, use `CfdiUtils\SumasConceptos\SumasConceptosWriter`.
- Se elimina la interface `CfdiUtils\Nodes\NodeHasValueInterface`, sus métodos ahora están dentro de `NodeInterface`.

## Errores con `genkgo/xsl`

La librería `genkgo/xsl` a la fecha 2025-02-18 tiene problemas de compatibilidad con PHP 8.4.
Es probable que estos problemas se corrijan en un futuro, pero ningún cambio es requerido en esta librería.
