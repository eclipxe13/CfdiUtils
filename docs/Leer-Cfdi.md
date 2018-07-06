# Leer un comprobante fiscal digital

El problema de leer un CFDI es que la información entre versiones 3.3, 3.2
y previas no es compatible. Por ello es necesario necesario primero
averiguar la versión del archivo que deseamos interpretar.

Como recomendación, si estás implementando esta librería para leer CFDI
-por ejemplo para procesar los CFDI recibidos- decide qué información deseas recopilar y qué hacer en caso de que no exista, después crea clases específicas que trabajen con los diferentes tipos de versiones y que entreguen un resultado homologado con el que sí puedas trabajar.


## Procesar un CFDI

Esta librería almacena la información de un CFDI en una estructura interna llamada
[`Nodes`](Nodes). Por lo que, al leer un CFDI lo que en realidad sucede es que se
convierte el contenido XML a esta estructura interna de nodos.


### El objeto `CfdiUtils\Cfdi`

Este es un ejemplo básico de lectura de un contenido XML a un objeto de
tipo `CfdiUtils\Cfdi`. Por lo general se utiliza el método estático
`CfdiUtils\Cfdi::newFromString`.

La clase ofrece cuatro principales *getters* para trabajo, siendo el más importante
el método `CfdiUtils\Cfdi::getNode` que devuelve la instancia del objeto de tipo
`NodeInterface` del elemento principal `<cfdi:Comprobante>`.

```php
<?php
$xmlContents = '<cfdi:Comprobante Version="3.3">...</cfdi:Comprobante>';
$cfdi = CfdiUtils\Cfdi::newFromString($xmlContents);
$cfdi->getVersion(); // (string) 3.3
$cfdi->getDocument(); // clon del objeto DOMDocument
$cfdi->getSource(); // (string) <cfdi:Comprobante...
$comprobante = $cfdi->getNode(); // Nodo de trabajo del nodo cfdi:Comprobante
```


#### Uso de `CfdiUtils\Cfdi::newFromString`

El método estático `CfdiUtils\Cfdi::newFromString` verifica que el contenido XML
no esté vacío y no contenga errores (se pueda crear un `DOMDocument` a partir
de este contenido).
Posteriormente invoca la creación de un objeto de tipo `CfdiUtils\Cfdi` pasando 
el objeto `DOMDocument` como parámetro.


#### Constructor de `CfdiUtils\Cfdi`

Al crear un objeto de tipo `CfdiUtils\Cfdi` se verifican las siguientes reglas
del objeto `DOMDocument`:

1. el documento implementa el espacio de nombres del cfdi `http://www.sat.gob.mx/cfd/3`
1. con el prefijo `cfdi`
1. en el elemento raíz `<cfdi:Comprobante>`

No realiza ninguna validación. La validación de un CFDI está fuera de los límites de esta clase.


#### Ejemplos básicos de uso de `NodeInterface`

Para obtener el atributo `Serie` de un complemento, esté o no definido el atributo
originalmente, si no está definido entonces devolverá una cadena de caracteres vacía.

```php
<?php
/** @var CfdiUtils\Cfdi $cfdi */
$complemento = $cfdi->getNode();
echo $complemento['Serie']; 
```

Para verificar si está especificado el atributo `MetodoPago`

```php
<?php
/** @var CfdiUtils\Cfdi $cfdi */
$complemento = $cfdi->getNode();
if (isset($complemento['MetodoPago']) {
    // ...
}
```

Para recorrer todos los nodos `cfdi:Concepto` (que está dentro de `cfdi:Conceptos`)
se puede utilizar el método `CfdiUtils\Nodes\NodeInterface::searchNodes` que devuelve
una colección de nodos iterable (que se puede utilizar dentro de un `foreach`).

```php
<?php
/** @var CfdiUtils\Cfdi $cfdi */
$complemento = $cfdi->getNode();
$conceptos = $complemento->searchNodes('cfdi:Conceptos', 'cfdi:Concepto');
foreach ($conceptos as $concepto) {
    echo $concepto['Unidad'];
}
```

Para obtener la primer ocurrencia de un nodo de determinado nombre se puede usar
el método `CfdiUtils\Nodes\NodeInterface::searchNode`. Este método devolverá el nodo
si fue encontrado o devolverá `null` si no se encontró.

No confundir con el método anterior que devuelve una colección de nodos.

```php
<?php
/** @var CfdiUtils\Cfdi $cfdi */
$complemento = $cfdi->getNode();
$tfd = $complemento->searchNodes('cfdi:Complemento', 'tfd:TimbreFiscalDigital');
if (null === $tfd) {
    echo 'No existe el timbre fiscal digital';
} else {
    echo 'UUID: ', $tfd['UUID'], PHP_EOL;
}
```

Recuerde consultar la entrada completa relacionada con la [Estructura de datos `Nodes`](Nodes).


## Obteniendo la versión de un CFDI sin la clase `CfdiUtils\Cfdi`

Obtener la versión de un CFDI es sencillo con la clase `CfdiUtils\CfdiVersion`.

El método que usarás para obtener la versión depende de la información que ya
tengas instanciada:
- `getFromXmlString()`: Cuando ya tienes el contenido del XML en una variable
- `getFromNode()`: Cuando tienes el nodo principal en un objeto de tipo `CfdiUtils\Nodes\NodeInterface`
- `getFromDOMDocument()` y `getFromDOMElement()`: Cuando tienes el contenido XML
  instanciado en un objeto de tipo DOM.

El resultado de estos métodos será un string con el número de versión y vacío en
caso de no encontrarse un número de versión compatible.

```php
<?php
$xmlContents = file_get_contents($cfdiFile);
$cfdiVersion = new CfdiUtils\CfdiVersion();
$version = $cfdiVersion->getFromXmlString($xmlContents);
```

Nota: la clase `CfdiUtils\Cfdi` ya realiza este proceso por lo que no es recomendado
duplicar el trabajo de averiguar la versión.


## Limpieza de CFDI

Es frecuente que los archivos CFDI contengan errores. Para entender más el tema
vea el artículo de [Limpieza de un CFDI 3.2 y 3.3](Limpieza-de-Cfdi).

Si está leyendo un CFDI recibido este es un ejemplo de cómo limpiar y crear el objeto CFDI:

```php
<?php
// obtener el contenido del archivo CFDI
$cfdiFile = '/cfdi/recibidos/2018/FEI-456823.xml';
$xmlContents = file_get_contents($cfdiFile);

// limpiar el cfdi
$xmlContents = CfdiUtils\Cleaner\Cleaner::staticClean($xmlContents);

// crear la instancia del objeto CFDI
$cfdi = CfdiUtils\Cfdi::newFromString($xmlContents);
```


## Lector rápido

Ve la documentación del lector rápido, si tu intensión no es editar el documento
y confías en el contenido (no te importa si está bien escrito el XML) entonces puedes
usar el [lector rápido](QuickReader).
