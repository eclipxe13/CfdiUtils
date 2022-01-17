<?php

namespace CfdiUtilsTests\Elements\Cfdi40;

use CfdiUtils\Elements\Cfdi40\ACuentaTerceros;
use CfdiUtils\Elements\Cfdi40\Addenda;
use CfdiUtils\Elements\Cfdi40\CfdiRelacionado;
use CfdiUtils\Elements\Cfdi40\CfdiRelacionados;
use CfdiUtils\Elements\Cfdi40\Complemento;
use CfdiUtils\Elements\Cfdi40\ComplementoConcepto;
use CfdiUtils\Elements\Cfdi40\Comprobante;
use CfdiUtils\Elements\Cfdi40\Concepto;
use CfdiUtils\Elements\Cfdi40\ConceptoImpuestos;
use CfdiUtils\Elements\Cfdi40\Conceptos;
use CfdiUtils\Elements\Cfdi40\CuentaPredial;
use CfdiUtils\Elements\Cfdi40\Emisor;
use CfdiUtils\Elements\Cfdi40\Impuestos;
use CfdiUtils\Elements\Cfdi40\InformacionAduanera;
use CfdiUtils\Elements\Cfdi40\InformacionGlobal;
use CfdiUtils\Elements\Cfdi40\Parte;
use CfdiUtils\Elements\Cfdi40\Receptor;
use CfdiUtils\Elements\Cfdi40\Retencion;
use CfdiUtils\Elements\Cfdi40\Retenciones;
use CfdiUtils\Elements\Cfdi40\Traslado;
use CfdiUtils\Elements\Cfdi40\Traslados;
use CfdiUtilsTests\Elements\ElementTestCase;

final class ComprobanteTest extends ElementTestCase
{
    public function testComprobante(): void
    {
        $element = new Comprobante();
        $this->assertElementHasName($element, 'cfdi:Comprobante');
        $this->assertElementHasOrder($element, [
            'cfdi:InformacionGlobal',
            'cfdi:CfdiRelacionados',
            'cfdi:Emisor',
            'cfdi:Receptor',
            'cfdi:Conceptos',
            'cfdi:Impuestos',
            'cfdi:Complemento',
            'cfdi:Addenda',
        ]);
        $this->assertElementHasFixedAttributes($element, [
            'xmlns:cfdi' => 'http://www.sat.gob.mx/cfd/4',
            'xmlns:xsi' => 'http://www.w3.org/2001/XMLSchema-instance',
            'xsi:schemaLocation' => 'http://www.sat.gob.mx/cfd/4'
                . ' http://www.sat.gob.mx/sitio_internet/cfd/4/cfdv40.xsd',
            'Version' => '4.0',
        ]);
        $this->assertElementHasChildSingle($element, InformacionGlobal::class);
        $this->assertElementHasChildMultiple($element, CfdiRelacionados::class);
        $this->assertElementHasChildSingle($element, Emisor::class);
        $this->assertElementHasChildSingle($element, Receptor::class);
        $this->assertElementHasChildSingle($element, Conceptos::class);
        $this->assertElementHasChildSingle($element, Impuestos::class);
        $this->assertElementHasChildSingleAddChild($element, Complemento::class);
        $this->assertElementHasChildSingleAddChild($element, Addenda::class);
    }

    public function testInformacionGlobal(): void
    {
        $element = new InformacionGlobal();
        $this->assertElementHasName($element, 'cfdi:InformacionGlobal');
    }

    public function testCfdiRelacionados(): void
    {
        $element = new CfdiRelacionados();
        $this->assertElementHasName($element, 'cfdi:CfdiRelacionados');
        $this->assertElementHasChildMultiple($element, CfdiRelacionado::class);
    }

    public function testCfdiRelacionado(): void
    {
        $element = new CfdiRelacionado();
        $this->assertElementHasName($element, 'cfdi:CfdiRelacionado');
    }

    public function testEmisor(): void
    {
        $element = new Emisor();
        $this->assertElementHasName($element, 'cfdi:Emisor');
    }

    public function testReceptor(): void
    {
        $element = new Receptor();
        $this->assertElementHasName($element, 'cfdi:Receptor');
    }

    public function testConceptos(): void
    {
        $element = new Conceptos();
        $this->assertElementHasName($element, 'cfdi:Conceptos');
        $this->assertElementHasChildMultiple($element, Concepto::class);
    }

    public function testConcepto(): void
    {
        $element = new Concepto();
        $this->assertElementHasName($element, 'cfdi:Concepto');
        $this->assertElementHasOrder($element, [
            'cfdi:Impuestos',
            'cfdi:ACuentaTerceros',
            'cfdi:InformacionAduanera',
            'cfdi:CuentaPredial',
            'cfdi:ComplementoConcepto',
            'cfdi:Parte',
        ]);
        $this->assertElementHasChildSingle($element, ConceptoImpuestos::class, 'getImpuestos', 'addImpuestos');
        $this->assertElementHasChildSingle($element, ACuentaTerceros::class);
        $this->assertElementHasChildMultiple($element, InformacionAduanera::class);
        $this->assertElementHasChildMultiple($element, CuentaPredial::class);
        $this->assertElementHasChildSingle($element, ComplementoConcepto::class);
        $this->assertElementHasChildMultiple($element, Parte::class);
    }

    public function testConceptoImpuestos(): void
    {
        $element = new ConceptoImpuestos();
        $this->assertElementHasName($element, 'cfdi:Impuestos');
        $this->assertElementHasOrder($element, [
            'cfdi:Traslados',
            'cfdi:Retenciones',
        ]);
        $this->assertElementHasChildSingle($element, Traslados::class);
        $this->assertElementHasChildSingle($element, Retenciones::class);
    }

    public function testTraslados(): void
    {
        $element = new Traslados();
        $this->assertElementHasName($element, 'cfdi:Traslados');
        $this->assertElementHasChildMultiple($element, Traslado::class);
    }

    public function testTraslado(): void
    {
        $element = new Traslado();
        $this->assertElementHasName($element, 'cfdi:Traslado');
    }

    public function testRetenciones(): void
    {
        $element = new Retenciones();
        $this->assertElementHasName($element, 'cfdi:Retenciones');
        $this->assertElementHasChildMultiple($element, Retencion::class);
    }

    public function testRetencion(): void
    {
        $element = new Retencion();
        $this->assertElementHasName($element, 'cfdi:Retencion');
    }

    public function testACuentaTerceros(): void
    {
        $element = new ACuentaTerceros();
        $this->assertElementHasName($element, 'cfdi:ACuentaTerceros');
    }

    public function testInformacionAduanera(): void
    {
        $element = new InformacionAduanera();
        $this->assertElementHasName($element, 'cfdi:InformacionAduanera');
    }

    public function testCuentaPredial(): void
    {
        $element = new CuentaPredial();
        $this->assertElementHasName($element, 'cfdi:CuentaPredial');
    }

    public function testComplementoConcepto(): void
    {
        $element = new ComplementoConcepto();
        $this->assertElementHasName($element, 'cfdi:ComplementoConcepto');
    }

    public function testParte(): void
    {
        $element = new Parte();
        $this->assertElementHasName($element, 'cfdi:Parte');
        $this->assertElementHasChildMultiple($element, InformacionAduanera::class);
    }

    public function testImpuestos(): void
    {
        $element = new Impuestos();
        $this->assertElementHasName($element, 'cfdi:Impuestos');
        $this->assertElementHasOrder($element, [
            'cfdi:Retenciones',
            'cfdi:Traslados',
        ]);
        $this->assertElementHasChildSingle($element, Retenciones::class);
        $this->assertElementHasChildSingle($element, Traslados::class);
    }

    public function testComplemento(): void
    {
        $element = new Complemento();
        $this->assertElementHasName($element, 'cfdi:Complemento');
    }

    public function testAddenda(): void
    {
        $element = new Addenda();
        $this->assertElementHasName($element, 'cfdi:Addenda');
    }
}
