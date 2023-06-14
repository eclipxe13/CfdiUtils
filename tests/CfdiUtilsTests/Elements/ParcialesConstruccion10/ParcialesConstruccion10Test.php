<?php

namespace CfdiUtilsTests\Elements\ParcialesConstruccion10;

use CfdiUtils\Elements\ParcialesConstruccion10\Inmueble;
use CfdiUtils\Elements\ParcialesConstruccion10\ParcialesConstruccion;
use CfdiUtilsTests\Elements\ElementTestCase;

class ParcialesConstruccion10Test extends ElementTestCase
{
    public function testParcialesConstruccion(): void
    {
        $element = new ParcialesConstruccion();
        $this->assertElementHasName($element, 'servicioparcial:parcialesconstruccion');
        $this->assertElementHasFixedAttributes($element, [
            'xmlns:servicioparcial' => 'http://www.sat.gob.mx/servicioparcialconstruccion',
            'xsi:schemaLocation' => 'http://www.sat.gob.mx/servicioparcialconstruccion'
                . ' http://www.sat.gob.mx/sitio_internet/cfd'
                . '/servicioparcialconstruccion/servicioparcialconstruccion.xsd',
            'Version' => '1.0',
        ]);
        $this->assertElementHasChildSingle($element, Inmueble::class);
    }

    public function testInmueble(): void
    {
        $element = new Inmueble();
        $this->assertElementHasName($element, 'servicioparcial:Inmueble');
    }
}
