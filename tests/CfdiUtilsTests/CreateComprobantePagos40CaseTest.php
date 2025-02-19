<?php

namespace CfdiUtilsTests;

use CfdiUtils\Certificado\Certificado;
use CfdiUtils\CfdiCreator40;
use CfdiUtils\Elements\Pagos20\Pagos;
use CfdiUtils\SumasPagos20\PagosWriter;
use CfdiUtils\Utils\Format;

final class CreateComprobantePagos40CaseTest extends TestCase
{
    public function testCreateComprobantePagos(): void
    {
        $cerfile = $this->utilAsset('certs/EKU9003173C9.cer');
        $keyfile = $this->utilAsset('certs/EKU9003173C9.key.pem');
        $certificado = new Certificado($cerfile);
        $fecha = strtotime('2023-06-13 14:15:16');
        $fechaPago = strtotime('2023-06-12 17:18:19');

        $creator = new CfdiCreator40();
        $comprobante = $creator->comprobante();
        $comprobante->addAttributes([
            'Fecha' => Format::datetime($fecha),
            'TipoDeComprobante' => 'P', // pago
            'LugarExpedicion' => '52000',
            'Moneda' => 'XXX',
            'Exportacion' => '01',
        ]);
        $creator->putCertificado($certificado);

        $comprobante->addEmisor([
            'RegimenFiscal' => '601',
        ]);
        $comprobante->addReceptor([
            'Rfc' => 'COSC8001137NA',
            'Nombre' => 'CARLOS CORTES SOTO',
            'RegimenFiscalReceptor' => '605',
            'UsoCFDI' => 'CP01',
            'DomicilioFiscalReceptor' => '52000',
        ]);
        // The concepto *must* have this content
        $comprobante->addConcepto([
            'ClaveProdServ' => '84111506',
            'Cantidad' => '1',
            'ClaveUnidad' => 'ACT',
            'Descripcion' => 'Pago',
            'ValorUnitario' => '0',
            'Importe' => '0',
            'ObjetoImp' => '01',
        ]);

        $complementoPagos = new Pagos();
        $pago = $complementoPagos->addPago([
            'FechaPago' => Format::datetime($fechaPago),
            'FormaDePagoP' => '03', // transferencia
            'MonedaP' => 'MXN',
            'TipoCambioP' => '1',
            'Monto' => '15000.00',
            'NumOperacion' => '963852',
            'RfcEmisorCtaOrd' => 'BMI9704113PA',
            'CtaOrdenante' => '0001970000',
            'RfcEmisorCtaBen' => 'BBA830831LJ2',
            'CtaBeneficiario' => '0198005000',
        ]);

        $pago->addDoctoRelacionado([
            'IdDocumento' => '00000000-1111-2222-3333-00000000000A',
            'MonedaDR' => 'MXN',
            'EquivalenciaDR' => '1',
            'NumParcialidad' => '2',
            'ImpSaldoAnt' => '12000.00',
            'ImpPagado' => '12000.00',
            'ImpSaldoInsoluto' => '0',
            'ObjetoImpDR' => '02',
        ])->getImpuestosDR()->getTrasladosDR()->addTrasladoDR([
            'ImpuestoDR' => '002',
            'TipoFactorDR' => 'Tasa',
            'TasaOCuotaDR' => '0.160000',
            'BaseDR' => '10344.83',
            'ImporteDR' => '1655.17',
        ]);

        $pago->addDoctoRelacionado([
            'IdDocumento' => '00000000-1111-2222-3333-00000000000B',
            'MonedaDR' => 'MXN',
            'EquivalenciaDR' => '1',
            'NumParcialidad' => '1',
            'ImpSaldoAnt' => '10000.00',
            'ImpPagado' => '3000.00',
            'ImpSaldoInsoluto' => '7000.00',
            'ObjetoImpDR' => '02',
        ])->getImpuestosDR()->getTrasladosDR()->addTrasladoDR([
            'ImpuestoDR' => '002',
            'TipoFactorDR' => 'Tasa',
            'TasaOCuotaDR' => '0.160000',
            'BaseDR' => '2586.21',
            'ImporteDR' => '413.79',
        ]);

        // add calculated values to pagos (totales, pagos montos y pagos impuestos)
        PagosWriter::calculateAndPut($complementoPagos);

        // add the "complemento de pagos" ($complementoPagos) to the $comprobante
        $comprobante->addComplemento($complementoPagos);

        // use this method (with 0 decimals) to add attributes
        $creator->addSumasConceptos(null, 0);

        // add sello and validate to assert that the specimen does not have any errors
        $creator->addSello('file://' . $keyfile, '');

        // this is after add sello to probe that it did not change the cadena origen or the sello
        $creator->moveSatDefinitionsToComprobante();

        // perform validations, it should not have any error nor warnings
        $findings = $creator->validate();

        // print_r(['validation' => ['errors' => $findings->errors(), 'warnings' => $findings->warnings()]]);

        $this->assertFalse(
            $findings->hasErrors() || $findings->hasWarnings(),
            'Created document must not contain errors, fix your test specimen'
        );

        // test that the file is the same as expected
        /** @see tests/assets/created-cfdi40-pago20-valid.xml */
        $expectedFile = $this->utilAsset('created-cfdi40-pago20-valid.xml');
        $this->assertXmlStringEqualsXmlFile($expectedFile, $creator->asXml());
    }
}
