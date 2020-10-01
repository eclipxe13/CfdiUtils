# CFDI de retenciones e información de pagos

Revisa la documentación de la [Lectura de CFDI de retenciones e información de pagos](../leer/leer-cfdi-retenciones.md),
ahí encontrarás más información de referencia.

En esta sección encontrarás el ejemplo para poder crear un CFDI de este tipo.

La estrategia para crear este un CFDI de retenciones es la misma que para [crear un CFDI 3.3](../crear/crear-cfdi.md).
Consulta la información relacionada con el uso de [elementos](../componentes/elements.md),
[nodos](../componentes/nodes.md) y [complementos no implementados](../crear/complementos-aun-no-implementados.md).

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
