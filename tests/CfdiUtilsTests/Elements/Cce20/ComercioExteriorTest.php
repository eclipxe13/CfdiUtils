<?php

namespace CfdiUtilsTests\Elements\Cce20;

use CfdiUtils\Elements\Cce20\ComercioExterior;
use CfdiUtils\Elements\Cce20\DescripcionesEspecificas;
use CfdiUtils\Elements\Cce20\Destinatario;
use CfdiUtils\Elements\Cce20\Domicilio;
use CfdiUtils\Elements\Cce20\Emisor;
use CfdiUtils\Elements\Cce20\Mercancia;
use CfdiUtils\Elements\Cce20\Mercancias;
use CfdiUtils\Elements\Cce20\Propietario;
use CfdiUtils\Elements\Cce20\Receptor;
use CfdiUtilsTests\Elements\ElementTestCase;

final class ComercioExteriorTest extends ElementTestCase
{
    public function testComercioExterior(): void
    {
        $element = new ComercioExterior();
        $this->assertElementHasName($element, 'cce20:ComercioExterior');
        $this->assertElementHasOrder($element, [
            'cce20:Emisor',
            'cce20:Propietario',
            'cce20:Receptor',
            'cce20:Destinatario',
            'cce20:Mercancias',
        ]);
        $this->assertElementHasFixedAttributes($element, [
            'xmlns:cce20' => 'http://www.sat.gob.mx/ComercioExterior20',
            'xsi:schemaLocation' => 'http://www.sat.gob.mx/ComercioExterior20'
                . ' http://www.sat.gob.mx/sitio_internet/cfd/ComercioExterior20/ComercioExterior20.xsd',
            'Version' => '2.0',
        ]);
        $this->assertElementHasChildSingle($element, Emisor::class);
        $this->assertElementHasChildMultiple($element, Propietario::class);
        $this->assertElementHasChildSingle($element, Receptor::class);
        $this->assertElementHasChildMultiple($element, Destinatario::class);
        $this->assertElementHasChildSingle($element, Mercancias::class);
    }

    public function testEmisor(): void
    {
        $element = new Emisor();
        $this->assertElementHasName($element, 'cce20:Emisor');
        $this->assertElementHasChildSingle($element, Domicilio::class);
    }

    public function testDomicilio(): void
    {
        $element = new Domicilio();
        $this->assertElementHasName($element, 'cce20:Domicilio');
    }

    public function testPropietario(): void
    {
        $element = new Propietario();
        $this->assertElementHasName($element, 'cce20:Propietario');
    }

    public function testReceptor(): void
    {
        $element = new Receptor();
        $this->assertElementHasName($element, 'cce20:Receptor');
        $this->assertElementHasChildSingle($element, Domicilio::class);
    }

    public function testDestinatario(): void
    {
        $element = new Destinatario();
        $this->assertElementHasName($element, 'cce20:Destinatario');
        $this->assertElementHasChildMultiple($element, Domicilio::class);
    }

    public function testMercancias(): void
    {
        $element = new Mercancias();
        $this->assertElementHasName($element, 'cce20:Mercancias');
        $this->assertElementHasChildMultiple($element, Mercancia::class);
    }

    public function testMercancia(): void
    {
        $element = new Mercancia();
        $this->assertElementHasName($element, 'cce20:Mercancia');
        $this->assertElementHasChildMultiple($element, DescripcionesEspecificas::class);
    }

    public function testDescripcionesEspecificas(): void
    {
        $element = new DescripcionesEspecificas();
        $this->assertElementHasName($element, 'cce20:DescripcionesEspecificas');
    }
}
