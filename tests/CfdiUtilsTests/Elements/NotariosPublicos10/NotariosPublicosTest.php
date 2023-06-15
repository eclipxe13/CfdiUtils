<?php

namespace CfdiUtilsTests\Elements\NotariosPublicos10;

use CfdiUtils\Elements\NotariosPublicos10\DatosAdquiriente;
use CfdiUtils\Elements\NotariosPublicos10\DatosAdquirienteCopSC;
use CfdiUtils\Elements\NotariosPublicos10\DatosAdquirientesCopSC;
use CfdiUtils\Elements\NotariosPublicos10\DatosEnajenante;
use CfdiUtils\Elements\NotariosPublicos10\DatosEnajenanteCopSC;
use CfdiUtils\Elements\NotariosPublicos10\DatosEnajenantesCopSC;
use CfdiUtils\Elements\NotariosPublicos10\DatosNotario;
use CfdiUtils\Elements\NotariosPublicos10\DatosOperacion;
use CfdiUtils\Elements\NotariosPublicos10\DatosUnAdquiriente;
use CfdiUtils\Elements\NotariosPublicos10\DatosUnEnajenante;
use CfdiUtils\Elements\NotariosPublicos10\DescInmueble;
use CfdiUtils\Elements\NotariosPublicos10\DescInmuebles;
use CfdiUtils\Elements\NotariosPublicos10\NotariosPublicos;
use CfdiUtilsTests\Elements\ElementTestCase;

final class NotariosPublicosTest extends ElementTestCase
{
    public function testNotariosPublicos(): void
    {
        $element = new NotariosPublicos();
        $this->assertElementHasName($element, 'notariospublicos:NotariosPublicos');
        $this->assertElementHasFixedAttributes($element, [
            'xmlns:notariospublicos' => 'http://www.sat.gob.mx/notariospublicos',
            'xsi:schemaLocation' => 'http://www.sat.gob.mx/notariospublicos'
                . ' http://www.sat.gob.mx/sitio_internet/cfd/notariospublicos/notariospublicos.xsd',
            'Version' => '1.0',
        ]);
        $this->assertElementHasChildSingle($element, DescInmuebles::class);
        $this->assertElementHasChildSingle($element, DatosOperacion::class);
        $this->assertElementHasChildSingle($element, DatosNotario::class);
        $this->assertElementHasChildSingle($element, DatosEnajenante::class);
        $this->assertElementHasChildSingle($element, DatosAdquiriente::class);
    }

    public function testDescInmuebles(): void
    {
        $element = new DescInmuebles();
        $this->assertElementHasName($element, 'notariospublicos:DescInmuebles');
        $this->assertElementHasChildMultiple($element, DescInmueble::class);
    }

    public function testDescInmueble(): void
    {
        $element = new DescInmueble();
        $this->assertElementHasName($element, 'notariospublicos:DescInmueble');
    }

    public function testDatosOperacion(): void
    {
        $element = new DatosOperacion();
        $this->assertElementHasName($element, 'notariospublicos:DatosOperacion');
    }

    public function testDatosNotario(): void
    {
        $element = new DatosNotario();
        $this->assertElementHasName($element, 'notariospublicos:DatosNotario');
    }

    public function testDatosEnajenante(): void
    {
        $element = new DatosEnajenante();
        $this->assertElementHasName($element, 'notariospublicos:DatosEnajenante');
        $this->assertElementHasChildSingle($element, DatosUnEnajenante::class);
        $this->assertElementHasChildSingle($element, DatosEnajenantesCopSC::class);
    }

    public function testDatosUnEnajenante(): void
    {
        $element = new DatosUnEnajenante();
        $this->assertElementHasName($element, 'notariospublicos:DatosUnEnajenante');
    }

    public function testDatosEnajenantesCopSC(): void
    {
        $element = new DatosEnajenantesCopSC();
        $this->assertElementHasName($element, 'notariospublicos:DatosEnajenantesCopSC');
        $this->assertElementHasChildMultiple($element, DatosEnajenanteCopSC::class);
    }

    public function testDatosEnajenanteCopSC(): void
    {
        $element = new DatosEnajenanteCopSC();
        $this->assertElementHasName($element, 'notariospublicos:DatosEnajenanteCopSC');
    }

    public function testDatosAdquiriente(): void
    {
        $element = new DatosAdquiriente();
        $this->assertElementHasName($element, 'notariospublicos:DatosAdquiriente');
        $this->assertElementHasChildSingle($element, DatosUnAdquiriente::class);
        $this->assertElementHasChildSingle($element, DatosAdquirientesCopSC::class);
    }

    public function testDatosUnAdquiriente(): void
    {
        $element = new DatosUnAdquiriente();
        $this->assertElementHasName($element, 'notariospublicos:DatosUnAdquiriente');
    }

    public function testDatosAdquirientesCopSC(): void
    {
        $element = new DatosAdquirientesCopSC();
        $this->assertElementHasName($element, 'notariospublicos:DatosAdquirientesCopSC');
        $this->assertElementHasChildMultiple($element, DatosAdquirienteCopSC::class);
    }

    public function testDatosAdquirienteCopSC(): void
    {
        $element = new DatosAdquirienteCopSC();
        $this->assertElementHasName($element, 'notariospublicos:DatosAdquirienteCopSC');
    }
}
