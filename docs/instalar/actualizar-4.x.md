# Actualizaciones a la versión 3

Esta versión mayor es creada primordialmente para asegurar la compatibilidad con PHP 8.4.
Sin embargo, esta compatibilidad requiere que cambien firmas de diferentes objetos, por lo que,
en respeto a las reglas de versionado semántico es necesario actualizar la versión mayor.

También se ha aprovechado la oportunidad para quitar código deprecado y actualizar dependencias.

## Cambios en la generación de la cadena de origen

- Se eliminó la clase `CfdiUtils\CadenaOrigen\CadenaOrigenBuilder` en favor de `CfdiUtils\CadenaOrigen\DOMBuilder`.
- Se eliminó la clase `CfdiUtils\CadenaOrigen\CadenaOrigenLocations`, use `CfdiUtils\CadenaOrigen\CfdiDefaultLocations`.
- Se eliminó la clase `CfdiUtils\CadenaOrigen\DefaultLocations`, en favor de `CfdiUtils\CadenaOrigen\CfdiDefaultLocations`.

## `Crp20277Fixer`

Se removió `CfdiUtils\Utils\Crp20277Fixer`, la clase era inútil y no debía utilizarse.
