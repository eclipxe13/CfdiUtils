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
- Múltiples nodos `cfdi:Complemento`, en el Anexo 20 está especificado que solo puede haber uno pero
  en el archivo XSD está especificado que pueden haber muchos.

Estos errores comunes terminan en un error de validación.

## Objeto `Cleaner`

Para evitar estos errores se puede usar el objeto `CfdiUtils\Cleaner\Cleaner`.
Este objeto requiere una cadena de texto con XML válido. Y limpia el XML siguiendo estos pasos:

1. Cambiar la defición incorrecta en algunos CFDI del SAT `xmlns:schemaLocation` por `xsi:schemaLocation`.
1. Remover la definición de CFDI 3 si no tiene prefijo `xmlns="http://www.sat.gob.mx/cfd/3"` siempre que la definición
   con prefijo `xmlns:cfdi="http://www.sat.gob.mx/cfd/3"` sí esté presente.
1. Remueve el nodo `cfdi:Addenda`.
1. Remueve dentro de las locaciones de espacios de nombre `xsi:schemaLocation` los namespaces que no tengan
   a continuación una uri que termine en `.xsd`.
1. Remueve todos los nodos que no tengan relación con el SAT (los que no contengan `http://www.sat.gob.mx/`).
1. Remueve todos los pares de espacio de nombre y archivo xsd de los `xsi:schemaLocation` que no tengan relación con el SAT.
1. Remueve todos los espacios de nombres listados que no están en uso.
1. Colapsa los nodos `cfdi:Complemento` en uno solo, respetando el mismo orden de aparición para que se genere
   exactamente la misma cadena de origen.

Las primeras dos formas no trabajan con el CFDI como XML, lo trabajan como una cadena de texto.

La forma rápida de usar el limpiador es usando el método estático
`CfdiUtils\Cleaner\Cleaner::staticClean(string $content): string`
que recibe el XML sucio y devuelve el XML limpio.

```php
<?php
$possibleDirty = '... el xml del cfdi ...';
$cleanContent = CfdiUtils\Cleaner\Cleaner::staticClean($possibleDirty);
```

También se puede instanciar un objeto de la clase `CfdiUtils\Cleaner\Cleaner` y usar estos métodos:

- `load(string $content)`: Carga un contenido XML "sucio"
- `clean()`: Realiza la limpieza
- `retrieveXml()`: Obtiene el contenido XML "limpio"

Si deseas implementar tu propio orden, hacer o agregar nuevos limpiadores puedes extender la clase o sobrescribir
el método `clean` o bien llamar a cada uno de los pasos de limpieza por tu propia cuenta.

De querer saltar las dos limpiezas previas a la carga del XML, es necesario construir el objeto `Cleaner`
pasando un objeto de tipo `BeforeLoadCleanerInterface` que no haga ninguna limpieza, por ejemplo:

```php
<?php
$content = '... el xml del cfdi ...';

// objeto que no hace limpieza implementando el patrón de diseño NULL
$nullBeforeLoadCleaner = new class () implements CfdiUtils\Cleaner\BeforeLoad\BeforeLoadCleanerInterface {
    public function clean(string $content): string {
        return $content;
    }
};

$cleaner = new CfdiUtils\Cleaner\Cleaner($content, $nullBeforeLoadCleaner);
$cleaner->clean();
$content = $cleaner->retrieveXml();
```
