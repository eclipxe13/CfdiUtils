# `\CfdiUtils\Cfdi`

Clase para minimizar el esfuerzo de trabajar con un cfdi.

La clase tiene por constructor un objeto de tipo `\DOMDocument`
y ofrece el método estático `newFromString(string $content)`
que devuelve un objeto del mismo tipo que la clase.
Entonces se puede crear este objeto a partir de un objeto `\DOMDocument` o de su contenido de texto.

**Esta clase no valida los contenidos de un cfdi**. Para crear el objeto solo requiere de dos reglas:
- Debe implementar el namespace `http://www.sat.gob.mx/cfd/3` con un prefijo. Por ejemplo `cfdi`.
- El nodo principal debe ser Comprobante con el mismo prefijo. Por ejemplo `<cfdi:Comprobante ...>`.

## Acerca de la versión

En cuanto se crea el objeto se trata de obtener su versión (muy útil para trabajar con versiones 3.3 y 3.2).
Note la diferencia en el uso de mayúsculas y minúsculas de el atributo.
- Para considerarse un comprobante 3.2 debe contener:  `<cfdi:Comprobante version="3.2" ...>`
- Para considerarse un comprobante 3.3 debe contener:  `<cfdi:Comprobante Version="3.3" ...>`

Si el atributo no existe, está vacío o tiene cualquier otra información entonces el objeto devuelve
una cadena de caracteres vacía en lugar de `3.2` o `3.3`.

## Extensibilidad

De esta clase se pueden desprender otras que trabajen directamente con los campos de un cfdi,
como por ejemplo # `\CfdiUtils\CfdiCertificado`.
