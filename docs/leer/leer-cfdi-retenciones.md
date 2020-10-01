# Leer un CFDI de Retenciones

Existen otro tipo de CFDI definido en el Anexo 20 sección II adicionales los CFDI de tipo
Comprobante `<cfdi:Comprobante/>` (ingresos, egresos, traslados y pagos).

Este CFDI se llama *CFDI de retenciones e información de pagos* y tiene la misma esencia de
los comprobantes "tradicionales" (definir en campos requeridos, condicionales y obligatorios)
pero cuenta con una estructura totalmente diferente.

## Acerca de los CFDI de Retenciones e información de pagos

Existe poca información alrededor de este tipo de comprobantes porque su generación es mucho
menor a los CFDI tradicionales.

Su obligatoriedad y la información que contienen está sujeta a LIVA, LISR y LIEPS.
Si requieres saber si caes en algún supuesto en donde requieras emitir este tipo de comprobantes
te recomiendo lo verifiques con tu contador de confianza.

Entre las diferencias fundamentales de *CFDI de retenciones* y *CFDI tradicionales* están:

- El espacio de nombres XML <http://www.sat.gob.mx/esquemas/retencionpago/1>
- El nombre de los atributos principales, como `retencion:Retenciones@Cert` en lugar de `cfdi:Comprobante@Certificado`
- Tiene sus propios catálogos de información
- El sello se procesa usando el algoritmo de digestión `SHA1` en lugar de `SHA256`
- La fecha se expresa en ISO-8601 (contiene al final `-06:00`)

Entre las semejanzas están:

- Se maneja con complementos
- Puede contener addendas
- También requiere firmarse por un PAC entregando un Timbre Fiscal Digital


### Herramientas gratuitas

Es importante notar que **el SAT no cuenta con una herramienta para elaborar CFDI de retenciones e información de pagos**
como la tiene para los CFDI tradicionales, en su explicación de esquema operativo explican que se requiere utilizar
a un PAC para poderlos emitir.

Por otro lado, el SAT no les ha exigido a los PAC que cuenten con herramientas gratuitas para elaborarlos,
por lo que generalmente se requerirá contratar el uso de una aplicación o bien contratar los servicios de timbrado.

Con `CfdiUtils` podrás generar el *precfdi* y mandarlo timbrar con tu PAC habitual, el PAC te devolverá el *cfdi*
incluyendo el timbre fiscal digital y este es el comprobante legal.


### Más información

- Esquema de factura de retenciones e información de pagos
  <https://www.sat.gob.mx/consulta/65554/conoce-el-esquema-de-factura-electronica-de-retenciones-e-informacion-de-pagos>
- Video chat No.21: CFDI de retenciones e información de pagos con sus complementos
  <https://www.youtube.com/watch?v=Z0TadVAjrFc>
- Anexo 20
  <http://omawww.sat.gob.mx/tramitesyservicios/Paginas/anexo_20_version3-3.htm>
- Preguntas frecuentes de retenciones e información de pagos
  <https://www.sat.gob.mx/cs/Satellite?blobcol=urldata&blobkey=id&blobtable=MungoBlobs&blobwhere=1461173416174&ssbinary=true>
- Esquema XML de estructura
  <http://www.sat.gob.mx/esquemas/retencionpago/1/retencionpagov1.xsd>
- Esquema XML de catálogos
  <http://www.sat.gob.mx/esquemas/retencionpago/1/catalogos/catRetenciones.xsd>
- Archivo PDF con los catálogos
  <https://www.sat.gob.mx/cs/Satellite?blobcol=urldata&blobkey=id&blobtable=MungoBlobs&blobwhere=1461172330889&ssbinary=true>
- Herramienta de transformación XML para generar la cadena original
  <http://www.sat.gob.mx/esquemas/retencionpago/1/retenciones.xslt>


## Lectura de CFDI de retenciones e información de pagos

La estrategia para leer CFDI de retenciones e información de pagos es la misma que se utiliza para realizar la
[lectura de CFDI](leer-cfdi.md). Consulta la información relacionada con el uso de
[elementos](../componentes/elements.md), [nodos](../componentes/nodes.md) y [lectura rápida](quickreader.md).

### Clase `CfdiUtils\Retenciones\Retenciones`

El objeto que se utiliza para poder hacer la lectura es `CfdiUtils\Retenciones\Retenciones`. Y lo puedes inicializar
por su constructor natural `$retenciones = new Retenciones(DOMDocument $document)` o con el constructor estático
`$retenciones = Retenciones::newFromString(string $xmlContent)`.

### Inmutabilidad

El objeto `Retenciones` es inmutable, es creado con un objeto `DOMDocument` pero es clonado al momento de su
construcción, por lo que si se hacen cambios en este objeto no se verán reflejados en el lector.
A su vez, el objeto devuelto por los métodos `getDocument()` o `getNode()`, a pesar de ser en sí mismos mutables,
no tendrán un impacto en el contenedor.

### Versión

El CFDI de retenciones solo tiene la versión 1.0, en caso de que se fabrique un objeto que contiene un número de versión
diferente, entonces el método `Retenciones::getVersion()` devolverá una cadena vacía. Esto es por homogeneidad con la
lectura de CFDI regulares.

### Lectura formal

La lectura formal utiliza [nodos](../componentes/nodes.md), que es una representación en memoria del contenido
de la estructura XML. En esta forma, los elementos XML se deben acceder utilizando su prefijo y nombre exacto,
y los atributos su nombre exacto. Para obtener el nodo raíz se usa el método `Retenciones::getNode()`.

### Lectura rápida

La lectura rápida utiliza [nodos](../componentes/nodes.md), que es una representación en memoria del contenido
de la estructura XML. En esta forma, a diferencia del modo formal, los elementos se obtienen solo por su nombre
simple y los nombres de elementos y atributos no son sensibles a mayúsculas y minúsculas.
Para obtener el nodo raíz se usa el método `Retenciones::getQuickReader()`.

### Ejemplo de lectura de CFDI de retenciones e información de pagos

```php
<?php

// construir el lector desde un contenido XML
$xmlContent = file_get_contents('cfdi-retenciones-e-informacion-de-pagos.xml');
$reader = \CfdiUtils\Retenciones\Retenciones::newFromString($xmlContent);

// obtener el nodo para lectura formal
$nodeRetenciones = $reader->getNode();
echo $nodeRetenciones->searchAttribute('retenciones:Emisor', 'RFCEmisor'); // AAA010101AAA

// obtener el QuickReader para lectura rápida
$qrRetenciones = $reader->getQuickReader();
echo $qrRetenciones->emisor['rfcemisor']; // AAA010101AAA
```
