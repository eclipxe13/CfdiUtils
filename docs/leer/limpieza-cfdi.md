# Limpieza de un CFDI

Frecuentemente se reciben archivos de CFDI que fueron firmados y son válidos pero contienen errores.

Sucede que, después de que el SAT (o el PAC en nombre del SAT) ha firmado un CFDI estos suelen ser alterados
con información que no pertenece a la cadena de origen. Lamentablemente esto es permitido por el SAT.

Un caso común de alteración es agregar más nodos al nodo `cfdi:Addenda`, como la información contenida
no pertenece a la cadena de origen entonces no se considera que el documento ha sido alterado.
Y hasta cierto punto esto no está mal. El problema viene cuando la información introducida contiene errores de XML.

Algunos de estos errores son:

- El nodo `cfdi:Addenda` contiene elementos hijos que no tienen asociado un namespace ni un XSD
- Existen espacios de nombres XML definidos que no están en uso
- Existen espacios de nombres XML definidos que no pertenecen al SAT y no está disponible su archivo XSD
- La especificación XSD no puede ser obtenida
- Los datos en el nodo `cfdi:Addenda` no cumplen con la especificación XSD

Estos errores comunes terminan en un error de validación.

## Objeto `Cleaner`

Para evitar estos errores se puede usar el objeto `CfdiUtils\Cleaner\Cleaner`.
Este objeto requiere una cadena de texto con XML válido. Y limpia el XML siguiendo estos pasos:

1. Remueve el nodo `cfdi:Addenda`.
1. Remueve todos los nodos que no tengan relación con el SAT (los que no contengan `http://www.sat.gob.mx/`).
1. Remueve todos los pares de espacio de nombre y archivo xsd de los `xsi:schemaLocation` que no tengan relación con el SAT.
1. Remueve todos los espacios de nombres listados que no están en uso.

La forma rápida de usar el limpiador es usando el método estático
`CfdiUtils\Cleaner\Cleaner::staticClean(string $content): string`
que recibe el XML sucio y devuelve el XML limpio.

También se puede instanciar un objeto de la clase `CfdiUtils\Cleaner\Cleaner` y usar estos métodos:

- `load(string $content)`: Carga un contenido XML "sucio"
- `clean()`: Realiza la limpieza
- `retrieveXml()`: Obtiene el contenido XML "limpio"
