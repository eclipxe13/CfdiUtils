<?php

namespace CfdiUtilsTests;

use CfdiUtils\Certificado\Certificado;
use CfdiUtils\CfdiCreator40;
use CfdiUtils\Utils\Format;
use CfdiUtils\Validate\Status;

final class CreateComprobante40CaseTest extends TestCase
{
    public function testCreateCfdiUsingComprobanteElement()
    {
        $cerfile = $this->utilAsset('certs/EKU9003173C9.cer');
        $keyfile = $this->utilAsset('certs/EKU9003173C9.key.pem');
        $certificado = new Certificado($cerfile);
        $fecha = mktime(14, 15, 16, 1, 13, 2021); // 2021-01-13 14:15:16

        // create comprobante using creator with attributes
        // did not set the XmlResolver then a new XmlResolver is created using the default location
        $creator = new CfdiCreator40([
            'Serie' => 'XXX',
            'Folio' => '0000123456',
            'Fecha' => Format::datetime($fecha),
            'FormaPago' => '01', // efectivo
            'Moneda' => 'USD',
            'TipoCambio' => Format::number(18.9008, 4), // taken from banxico
            'TipoDeComprobante' => 'I', // ingreso
            'Exportacion' => '01', // No aplica
            'LugarExpedicion' => '52000',
        ], $certificado);

        $comprobante = $creator->comprobante();
        $comprobante['MetodoPago'] = 'PUE'; // Pago en una sola exhibición
        $comprobante->addEmisor([
            'RegimenFiscal' => '601', // General de Ley Personas Morales
        ]);

        $comprobante->addReceptor([
            'Rfc' => 'COSC8001137NA',
            'Nombre' => 'Carlos Cortés Soto', // note is an "e" with accent
            'UsoCFDI' => 'G01', // Adquisición de mercancías
            'RegimenFiscalReceptor' => '612', // Personas Físicas con Actividades Empresariales y Profesionales
            'DomicilioFiscalReceptor' => '52000',
        ]);

        // add concepto #1
        $concepto = $comprobante->addConcepto([
            'ClaveProdServ' => '52161557', // Consola portátil de juegos de computador
            'NoIdentificacion' => 'GAMEPAD007',
            'Cantidad' => '4',
            'ClaveUnidad' => 'H87', // Pieza
            'Unidad' => 'PIEZA',
            'Descripcion' => 'Portable tetris gamepad pro++',
            'ValorUnitario' => '500',
            'Importe' => '2000',
            'Descuento' => '500', // hot sale: take 4, pay only 3
            'ObjetoImp' => '02',
        ]);
        $concepto->addTraslado([
            'Base' => '1500',
            'Impuesto' => '002', // IVA
            'TipoFactor' => 'Tasa', // this is a catalog
            'TasaOCuota' => '0.160000', // this is also a catalog
            'Importe' => '240',
        ]);
        $concepto->multiInformacionAduanera(
            ['NumeroPedimento' => '17  24  3420  7010987'],
            ['NumeroPedimento' => '17  24  3420  7010123']
        );

        // add concepto #2
        $comprobante->addConcepto([
            'ClaveProdServ' => '43211914', // Pantalla pasiva lcd
            'NoIdentificacion' => 'SCREEN5004',
            'Cantidad' => '1',
            'ClaveUnidad' => 'H87', // Pieza
            'Unidad' => 'PIEZA',
            'Descripcion' => 'Pantalla led 3x4" con entrada HDMI',
            'ValorUnitario' => '1000',
            'Importe' => '1000',
            'ObjetoImp' => '02',
        ])->addTraslado([
            'Base' => '1000',
            'Impuesto' => '002', // IVA
            'TipoFactor' => 'Tasa', // this is a catalog
            'TasaOCuota' => '0.160000', // this is also a catalog
            'Importe' => '160',
        ]);

        // concepto #3 (freight)
        $comprobante->addConcepto([
            // - Servicios de Transporte, Almacenaje y Correo
            //   - Manejo y embalaje de material
            //     - Servicios de manejo de materiales
            //       - Tarifa de los fletes
            'ClaveProdServ' => '78121603', // Tarifa de los fletes
            'NoIdentificacion' => 'FLETE-MX',
            'Cantidad' => '1',
            'ClaveUnidad' => 'E48', // Unidad de servicio
            'Unidad' => 'SERVICIO',
            'Descripcion' => 'Servicio de envío de mercancías',
            'ValorUnitario' => '300',
            'Importe' => '300',
            'ObjetoImp' => '02',
        ])->addTraslado([
            'Base' => '300',
            'Impuesto' => '002', // IVA
            'TipoFactor' => 'Tasa', // this is a catalog
            'TasaOCuota' => '0.160000', // this is also a catalog
            'Importe' => '48',
        ]);

        // add additional calculated information sumas sello
        $creator->addSumasConceptos(null, 2);
        $creator->addSello('file://' . $keyfile);

        // validate the comprobante and check it has no errors or warnings
        $asserts = $creator->validate();

        $this->assertFalse($asserts->hasErrors());
        $this->assertFalse($asserts->hasStatus(Status::warn()));

        // check the xml
        $expectedFileContents = $this->utilAsset('created-with-discounts-40.xml');
        $xmlContents = $creator->asXml();
        $this->assertXmlStringEqualsXmlFile($expectedFileContents, $xmlContents);
        $this->assertStringStartsWith('<?xml', $xmlContents);
    }
}
