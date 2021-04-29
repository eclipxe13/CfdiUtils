<?php

namespace CfdiUtilsTests\UseCases;

use CfdiUtils\CadenaOrigen\CfdiDefaultLocations;
use CfdiUtils\CadenaOrigen\DOMBuilder;
use CfdiUtils\Certificado\Certificado;
use CfdiUtils\Cfdi;
use CfdiUtils\CfdiCreator33;
use CfdiUtils\ConsultaCfdiSat\RequestParameters;
use CfdiUtils\Elements\Tfd11\TimbreFiscalDigital;
use CfdiUtils\Nodes\Node;
use CfdiUtils\Utils\Format;
use CfdiUtilsTests\TestCase;
use PhpCfdi\CfdiCleaner\XmlDocumentCleaners\CollapseComplemento;

final class ReadCfdiWithTimbreOnSecondComplementoTest extends TestCase
{
    public function testRetrieveTimbre()
    {
        $uuid = '11111111-2222-3333-4444-555555555555';
        $dirtyXml = $this->createCfdiForTesting($uuid);
        $dirtySourceString = $this->obtainSourceString($dirtyXml);
        $dirtyCfdi = Cfdi::newFromString($dirtyXml);
        $this->assertCount(2, $dirtyCfdi->getNode()->searchNodes('cfdi:Complemento')); // expected 2 complemento

        // none of this methods retrieve the correct UUID (verification that the problem exists)
        $this->assertEmpty(
            $dirtyCfdi->getQuickReader()->complemento->timbreFiscalDigital['UUID'],
            'Expected empty UUID from dirty CFDI using QuickReader'
        );
        $this->assertEmpty(
            $dirtyCfdi->getNode()->searchAttribute('cfdi:Complemento', 'tfd:TimbreFiscalDigital', 'UUID'),
            'Expected empty UUID from dirty CFDI using NodeInterface::searchAttribute'
        );
        $this->assertEmpty(
            RequestParameters::createFromCfdi($dirtyCfdi)->getUuid(),
            'Expected empty UUID from dirty CFDI using RequestParameters'
        );

        // perform cleaning
        $cleanDocument = $dirtyCfdi->getDocument();
        $cleaner = new CollapseComplemento();
        $cleaner->clean($cleanDocument);

        // open the clean XML
        $cleanCfdi = new Cfdi($cleanDocument);
        $cleanXml = $cleanCfdi->getSource();
        $cleanSourceString = $this->obtainSourceString($cleanXml);
        $this->assertCount(1, $cleanCfdi->getNode()->searchNodes('cfdi:Complemento'));  // expected 1 complemento
        $this->assertSame($dirtySourceString, $cleanSourceString, 'Source string after cleaning must be the same');

        // assert that the TimbreFiscalDigital can be read using QuickReader
        $this->assertSame(
            $uuid,
            $cleanCfdi->getQuickReader()->complemento->timbreFiscalDigital['UUID'],
            'Cannot get UUID using QuickReader'
        );

        // assert that the TimbreFiscalDigital can be read using Node
        $this->assertSame(
            $uuid,
            $cleanCfdi->getNode()->searchAttribute('cfdi:Complemento', 'tfd:TimbreFiscalDigital', 'UUID'),
            'Cannot get UUID using NodeInterface::searchAttribute'
        );

        // assert that the TimbreFiscalDigital can be read using RequestParameters
        $this->assertSame(
            $uuid,
            RequestParameters::createFromCfdi($cleanCfdi)->getUuid(),
            'Cannot get UUID using RequestParameters'
        );
    }

    protected function createCfdiForTesting(string $uuid): string
    {
        $cerfile = $this->utilAsset('certs/EKU9003173C9.cer');
        $keyfile = $this->utilAsset('certs/EKU9003173C9.key.pem');
        $certificado = new Certificado($cerfile);
        $fecha = strtotime('now - 10 minutes');

        $creator = new CfdiCreator33();
        $creator->putCertificado($certificado);
        $creator->setXmlResolver($this->newResolver());

        $comprobante = $creator->comprobante();
        $comprobante->addAttributes([
            'Serie' => 'XXX',
            'Folio' => '0000123456',
            'Fecha' => Format::datetime($fecha),
            'FormaPago' => '01', // efectivo
            'Moneda' => 'USD',
            'TipoCambio' => Format::number(18.9008, 4),
            'TipoDeComprobante' => 'I', // ingreso
            'LugarExpedicion' => '52000',
        ]);
        $comprobante['MetodoPago'] = 'PUE'; // Pago en una sola exhibición
        $comprobante->addEmisor([
            'RegimenFiscal' => '601', // General de Ley Personas Morales
        ]);

        $comprobante->addReceptor([
            'Rfc' => 'COSC8001137NA',
            'Nombre' => 'Carlos Cortés Soto', // note is an "e" with accent
            'UsoCFDI' => 'G01', // Adquisición de mercancias
        ]);

        $comprobante->addConcepto([
            'ClaveProdServ' => '52161557', // Consola portátil de juegos de computador
            'NoIdentificacion' => 'GAMEPAD007',
            'Cantidad' => '4',
            'ClaveUnidad' => 'H87', // Pieza
            'Unidad' => 'PIEZA',
            'Descripcion' => 'Portable tetris gamepad pro++',
            'ValorUnitario' => '500',
            'Importe' => '2000',
            'Descuento' => '500', // hot sale: take 4, pay only 3
        ])->addTraslado([
            'Base' => '1500',
            'Impuesto' => '002', // IVA
            'TipoFactor' => 'Tasa', // this is a catalog
            'TasaOCuota' => '0.160000', // this is also a catalog
            'Importe' => '240',
        ]);
        $creator->addSumasConceptos(null, 2);

        // agregar el primer complemento
        $leyenda = new Node('leyendasFisc:LeyendasFiscales', [
            'xmlns:leyendasFisc' => 'http://www.sat.gob.mx/leyendasFiscales',
            'xsi:schemaLocation' => 'http://www.sat.gob.mx/leyendasFiscales'
                . ' http://www.sat.gob.mx/sitio_internet/cfd/leyendasFiscales/leyendasFisc.xsd',
            'version' => '1.0',
        ], [
            new Node('leyendasFisc:Leyenda', ['textoLeyenda' => 'Esto es una prueba sobre un CFDI falso']),
        ]);
        $comprobante->addComplemento($leyenda);

        // sellar el archivo
        $creator->addSello('file://' . $keyfile);

        // validar que no tiene errores
        $asserts = $creator->validate();
        if ($asserts->hasErrors() || $asserts->hasWarnings()) {
            print_r([
                'warnings' => $asserts->warnings(),
                'errors' => $asserts->errors(),
            ]);
            throw new \RuntimeException('The PRECFDI created for testing has errors');
        }

        // agregar un timbre fiscal digital falso sobre un segundo nodo de complemento
        $segundoComplemento = new Node('cfdi:Complemento', [], [
            new TimbreFiscalDigital([
                'UUID' => $uuid,
                'FechaTimbrado' => Format::datetime($fecha + 60),
                'selloCFD' => str_repeat('0', 344),
                'noCertificadoSAT' => '00001000000000000001',
                'selloSAT' => str_repeat('1', 344),
            ]),
        ]);
        $comprobante->addChild($segundoComplemento);

        return $creator->asXml();
    }

    protected function obtainSourceString(string $dirtyXml): string
    {
        return (new DOMBuilder())->build(
            $dirtyXml,
            $this->newResolver()->resolve(CfdiDefaultLocations::location('3.3'))
        );
    }
}
