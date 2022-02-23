<?php

namespace CfdiUtilsTests\Elements\Pagos20;

use CfdiUtils\Elements\Pagos20\DoctoRelacionado;
use CfdiUtils\Elements\Pagos20\ImpuestosDR;
use CfdiUtils\Elements\Pagos20\ImpuestosP;
use CfdiUtils\Elements\Pagos20\Pago;
use CfdiUtils\Elements\Pagos20\Pagos;
use CfdiUtils\Elements\Pagos20\RetencionDR;
use CfdiUtils\Elements\Pagos20\RetencionesDR;
use CfdiUtils\Elements\Pagos20\RetencionesP;
use CfdiUtils\Elements\Pagos20\RetencionP;
use CfdiUtils\Elements\Pagos20\Totales;
use CfdiUtils\Elements\Pagos20\TrasladoDR;
use CfdiUtils\Elements\Pagos20\TrasladoP;
use CfdiUtils\Elements\Pagos20\TrasladosDR;
use CfdiUtils\Elements\Pagos20\TrasladosP;
use CfdiUtilsTests\Elements\ElementTestCase;

final class Pagos20Test extends ElementTestCase
{
    public function testPagos(): void
    {
        $element = new Pagos();
        $this->assertElementHasName($element, 'pago20:Pagos');
        $this->assertElementHasFixedAttributes($element, [
            'xmlns:pago20' => 'http://www.sat.gob.mx/Pagos20',
            'xsi:schemaLocation' => 'http://www.sat.gob.mx/Pagos20'
            . ' http://www.sat.gob.mx/sitio_internet/cfd/Pagos/Pagos20.xsd',
            'Version' => '2.0',
        ]);
        $this->assertElementHasChildSingle($element, Totales::class);
        $this->assertElementHasChildMultiple($element, Pago::class);
    }

    public function testTotales(): void
    {
        $element = new Totales();
        $this->assertElementHasName($element, 'pago20:Totales');
    }

    public function testPago(): void
    {
        $element = new Pago();
        $this->assertElementHasName($element, 'pago20:Pago');
        $this->assertElementHasOrder($element, [
            'pago20:DoctoRelacionado',
            'pago20:ImpuestosP',
        ]);
        $this->assertElementHasChildMultiple($element, DoctoRelacionado::class);
        $this->assertElementHasChildSingle($element, ImpuestosP::class);
    }

    public function testDoctoRelacionado(): void
    {
        $element = new DoctoRelacionado();
        $this->assertElementHasName($element, 'pago20:DoctoRelacionado');
        $this->assertElementHasChildSingle($element, ImpuestosDR::class);
    }

    public function testImpuestosDR(): void
    {
        $element = new ImpuestosDR();
        $this->assertElementHasName($element, 'pago20:ImpuestosDR');
        $this->assertElementHasOrder($element, [
            'pago20:RetencionesDR',
            'pago20:TrasladosDR',
        ]);
        $this->assertElementHasChildSingle($element, RetencionesDR::class);
        $this->assertElementHasChildSingle($element, TrasladosDR::class);
    }

    public function testRetencionesDR(): void
    {
        $element = new RetencionesDR();
        $this->assertElementHasName($element, 'pago20:RetencionesDR');
        $this->assertElementHasChildMultiple($element, RetencionDR::class);
    }

    public function testRetencionDR(): void
    {
        $element = new RetencionDR();
        $this->assertElementHasName($element, 'pago20:RetencionDR');
    }

    public function testTrasladosDR(): void
    {
        $element = new TrasladosDR();
        $this->assertElementHasName($element, 'pago20:TrasladosDR');
        $this->assertElementHasChildMultiple($element, TrasladoDR::class);
    }

    public function testTrasladoDR(): void
    {
        $element = new TrasladoDR();
        $this->assertElementHasName($element, 'pago20:TrasladoDR');
    }

    public function testImpuestosP(): void
    {
        $element = new ImpuestosP();
        $this->assertElementHasName($element, 'pago20:ImpuestosP');
        $this->assertElementHasOrder($element, [
            'pago20:RetencionesP',
            'pago20:TrasladosP',
        ]);
        $this->assertElementHasChildSingle($element, RetencionesP::class);
        $this->assertElementHasChildSingle($element, TrasladosP::class);
    }

    public function testRetencionesP(): void
    {
        $element = new RetencionesP();
        $this->assertElementHasName($element, 'pago20:RetencionesP');
        $this->assertElementHasChildMultiple($element, RetencionP::class);
    }

    public function testRetencionP(): void
    {
        $element = new RetencionP();
        $this->assertElementHasName($element, 'pago20:RetencionP');
    }

    public function testTrasladosP(): void
    {
        $element = new TrasladosP();
        $this->assertElementHasName($element, 'pago20:TrasladosP');
        $this->assertElementHasChildMultiple($element, TrasladoP::class);
    }

    public function testTrasladoP(): void
    {
        $element = new TrasladoP();
        $this->assertElementHasName($element, 'pago20:TrasladoP');
    }
}
