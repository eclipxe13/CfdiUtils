# Creación de CFDI 3.3

Para crear un CFDI versión 3.3 se ofrece el objeto `CfdiUtils\CfdiCreator33`.

Este objeto trabaja directamente con la estructura `CfdiUtils\Elements\Cfdi33\Complemento`
para facilitar la manipulación de la estructura y los datos, y contiene métodos que ayudan
a establecer el certificado, generar el sello, generar o almacenar el XML, y validar la estructura recién creada.

Esta clase es una especie de pegamento de todas las pequeñas utilerías y estructuras de datos.

### Métodos de ayuda
- `comprobante(): Comprobante`: Obtiene el nodo raíz `Comprobante`. Todos los métodos utilizan este objeto.
- `putCertificado(Certificado $certificado, bool $putEmisorRfcNombre = true)`: Establece el valor de los atributos
   `NoCertificado` y `Certificado`, y si `$putEmisorRfcNombre` es verdadero entonces también establece el valor
   de `Rfc` y `Nombre` en el nodo `Emisor`.
- `asXml(): string`: Genera y devuelve el contenido XML de la exportación del nodo `Comprobante`.
- `saveXml(string $filename): bool`:  Genera y almacena el contenido XML.
- `buildCadenaDeOrigen(): string`: Construye la cadena de origen siempre que exista un resolvedor de recursos XML.
- `buildSumasConceptos(int $precision = 2): SumasConceptos`: Genera un objeto de tipo `SumasConceptos` según los datos de los `Conceptos`.
- `addSumasConceptos(SumasConceptos $sumasConceptos = null, int $precision = 2)`: Establece los valores de `$sumasConceptos`
   en el comprobante, si no se pasó el objeto entonces lo fabrica con `buildSumasConceptos()`.
- `addSello(string $key, string $passPhrase = '')`: Realiza el procedimiento de firma con la llave primaria y
   almacena el valor de dicha llave en base64 en el atributo `Sello`.
   Si el certificado existe como un objeto `Certificado` entonces este método también valida que la llave primaria
   pertenece al certificado y genera una excepción si no es así.
- `validate(): Asserts`: Crea un validador que verifica la estructura XML contra su archivo XSD
   y realiza validaciones adicionales. Consulta la [documentación de validaciones](Validar-cfdi33) para más información.


### Resolvedor de recursos XML

Los archivos XSD necesarios para validar la estructura XML de un CFDI y
los archivos XSLT necesarios para generar la cadena de origen
son almacenados localmente y reutilizados cada vez que se require.

Para establecer dicha configuración diferente a la predeterminada establezca el objeto `XmlResolver`
usando el método `CfdiCreator33::setXmlResolver(XmlResolver $xmlResolver = null)`.

Si establece el valor a nulo (`CfdiCreator33::hasXmlResolver()` es `false`) entonces no se podrá
crear la cadena de origen (necesario para obtener la ruta de los archivos XSLT) y tampoco se podrá abastecer
a los objetos de validación que requieran de un resolvedor con el objeto apropiado resultando en varias revisiones
sin ejecutar.

Si lo que desea es no almacenar localmente los recursos entonces lo que debe hacer es establecer
una cadena de caracteres vacía mediante el método `XmlResolver::setLocalPath`.


### Pasos básicos de creación de un CFDI

No hay una sola forma de hacer las cosas, pero la receta de creación sería algo como:

```php
<?php
$certificado = new \CfdiUtils\Certificado\Certificado('... ubicación archivo CER');
$comprobanteAtributos = [
    'Serie' => 'XXX',
    'Folio' => '0000123456',
    // y otros atributos más...
];
$creator = new \CfdiUtils\CfdiCreator33($comprobanteAtributos, $certificado);

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

// método de ayuda para validar usando las validaciones estándar de creación de la librería
$asserts = $creator->validate();
$asserts->hasErrors(); // contiene si hay o no errores

// método de ayuda para generar el xml y guardar los contenidos en un archivo
$creator->saveXml('... lugar para almacenar el cfdi ...');

// método de ayuda para generar el xml y retornarlo como un string
$creator->asXml();
```


### Formación de el texto de los códigos QR.

La formación del texto que se incluye en los códigos QR tiene reglas específicas
y puede utilizarse el objeto `\CfdiUtils\ConsultaCfdiSat\RequestParameters`
para obtener la representación impresa.


### Orden de los nodos de un CFDI

A pesar de tratarse de una estructura XML el SAT por las reglas impuestas en los
archivos XSD ha puesto reglas de orden de aparición de nodos.

Por lo anterior esta estructura presentará error porque el nodo `Receptor`
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
