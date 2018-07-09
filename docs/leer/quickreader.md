# Lector rápido de CFDI

El lector rápido ofrece una forma simple y rápida de acceder a los contenidos
de un CFDI.

El `QuickReader` permite poder acceder a los atributos y los elementos sin importar
las mayúsculas y minúsculas y también omite la información del namespace XML.

* Atributos: Se accede a su información usando la notación de arreglo.
* Elementos (primero): Se accede al primer elemento usando propiedades.
* Elementos (varios): Se accede a un conjunto de elementos *ejecutando* el objeto.

`QuickReader` es un objeto de solo lectura, inmutable, cualquier intento de modificación
resultará en una excepción.

Si accedes a una propiedad o elemento que no existe **no habrá ningún error**.

El lector rápido fue creado para casos en donde requieres información rápida y lo que más necesitas es una
navegación ágil dentro de la estructura de un CFDI, por ejemplo, en la exportación de los datos a una estructura
específica de JSON o bien en la exportación de los datos a un template para luego crear un PDF.

El lector rápido es una transformación con pérdida de datos, para empezar se pierde en espacio de nombres XML, así como la diferenciación de mayúsculas y minúsculas. Es por eso que no debes pensar en este objeto como una forma fácil de escribir un XML, fue creado expresamente para lectura. También ten en cuenta que no puede interpretar todo el contenido del XML, solo los elementos (tags) y sus atributos, no puede interpretar nodos de tipo texto o comentarios.


## Obtener el lector rápido

```php
<?php
// crear el objeto CFDI
$cfdi = \CfdiUtils\Cfdi::newFromString(
    file_get_contents('cfdi.xml')
);
// obtener el QuickReader con el método dedicado
$comprobante = $cfdi->getQuickReader();
```


## Acceder a los atributos

Utiliza el objeto como un arreglo usando la notación de corchetes.

Puedes averiguar si un atributo existe usando `isset()`.

```php
<?php
$comprobante = \CfdiUtils\Cfdi::newFromString(file_get_contents('cfdi.xml'))
    ->getQuickReader();

echo $comprobante['version']; // (string) "3.3"
echo $comprobante['Version']; // (string) "3.3"
echo $comprobante['vErSiOn']; // (string) "3.3"
var_dump(isset($comprobante['version'])); // (bool) true


var_dump($comprobante['no-existe']); // (string) ""
var_dump(isset($comprobante['no-existe'])); // (bool) false
```


## Acceder al primer elemento hijo

Puedes acceder al primer elemento hijo (exista o no) usando la notación de propiedades.

Al acceder por medio de propiedades siempre devuelve un objeto de tipo `QuickReader`
aun cuando no exista. Si existe devolverá el primero.

Puedes averiguar si existe al menos un elemento usando `isset()`.

```php
<?php
$comprobante = \CfdiUtils\Cfdi::newFromString(file_get_contents('cfdi.xml'))
    ->getQuickReader();

/*
 * <cfdi:Comprobante ...>
 *     <cfdi:Impuestos TotalImpuestosTrasladados="123.45">...</cfdi:Impuestos>
 * </cfdi:Comprobante>
 */
echo $comprobante->impuestos['totalImpuestosTrasladados']; // (string) "123.45"

/*
 * <cfdi:Comprobante ...>
 *     <cfdi:Complemento>
 *         <tfd:TimbreFiscalDigital FechaTimbrado="2017-03-21T08:18:08" ... />
 *     </cfdi:Complemento>
 * </cfdi:Comprobante>
 */
echo $comprobante->complemento->timbreFiscalDigital['fechatimbrado']; // 2017-03-21T08:18:08

// aun si no existe un elemento no generará una excepción
var_dump(isset($comprobante->foo)); // (bool) false
echo $comprobante->foo->bar->baz->xee['info']; // (string) ""
```


## Acceder a todos los hijos

Entrar a todos los hijos requiere de una sintaxis especial que consiste en llamar al objeto
como una función. Al realizar la llamada lo que se devuelve es un arreglo de objetos `QuickReader` con los hijos.

```php
<?php
$comprobante = \CfdiUtils\Cfdi::newFromString(file_get_contents('cfdi.xml'))
    ->getQuickReader();

// $hijos es un arreglo de QuickReader
$hijos = $comprobante();

foreach($hijos as $hijo) {
    echo $hijo; // Emisor, Receptor, Conceptos, Impuestos, etc...
}
```

La sintaxis de esta operación al principio puede ser un poco complicada, pero una vez que te acostumbras es
bastante entendible.

Por ejemplo, para acceder a todos los hijos del nodo `Conceptos` **no se puede hacer**:
`$comprobante->conceptos()` porque esto significa invocar al método `conceptos` del objeto `$comprobante`.

Hay dos alternativas para poder hacer esta llamada:

* Asignar a una variable y luego hacer la invocación:

    `$conceptos = $comprobante->conceptos; $conceptos();`

* Usar paréntesis para separar la propiedad, y luego invocarla:

    `($comprobante->conceptos)()`

Si uso dependerá de tu preferencia, aquí dos formas que ejemplifican lo mismo,
primero una variable para acceder a los hijos (conceptos), luego se hace una invocación de la propiedad (traslado).

```php
<?php
$comprobante = \CfdiUtils\Cfdi::newFromString(file_get_contents('cfdi.xml'))
    ->getQuickReader();

// usando asignación de variable
$conceptos = $comprobante->conceptos;
foreach($conceptos() as $concepto) {
    // usando propiedad
    foreach(($concepto->impuestos->traslados)() as $traslado) {
        echo $traslado['impuesto'];
    }
}
```


## Acceder a hijos con un mismo nombre

Si requieres todos los nodos hijos a los que les corresponda un mismo nombre
simplemente pasa el nombre como argumento de la ejecución del objeto.

```php
<?php
$comprobante = \CfdiUtils\Cfdi::newFromString(file_get_contents('cfdi.xml'))
    ->getQuickReader();

// $hijos es un arreglo que contiene solo aquellos hijos llamados "concepto"
$hijos = ($comprobante->conceptos)('concepto');
```


## Nombre del nodo

Difícilmente lo utilizarás, pero si necesitas saber el nombre del nodo entonces puedes
convertir el nodo a cadena de caracteres y te devolverá su nombre.

```php
<?php
$comprobante = \CfdiUtils\Cfdi::newFromString(file_get_contents('cfdi.xml'))
    ->getQuickReader();

echo (string) $comprobante; // (string) "Comprobante"
```
