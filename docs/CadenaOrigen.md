# `\CfdiUtils\CadenaOrigen`

Esta clase genera la cadena de origen que sirve para generar o verificar el sello de un CFDI.

El método que ocupa esta clase (recomendado por el SAT) es el de Transformaciones XSL
"Extensible Stylesheet Language Transformations" XSLT.

La clase contiene un método `build(string $cfdiContent, string $xsltLocation = ''): string`.

- `$cfdiContent` cadena de texto que contiene el XML del CFDI
- `$xsltLocation` cadena de texto que contiene la url o la ruta del archivo de transformación.
- retorna el resultado de la transformación.

Si el parámetro `$xsltLocation` (la ubicación del archivo xslt) está vacío entonces el método
intenta obtener la versión del CFDI y usa el método `getXsltLocation($version)` para obtener
la ubicación.

Los métodos `getXsltLocation(string $version)` y `setXsltLocation(string $version, string $location)`
permiten configurar las ubicaciones que serán solicitadas en el método `build`.

Por lo tanto, si no quieres usar la url del SAT, porque simplemente a veces no está disponible
o bien es muy lento (aproximadamente 10 segundos) entonces puedes cambiar la ubicación a una ruta local.

Incluso, puedes sacar provecho de otra librería: `eclipxe/xmlresourceretriever` que descarga el xslt
inicial y sus dependencias y las almacena en una carpeta, ve el siguiente ejemplo:

```php
<?php
/* obtener la copia local de el transformador para CFDI 3.3 */
use XmlResourceRetriever\XsltRetriever;
$retriever = new XsltRetriever($this->utilAsset('/tmp'));
$remote = 'http://www.sat.gob.mx/sitio_internet/cfd/3/cadenaoriginal_3_3/cadenaoriginal_3_3.xslt';
$local = $retriever->buildPath($remote);
if (! file_exists($local)) {
    $retriever->retrieve($remote);
}

/* modificar la url y poner la ruta local */
$builder = new \CfdiUtils\CadenaOrigen();
$builder->setXsltLocation('3.3', $local);

/* construir la cadena de origen */
$cfdContent = "<cfdi:Comprobante ...";
$cadena = $builder->build($cfdContent);
```

