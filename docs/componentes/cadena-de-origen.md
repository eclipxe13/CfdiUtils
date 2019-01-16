# Cadena de origen

El SAT utiliza el método de generar cadenas originales para agrupar
en una forma y orden determinados la información que no debería ser alterada de un CFDI.

Una vez que se cuenta con la *cadena de origen*, se genera una firma con la llave
privada que puede ser verificada con un certificado (llave pública).

Si algún dato (que es parte de la cadena de origen) fue modificado
entonces producirá un sello diferente o la verificación del sello será negativa.

Esto significa que un CFDI *podría* ser alterado posteriormente a su elaboración.
Por ejemplo, se le puede agregar una adenda o poner/quitar formato al XML,
pues ni el nodo Addenda ni el "XML Whitespace" forman parte de la cadena de origen.

Incluso, es frecuente "reparar" un CFDI que tiene errores como una adenda sin XSD
o errores sintácticos como poner un número par de rutas en el `xsi:schemaLocation`
o eliminar espacios de nombres no utilizados.


## Método del SAT para generar cadenas de origen

El método que utiliza el SAT es convertir archivos XML (o parte de los archivos)
a texto simple utilizando la tecnología XSLT.

Nota: Este no es el único método, siguiendo la especificación del Anexo 20
y todas y cada una de las especificaciones de los complementos se podría
hacer un generador de cadenas de origen.

Yo ya lo hice antes y es mucho código para fabricar y testear, además de
que deberá cambiar conforme cambien las especificaciones.


## Generar una cadena de origen

Para generar cadenas de origen tenemos diferentes implementaciones de
la interfaz `\CfdiUtils\CadenaOrigen\XsltBuilderInterface`.
Contiene un único método `build(string $xmlContent, string $xsltLocation)`.

El `$xmlContent` es el XML que se desea convertir y `$xsltLocation` es la
ubicación del archivo XSLT (local o remoto).

Las implementaciones son:

- `DOMBuilder`: Genera la transformación usando PHP, aunque no existe
  soporte nativo para Xslt versión 2, la transformación es compatible
  y genera el resultado esperado.
- `GenkgoXslBuilder`: Funciona igual que `DOMBuilder` pero al momento de hacer
  la transformación utiliza la librería [genkgo/xsl](https://github.com/genkgo/xsl)
  que es una implementación de Xslt versión 2 en PHP.
  Para usarla debes hacer algo como `composer require genkgo/xsl`.
- `SaxonbCliBuilder`: Utiliza la herramienta
  [Saxon-B XSLT Processor](https://en.wikipedia.org/wiki/Saxon_XSLT) desde la
  ejecución por línea de comandos. Esta utilería presume la implementación de Xslt versión 2.
  Para usarla debes hacer algo como `apt-get install libsaxonb-java`.


### Generar una cadena de origen de un creador de CFDI 3.3

Si se está utilizando un objeto creador de un CFDI versión 3.3 se puede utilizar el
método `CfdiUtils\CfdiCreator33::buildCadenaDeOrigen(): string`.

Este método depende de las propiedades [`XmlResolver`](xmlresolver.md) para obtener los archivos XSLT
y de `XsltBuilder` para hacer la transformación.


### Generar la cadena de origen de un Comprobante

Se puede seguir esta receta:

```php
<?php
use \CfdiUtils\XmlResolver\XmlResolver;
use \CfdiUtils\CadenaOrigen\DOMBuilder;

// el contenido del cfdi
$xmlContent = file_get_contents('... archivo xml');

// usar el resolvedor para usar los recursos descargados
$resolver = new XmlResolver();

// el resolvedor tiene un método de ayuda para obtener le ubicacion del XSLT
// dependiendo de la versión del comprobante
$location = $resolver->resolveCadenaOrigenLocation('3.3');

// fabricar la cadena de origen
$builder = new DOMBuilder();
$cadenaorigen = $builder->build($xmlContent, $location);
```

Sin embargo, en la práctica es poco probable que desees generar la cadena de origen.
Básicamente porque si estás creando un CFDI esta será generada automáticamente.
Si estás leyendo o validando también será generada automáticamente por los validadores.


### Generar la cadena de origen de un Timbre Fiscal Digital

A diferencia de la cadena de origen del Comprobante, la cadena de origen del Timbre Fiscal Digital
sí se necesita al menos para mostrarla en la representación impresa del CFDI.

Para ello puedes utilizar la clase `\CfdiUtils\TimbreFiscalDigital\TfdCadenaDeOrigen`.
Esta clase solo funciona con TFD versiones 1.0 y 1.1. En caso de otra versión genera una excepción.

Esta clase depende de las propiedades [`XmlResolver`](xmlresolver.md) para obtener los archivos XSLT
y de `XsltBuilder` para hacer la transformación.

```php
<?php
use \CfdiUtils\TimbreFiscalDigital\TfdCadenaDeOrigen;

$tfdXmlString = '<tfd:TimbreFiscalDigital xmlns:tfd="..." />';

$builder = new TfdCadenaDeOrigen();

// para cambiar el XmlResolver (por omisión crea uno nuevo)
/** @var \CfdiUtils\XmlResolver\XmlResolver $myXmlResolver */
$builder->setXmlResolver($myXmlResolver);

// para cambiar el XsltBuilder (por omisión crea uno nuevo de tipo DOMBuilder)
/** @var \CfdiUtils\CadenaOrigen\XsltBuilderInterface $myXsltBuilder */
$builder->setXsltBuilder($myXsltBuilder);

$tfdCadenaOrigen = $builder->build($tfdXmlString);
```

Si no cuentas con el código XML del Timbre Fiscal Digital esta receta te puede ayudar:

```php
<?php
$cfdiFile = '/facturas/.../fei-123456.xml';
$cfdi = \CfdiUtils\Cfdi::newFromString($cfdiFile);
$tfd = $cfdi->getNode()->searchNode('cfdi:Complemento', 'tfd:TimbreFiscalDigital');
$tfdXmlString = \CfdiUtils\Nodes\XmlNodeUtils::nodeToXmlString($tfd);
```


## PHP y XLST versión 2

Es importante notar que hasta el momento (enero/2019) no es posible en PHP
procesar XSLT versión 2.0. Sin embargo el procesador que sí tiene PHP genera
las cadenas de origen a pesar de la versión.
Esto no garantiza que si el SAT modifica los archivos XSLT utilizando
características incompatibles se producirá el resultado correcto.

En la versión 2.2.0 de la librería se ha implementado **Saxon-B** y **Genkgo Xsl**
como alternativas al método de PHP. Las tres entregan el mismo resultado en los test.
La que se usa de forma predeterminada es la de PHP `DOMBuilder`.
