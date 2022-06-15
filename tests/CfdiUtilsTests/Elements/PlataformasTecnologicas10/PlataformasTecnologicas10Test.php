<?php

namespace CfdiUtilsTests\Elements\PlataformasTecnologicas10;

use CfdiUtils\Elements\PlataformasTecnologicas10\ComisionDelServicio;
use CfdiUtils\Elements\PlataformasTecnologicas10\ContribucionGubernamental;
use CfdiUtils\Elements\PlataformasTecnologicas10\DetallesDelServicio;
use CfdiUtils\Elements\PlataformasTecnologicas10\ImpuestosTrasladadosdelServicio;
use CfdiUtils\Elements\PlataformasTecnologicas10\Servicios;
use CfdiUtils\Elements\PlataformasTecnologicas10\ServiciosPlataformasTecnologicas;
use CfdiUtilsTests\Elements\ElementTestCase;

class PlataformasTecnologicas10Test extends ElementTestCase
{
    public function testPlataformasTecnologicas(): void
    {
        $element = new ServiciosPlataformasTecnologicas();
        $this->assertElementHasName($element, 'plataformasTecnologicas:ServiciosPlataformasTecnologicas');
        $this->assertElementHasFixedAttributes($element, [
            'xmlns:plataformasTecnologicas' => 'http://www.sat.gob.mx/esquemas/retencionpago/1'
                . '/PlataformasTecnologicas10',
            'xsi:schemaLocation' => 'http://www.sat.gob.mx/esquemas/retencionpago/1/PlataformasTecnologicas10'
                . ' http://www.sat.gob.mx/esquemas/retencionpago/1/PlataformasTecnologicas10'
                . '/ServiciosPlataformasTecnologicas10.xsd',
            'Version' => '1.0',
        ]);
        $this->assertElementHasChildSingle($element, Servicios::class);
    }

    public function testServicios(): void
    {
        $element = new Servicios();
        $this->assertElementHasName($element, 'plataformasTecnologicas:Servicios');
        $this->assertElementHasChildMultiple($element, DetallesDelServicio::class);
    }

    public function testDetallesDelServicio(): void
    {
        $element = new DetallesDelServicio();
        $this->assertElementHasName($element, 'plataformasTecnologicas:DetallesDelServicio');
        $this->assertElementHasChildSingle($element, ImpuestosTrasladadosdelServicio::class);
        $this->assertElementHasChildSingle($element, ContribucionGubernamental::class);
        $this->assertElementHasChildSingle($element, ComisionDelServicio::class);
    }

    public function testImpuestosTrasladados(): void
    {
        $element = new ImpuestosTrasladadosdelServicio();
        $this->assertElementHasName($element, 'plataformasTecnologicas:ImpuestosTrasladadosdelServicio');
    }

    public function testContribucionGubernamental(): void
    {
        $element = new ContribucionGubernamental();
        $this->assertElementHasName($element, 'plataformasTecnologicas:ContribucionGubernamental');
    }

    public function testComisionDelServicio(): void
    {
        $element = new ComisionDelServicio();
        $this->assertElementHasName($element, 'plataformasTecnologicas:ComisionDelServicio');
    }
}
