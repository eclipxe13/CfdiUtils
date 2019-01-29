# CFDI de retenciones e información de pagos

Existen otro tipo de CFDI definido en el Anexo 20 sección II adicionales los CFDI de tipo
Comprobante `<cfdi:Comprobante/>` (ingresos, egresos, traslados y pagos).

Este CFDI se llama *CFDI de retenciones e información de pagos* y tiene la misma esencia de
los comprobantes "tradicionales" (definir en campos requeridos, condicionales y obligatorios)
pero cuenta con una estructura totalmente diferente.

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


## Herramientas gratuitas

Es importante notar que **el SAT no cuenta con una herramienta para elaborar CFDI de retenciones e información de pagos**
como la tiene para los CFDI tradicionales, en su explicación de esquema operativo explican que se requiere utilizar
a un PAC para poderlos emitir.

Por otro lado, el SAT no les ha exigido a los PAC que cuenten con herramientas gratuitas para elaborarlos,
por lo que generalmente se requerirá contratar el uso de una aplicación o bien contratar los servicios de timbrado.

Con `CfdiUtils` podrás generar el *precfdi* y mandarlo timbrar con tu PAC habitual, el PAC te devolverá el *cfdi*
incluyendo el timbre fiscal digital y este es el comprobante legal.


## Más información

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


## Ejemplo de creación de un CFDI de retenciones e información de pagos

```php
<?php
// creator es un objeto de ayuda, similar a CfdiCreator33
$creator = new \CfdiUtils\Retenciones\RetencionesCreator10([
    'FechaExp' => '2019-01-23T08:00:00-06:00',
    'CveRetenc' => '14', // Dividendos o utilidades distribuidos
]);

// retenciones es un objeto de ayuda, similar a Comprobante
$retenciones = $creator->retenciones();
$retenciones->addEmisor([
    'RFCEmisor' => 'AAA010101AAA',
    'NomDenRazSocE' => 'ACCEM SERVICIOS EMPRESARIALES SC',
]);
$retenciones->getReceptor()->addExtranjero([
    'NumRegIdTrib' => '998877665544332211',
    'NomDenRazSocR' => 'WORLD WIDE COMPANY INC',
]);
$retenciones->addPeriodo(['MesIni' => '5', 'MesFin' => '5', 'Ejerc' => '2018']);
$retenciones->addTotales([
    'montoTotOperacion' => '55578643',
    'montoTotGrav' => '0',
    'montoTotExent' => '55578643',
    'montoTotRet' => '0',
]);
$retenciones->addImpRetenidos([
    'BaseRet' => '0',
    'Impuesto' => '01', // 01 - ISR
    'montoRet' => '0',
    'TipoPagoRet' => 'Pago provisional',
]);

// dividendos es un objeto de ayuda para el complemento, similar a Comprobante
$dividendos = new \CfdiUtils\Elements\Dividendos10\Dividendos();
$dividendos->addDividOUtil([
    'CveTipDivOUtil' => '06', // 06 - Proviene de CUFIN al 31 de diciembre 2013
    'MontISRAcredRetMexico' => '0',
    'MontISRAcredRetExtranjero' => '0',
    'MontRetExtDivExt' => '0',
    'TipoSocDistrDiv' => 'Sociedad Nacional',
    'MontISRAcredNal' => '0',
    'MontDivAcumNal' => '0',
    'MontDivAcumExt' => '0',
]);
$retenciones->addComplemento($dividendos);

// poner certificado y sellar el precfdi, después de sellar no debes hacer cambios
$creator->putCertificado(new \CfdiUtils\Certificado\Certificado('archivo.cer'));
$creator->addSello('file://archivo.key.pem', 'la contraseña');

// Asserts contendrá el resultado de la validación
$asserts = $creator->validate();

// guardar el precfdi
file_put_contents('precfdi.xml', $creator->asXml());
```
