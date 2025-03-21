# Creación de CFDI 4.0

Para crear un CFDI versión 4.0 se ofrece el objeto `CfdiUtils\CfdiCreator40`.

Este objeto trabaja directamente con la estructura `CfdiUtils\Elements\Cfdi40\Comprobante`
para facilitar la manipulación de la estructura y los datos, y contiene métodos que ayudan
a establecer el certificado, generar el sello, generar o almacenar el XML, y validar la estructura recién creada.

Esta clase es una especie de pegamento de todas las pequeñas utilerías y estructuras de datos.

## Métodos de ayuda

- `comprobante(): Comprobante`: Obtiene el nodo raíz `Comprobante`. Todos los métodos utilizan este objeto.

- `putCertificado(Certificado $certificado, bool $putEmisorRfcNombre = true)`: Establece el valor de los atributos
  `NoCertificado` y `Certificado`, y si `$putEmisorRfcNombre` es verdadero entonces también establece el valor
  de `Rfc` y `Nombre` en el nodo `Emisor`.
- En el caso del `Emisor`, se quita el prefijo relacionado al régimen de capital.

- `asXml(): string`: Genera y devuelve el contenido XML de la exportación del nodo `Comprobante`.

- `saveXml(string $filename): bool`: Genera y almacena el contenido XML.

- `buildCadenaDeOrigen(): string`: Construye la cadena de origen siempre que exista un resolvedor de recursos XML.

- `buildSumasConceptos(int $precision = 2): SumasConceptos`: Genera un objeto de tipo `SumasConceptos`
  según los datos de los `Conceptos`.

- `addSumasConceptos(SumasConceptos $sumasConceptos = null, int $precision = 2)`: Establece los valores de `$sumasConceptos`
  en el comprobante, si no se pasó el objeto entonces lo fabrica con `buildSumasConceptos()`. Las sumas en cuestión son
  los valores del comprobante `SubTotal`, `Total` y `Descuento`, nodo de impuestos del comprobante, y también
  los totales `TotaldeRetenciones` y `TotaldeTraslados` del complemento de impuestos locales.

- `addSello(string $key, string $passPhrase = '')`: Realiza el procedimiento de firma con la llave primaria y
  almacena el valor de dicha llave en base64 en el atributo `Sello`.
  Si el certificado existe como un objeto `Certificado` entonces este método también verifica que
  la llave primaria pertenece al certificado y genera una excepción si no es así.

- `validate(): Asserts`: Crea un validador que verifica la estructura XML contra su archivo XSD
  y realiza validaciones adicionales.
  Consulta la [documentación de validaciones](../validar/validacion-cfdi.md) para más información.

- `moveSatDefinitionsToComprobante(): void`: Mueve las declaraciones de espacios de nombres `xmlns:*`
  y las declaraciones de ubicación de esquemas `xsi:schemaLocation` al nodo raíz.


## Pasos básicos de creación de un CFDI

No hay una sola forma de hacer las cosas, pero la receta de creación sería algo como:

```php
<?php
$certificado = new \CfdiUtils\Certificado\Certificado('... ubicación archivo CER');
$comprobanteAtributos = [
    'Serie' => 'XXX',
    'Folio' => '0000123456',
    // y otros atributos más...
];
$creator = new \CfdiUtils\CfdiCreator40($comprobanteAtributos, $certificado);

$comprobante = $creator->comprobante();

// No agrego (aunque puedo) el Rfc y Nombre porque uso los que están establecidos en el certificado
$comprobante->addEmisor([
    'RegimenFiscal' => '601', // General de Ley Personas Morales
]);

$comprobante->addReceptor([/* Atributos del receptor */]);

$comprobante->addConcepto([
    /* Atributos del concepto */
])->addTraslado([
    /* Atributos del impuesto trasladado */
]);

// método de ayuda para establecer las sumas del comprobante e impuestos
// con base en la suma de los conceptos y la agrupación de sus impuestos
$creator->addSumasConceptos(null, 2);

// método de ayuda para generar el sello (obtener la cadena de origen y firmar con la llave privada)
$creator->addSello('file:// ... ruta para mi archivo key convertido a PEM ...', 'contraseña de la llave');

// método de ayuda para mover las declaraciones de espacios de nombre al nodo raíz
$creator->moveSatDefinitionsToComprobante();

// método de ayuda para validar usando las validaciones estándar de creación de la librería
$asserts = $creator->validate();
if ($asserts->hasErrors()) { // contiene si hay o no errores
    print_r(['errors' => $asserts->errors()]);
    return;
}

// método de ayuda para generar el xml y guardar los contenidos en un archivo
$creator->saveXml('... lugar para almacenar el cfdi ...');

// método de ayuda para generar el xml y retornarlo como un string
$creator->asXml();
```

En el ejemplo anterior en la línea que dice `$comprobante = $creator->comprobante();`
se está obteniendo el **elemento** `CfdiUtils\Elements\Cfdi40\Comprobante`.

Todos los [elementos](../componentes/elements.md) son una especialización de los [nodos](../componentes/nodes.md).
A diferencia de los nodos, los elementos contienen métodos de ayuda que permiten entender los hijos que manejan,
por ejemplo `CfdiUtils\Elements\Cfdi40\Comprobante` contiene un método llamado `addReceptor()`
con el que se puede insertar en el lugar correcto el nodo "Receptor" incluyendo un arreglo de atributos.

## Acerca de las definiciones de espacios de nombre

A partir de la versión `2.12.0` se agregó el método `moveSatDefinitionsToComprobante()` que ayuda a mover las
definiciones de espacios de nombres al nodo principal `cfdi:Comprobante`.

Si no se llama a este método, las definiciones de espacios de nombres quedarán en el nodo que las utiliza,
por ejemplo:

```xml
<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/4"
    xsi:schemaLocation="http://www.sat.gob.mx/cfd/4 http://www.sat.gob.mx/sitio_internet/cfd/4/cfdv40.xsd">
    <!-- ... nodos del comprobante ... -->
    <cfdi:Complemento>
        <!-- ... otros complementos ... -->
        <leyendasFisc:LeyendasFiscales version="1.0" xmlns:leyendasFisc="http://www.sat.gob.mx/leyendasFiscales"
            xsi:schemaLocation="http://www.sat.gob.mx/leyendasFiscales http://www.sat.gob.mx/sitio_internet/cfd/leyendasFiscales/leyendasFisc.xsd">
            <leyendasFisc:Leyenda disposicionFiscal="RESDERAUTH" norma = "Artículo 2. Fracción IV." textoLeyenda = "El software desarrollado se entrega con licencia MIT" />
        </leyendasFisc:LeyendasFiscales>
    </cfdi:Complemento>
</cfdi:Comprobante>
```

Y si aplica este método las definiciones cambiarán de lugar, quedando como:

```xml
<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/4" xmlns:leyendasFisc="http://www.sat.gob.mx/leyendasFiscales"
    xsi:schemaLocation="http://www.sat.gob.mx/cfd/4 http://www.sat.gob.mx/sitio_internet/cfd/4/cfdv40.xsd http://www.sat.gob.mx/leyendasFiscales http://www.sat.gob.mx/sitio_internet/cfd/leyendasFiscales/leyendasFisc.xsd">
    <!-- ... nodos del comprobante ... -->
    <cfdi:Complemento>
        <!-- ... otros complementos ... -->
        <leyendasFisc:LeyendasFiscales version="1.0">
            <leyendasFisc:Leyenda disposicionFiscal="RESDERAUTH" norma = "Artículo 2. Fracción IV." textoLeyenda = "El software desarrollado se entrega con licencia MIT" />
        </leyendasFisc:LeyendasFiscales>
    </cfdi:Complemento>
</cfdi:Comprobante>
```

En realidad, esto no es una regla importarte e incluso se podría decir que sale de la práctica común de XML.
Sin embargo, en la documentación técnica del SAT lo documenta como *obligatorio*.

Si no creaste tus CFDI con esta estructura malamente obligada por el SAT, no te preocupes, en caso de ser
necesario podrías hasta modificar tus CFDI anteriores (aun cuando tengan sello) porque la ubicación de las
definiciones de los espacios de nombres no participan en la formación de la cadena de origen.


## Formación del texto de los códigos QR

La formación del texto que se incluye en los códigos QR tiene reglas específicas
y puede utilizarse el objeto `\CfdiUtils\ConsultaCfdiSat\RequestParameters`
para obtener el texto contenido en el código QR.

Este es un ejemplo para obtener la URL directamente de un contenido XML.

```php
$xmlContents = '<cfdi:Comprobante Version="4.0">...</cfdi:Comprobante>';
$cfdi = \CfdiUtils\Cfdi::newFromString($xmlContents);
$parameters = \CfdiUtils\ConsultaCfdiSat\RequestParameters::createFromCfdi($cfdi);

echo $parameters->expression(); // https://verificacfdi.facturaelectronica.sat.gob.mx/...
```


## Orden de los nodos de un CFDI

A pesar de tratarse de una estructura XML el SAT por las reglas impuestas en los
archivos XSD ha puesto reglas de orden de aparición de nodos.

Por lo anterior **esta estructura presentará error** porque el nodo `Receptor`
debe ir después del nodo `Emisor`:

```xml
<cfdi:Comprobante>
    <cfdi:Receptor/>
    <cfdi:Emisor/>
</cfdi:Comprobante>
```

Cuando se está usando el espacio de nombres `CfdiUtils\Elements` las estructuras conocen el
orden en el que deben existir los nodos, por lo que no es necesario preocuparse por el orden de aparición.
Esta mejora fue introducida en la versión 2.4.0.

Si se está utilizando `CfdiUtils\Nodes` de forma independiente a `CfdiUtils\Elements` entonces será necesario
establecer el orden de los nodos con el método `CfdiUtils\Nodes\Nodes::setOrder(array $order)`.
O simplemente insertar los nodos en el orden correcto.


## Resolvedor de recursos XML

Los archivos XSD necesarios para validar la estructura XML de un CFDI y
los archivos XSLT necesarios para generar la cadena de origen
son almacenados localmente y reutilizados cada vez que se require.

Para establecer dicha configuración diferente a la predeterminada establezca el objeto `XmlResolver`
usando el método `CfdiCreator40::setXmlResolver(XmlResolver $xmlResolver = null)`.

Si establece el valor a nulo (`CfdiCreator40::hasXmlResolver()` es `false`) entonces no se podrá
crear la cadena de origen (necesario para obtener la ruta de los archivos XSLT) y tampoco se podrá abastecer
a los objetos de validación que requieran de un resolvedor con el objeto apropiado resultando en varias revisiones
sin ejecutar.

Si lo que desea es no almacenar localmente los recursos entonces lo que debe hacer es establecer
una cadena de caracteres vacía mediante el método `XmlResolver::setLocalPath`.


## Migrar de CFDI 3.3 a CFDI 4.0

Por lo relacionado con esta librería, solo necesitarás cambiar lo que diga `CfdiUtils\Elements\Cfdi33`
por `CfdiUtils\Elements\Cfdi40`. Así como usar el objeto `CfdiUtils\CfdiCreator40`.

La versión 2.19.0 o mayor fue dotada con todos los elementos necesarios para hacer una migración
de CFDI 3.3 a CFDI 4.0 lo menos dolorosa posible.

Ten en cuenta que tendrás que modificar la información que pasas a los elementos porque se han agregado
varios de ellos, sin embargo, la *compatibilidad* ofrecida permite que te concentres en solo un problema:
*Aplicar los cambios del SAT a CFDI 4.0*.


## Uso de `phpcfdi/credentials`

Esta librería implementa objetos para trabajar con Certificados y Llaves primarias.
Sin embargo, se recomienda usar [`phpcfdi/credentials`](https://github.com/phpcfdi/credentials)
dado que es una librería especializada para trabajar con archivos FIEL y CSD.

El siguiente ejemplo muestra cómo se puede usar `phpcfdi/credentials` para abrir
el certificado y llave privada tal como son entregadas por el SAT.

```php
<?php
use CfdiUtils\CfdiCreator40;
use PhpCfdi\Credentials\Credential;

/**
 * @var string $cerFile Ruta del archivo local del certificado
 * @var string $keyFile Ruta del archivo local de la llave privada
 * @var string $passPhrase Contraseña de la llave privada
 */
$csd = Credential::openFiles($cerfile, $keyfile, $passPhrase);

// helper de creación del cfdi
$creator = new CfdiCreator40();

// poner el certificado y número del certificado junto con los datos del emisor
$creator->putCertificado(
    new Certificado($csd->certificate()->pem()),
    true // establecer RFC y el nombre del emisor sin régimen de capital
);

// se construye el pre-cfdi ...

// sellado del cfdi
$creator->addSello($csd->privateKey()->pem(), $csd->privateKey()->passPhrase());
```

El CSD también se puede crear usando el contenido del certificado y llave privada.

```php
<?php
use PhpCfdi\Credentials\Credential;

/**
 * @var string $cerFile Ruta del archivo local del certificado
 * @var string $keyFile Ruta del archivo local de la llave privada
 * @var string $passPhrase Contraseña de la llave privada
 */
$csd = Credential::openFiles($cerfile, $keyfile, $passPhrase);
```
