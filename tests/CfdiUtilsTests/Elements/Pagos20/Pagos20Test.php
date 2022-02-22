<?php

namespace CfdiUtilsTests\Elements\Pagos20;

use CfdiUtils\Elements\Pagos20\Pago;
use CfdiUtils\Elements\Pagos20\Pagos;
use CfdiUtils\Elements\Pagos20\Totales;
use CfdiUtils\Elements\Pagos20\TrasladoP;
use CfdiUtils\Elements\Pagos20\ImpuestosP;
use CfdiUtils\Elements\Pagos20\RetencionP;
use CfdiUtils\Elements\Pagos20\TrasladoDR;
use CfdiUtils\Elements\Pagos20\TrasladosP;
use CfdiUtils\Elements\Pagos20\ImpuestosDR;
use CfdiUtils\Elements\Pagos20\RetencionDR;
use CfdiUtils\Elements\Pagos20\TrasladosDR;
use CfdiUtils\Elements\Pagos20\RetencionesP;
use CfdiUtilsTests\Elements\ElementTestCase;
use CfdiUtils\Elements\Pagos20\RetencionesDR;
use CfdiUtils\Elements\Pagos20\DoctoRelacionado;

final class Pagos20Test extends ElementTestCase
{

    public function testPagos20(): void
    {
        $element = new Pagos();
        $this->assertElementHasName($element, 'pagos20:Pagos');
        $this->assertElementHasFixedAttributes($element, [
            'xmlns:pagos20' => 'http://www.sat.gob.mx/Pagos20',
            'xsi:schemaLocation' => 'http://www.sat.gob.mx/Pagos20'
            . ' http://www.sat.gob.mx/sitio_internet/cfd/Pagos/Pagos20.xsd',
            'Version' => '2.0'
        ]);
        $this->assertElementHasChildSingle($element, Totales::class);
        $this->assertElementHasChildMultiple($element, Pago::class);
    }

    public function testTotales(): void
    {
        $element = new Totales();
        $this->assertElementHasName($element, 'pagos20:Totales');
    }

    public function testPagos(): void
    {
        $element = new Pagos();
        $this->assertElementHasName($element, 'pagos20:Pagos');
        $this->assertElementHasChildMultiple($element, Pago::class);
    }

    public function testPago(): void
    {
        $element = new Pago();
        $this->assertElementHasName($element, 'pagos20:Pago');
        $this->assertElementHasOrder($element, [
            'pagos20:DoctoRelacionado',
            'pagos20:ImpuestosP'
        ]);
        $this->assertElementHasChildMultiple($element, DoctoRelacionado::class);
        $this->assertElementHasChildSingle($element, ImpuestosP::class);
    }

    public function testDoctoRelacionado(): void
    {
        $element = new DoctoRelacionado();
        $this->assertElementHasName($element, 'pagos20:DoctoRelacionado');
        $this->assertElementHasChildSingle($element, ImpuestosDR::class);
    }

    public function testImpuestosDR(): void
    {
        $element = new ImpuestosDR();
        $this->assertElementHasName($element, 'pagos20:ImpuestosDR');
        $this->assertElementHasOrder($element, [
            'pagos20:RetencionesDR',
            'pagos20:TrasladosDR'
        ]);
        $this->assertElementHasChildSingle($element, RetencionesDR::class);
        $this->assertElementHasChildSingle($element, TrasladosDR::class);
    }

    public function testRetencionesDR(): void
    {
        $element = new RetencionesDR();
        $this->assertElementHasName($element, 'pagos20:RetencionesDR');
        $this->assertElementHasChildMultiple($element, RetencionDR::class);
    }

    public function testRetencionDR(): void
    {
        $element = new RetencionDR();
        $this->assertElementHasName($element, 'pagos20:RetencionDR');
    }

    public function testTrasladosDR(): void
    {
        $element = new TrasladosDR();
        $this->assertElementHasName($element, 'pagos20:TrasladosDR');
        $this->assertElementHasChildMultiple($element, TrasladoDR::class);
    }

    public function testTrasladoDR(): void
    {
        $element = new TrasladoDR();
        $this->assertElementHasName($element, 'pagos20:TrasladoDR');
    }

    public function testImpuestosP(): void
    {
        $element = new ImpuestosP();
        $this->assertElementHasName($element, 'pagos20:ImpuestosP');
        $this->assertElementHasOrder($element, [
            'pagos20:RetencionesP',
            'pagos20:TrasladosP'
        ]);
        $this->assertElementHasChildSingle($element, RetencionesP::class);
        $this->assertElementHasChildSingle($element, TrasladosP::class);
    }

    public function testRetencionesP(): void
    {
        $element = new RetencionesP();
        $this->assertElementHasName($element, 'pagos20:RetencionesP');
        $this->assertElementHasChildMultiple($element, RetencionP::class);
    }

    public function testTrasladosP(): void
    {
        $element = new TrasladosP();
        $this->assertElementHasName($element, 'pagos20:TrasladosP');
        $this->assertElementHasChildMultiple($element, TrasladoP::class);
    }
}
