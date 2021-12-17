<?php

namespace CfdiUtilsTests\Elements\CartaPorte20;

use CfdiUtils\Elements\CartaPorte20\Autotransporte;
use CfdiUtils\Elements\CartaPorte20\CantidadTransporta;
use CfdiUtils\Elements\CartaPorte20\Carro;
use CfdiUtils\Elements\CartaPorte20\CartaPorte;
use CfdiUtils\Elements\CartaPorte20\Contenedor;
use CfdiUtils\Elements\CartaPorte20\DerechosDePaso;
use CfdiUtils\Elements\CartaPorte20\DetalleMercancia;
use CfdiUtils\Elements\CartaPorte20\Domicilio;
use CfdiUtils\Elements\CartaPorte20\FiguraTransporte;
use CfdiUtils\Elements\CartaPorte20\GuiasIdentificacion;
use CfdiUtils\Elements\CartaPorte20\IdentificacionVehicular;
use CfdiUtils\Elements\CartaPorte20\Mercancia;
use CfdiUtils\Elements\CartaPorte20\Mercancias;
use CfdiUtils\Elements\CartaPorte20\PartesTransporte;
use CfdiUtils\Elements\CartaPorte20\Pedimentos;
use CfdiUtils\Elements\CartaPorte20\Remolque;
use CfdiUtils\Elements\CartaPorte20\Remolques;
use CfdiUtils\Elements\CartaPorte20\Seguros;
use CfdiUtils\Elements\CartaPorte20\TiposFigura;
use CfdiUtils\Elements\CartaPorte20\TransporteAereo;
use CfdiUtils\Elements\CartaPorte20\TransporteFerroviario;
use CfdiUtils\Elements\CartaPorte20\TransporteMaritimo;
use CfdiUtils\Elements\CartaPorte20\Ubicacion;
use CfdiUtils\Elements\CartaPorte20\Ubicaciones;
use CfdiUtilsTests\Elements\ElementTestCase;

final class CartaPorteTest extends ElementTestCase
{
    public function testCartaPorte(): void
    {
        $element = new CartaPorte();
        $this->assertElementHasName($element, 'cartaporte20:CartaPorte');
        $this->assertElementHasOrder($element, [
            'cartaporte20:Ubicaciones',
            'cartaporte20:Mercancias',
            'cartaporte20:FiguraTransporte',
        ]);
        $this->assertElementHasFixedAttributes($element, [
            'xmlns:cartaporte20' => 'http://www.sat.gob.mx/cartaporte',
            'xsi:schemaLocation' => 'http://www.sat.gob.mx/cartaporte'
                . ' http://www.sat.gob.mx/sitio_internet/cfd/CartaPorte/CartaPorte20.xsd',
            'Version' => '2.0',
        ]);
        $this->assertElementHasChildSingle($element, Ubicaciones::class);
        $this->assertElementHasChildSingle($element, Mercancias::class);
        $this->assertElementHasChildSingle($element, FiguraTransporte::class);
    }

    public function testUbicaciones(): void
    {
        $element = new Ubicaciones();
        $this->assertElementHasName($element, 'cartaporte20:Ubicaciones');
        $this->assertElementHasChildMultiple($element, Ubicacion::class);
    }

    public function testMercancias(): void
    {
        $element = new Mercancias();
        $this->assertElementHasName($element, 'cartaporte20:Mercancias');
        $this->assertElementHasOrder($element, [
            'cartaporte20:Mercancia',
            'cartaporte20:Autotransporte',
            'cartaporte20:TransporteMaritimo',
            'cartaporte20:TransporteAereo',
            'cartaporte20:TransporteFerroviario',
        ]);
        $this->assertElementHasChildMultiple($element, Mercancia::class);
        $this->assertElementHasChildSingle($element, Autotransporte::class);
        $this->assertElementHasChildSingle($element, TransporteMaritimo::class);
        $this->assertElementHasChildSingle($element, TransporteAereo::class);
        $this->assertElementHasChildSingle($element, TransporteFerroviario::class);
    }

    public function testFiguraTransporte(): void
    {
        $element = new FiguraTransporte();
        $this->assertElementHasName($element, 'cartaporte20:FiguraTransporte');
        $this->assertElementHasChildMultiple($element, TiposFigura::class);
    }

    public function testUbicacion(): void
    {
        $element = new Ubicacion();
        $this->assertElementHasName($element, 'cartaporte20:Ubicacion');
        $this->assertElementHasChildSingle($element, Domicilio::class);
    }

    public function testMercancia(): void
    {
        $element = new Mercancia();
        $this->assertElementHasName($element, 'cartaporte20:Mercancia');
        $this->assertElementHasOrder($element, [
            'cartaporte20:Pedimentos',
            'cartaporte20:GuiasIdentificacion',
            'cartaporte20:CantidadTransporta',
            'cartaporte20:DetalleMercancia',
        ]);
        $this->assertElementHasChildMultiple($element, Pedimentos::class);
        $this->assertElementHasChildMultiple($element, GuiasIdentificacion::class);
        $this->assertElementHasChildMultiple($element, CantidadTransporta::class);
        $this->assertElementHasChildSingle($element, DetalleMercancia::class);
    }

    public function testAutotransporte(): void
    {
        $element = new Autotransporte();
        $this->assertElementHasName($element, 'cartaporte20:Autotransporte');
        $this->assertElementHasOrder($element, [
            'cartaporte20:IdentificacionVehicular',
            'cartaporte20:Seguros',
            'cartaporte20:Remolques',
        ]);
        $this->assertElementHasChildSingle($element, IdentificacionVehicular::class);
        $this->assertElementHasChildSingle($element, Seguros::class);
        $this->assertElementHasChildSingle($element, Remolques::class);
    }

    public function testTransporteMaritimo(): void
    {
        $element = new TransporteMaritimo();
        $this->assertElementHasName($element, 'cartaporte20:TransporteMaritimo');
        $this->assertElementHasChildMultiple($element, Contenedor::class);
    }

    public function testTransporteAereo(): void
    {
        $element = new TransporteAereo();
        $this->assertElementHasName($element, 'cartaporte20:TransporteAereo');
    }

    public function testTransporteFerroviario(): void
    {
        $element = new TransporteFerroviario();
        $this->assertElementHasName($element, 'cartaporte20:TransporteFerroviario');
        $this->assertElementHasOrder($element, [
            'cartaporte20:DerechosDePaso',
            'cartaporte20:Carro',
        ]);
        $this->assertElementHasChildMultiple($element, DerechosDePaso::class);
        $this->assertElementHasChildMultiple($element, Carro::class);
    }

    public function testDomicilio(): void
    {
        $element = new Domicilio();
        $this->assertElementHasName($element, 'cartaporte20:Domicilio');
    }

    public function testTiposFigura(): void
    {
        $element = new TiposFigura();
        $this->assertElementHasName($element, 'cartaporte20:TiposFigura');
        $this->assertElementHasOrder($element, [
            'cartaporte20:PartesTransporte',
            'cartaporte20:Domicilio',
        ]);
        $this->assertElementHasChildMultiple($element, PartesTransporte::class);
        $this->assertElementHasChildSingle($element, Domicilio::class);
    }

    public function testPedimentos(): void
    {
        $element = new Pedimentos();
        $this->assertElementHasName($element, 'cartaporte20:Pedimentos');
    }

    public function testGuiasIdentificacion(): void
    {
        $element = new GuiasIdentificacion();
        $this->assertElementHasName($element, 'cartaporte20:GuiasIdentificacion');
    }

    public function testCantidadTransporta(): void
    {
        $element = new CantidadTransporta();
        $this->assertElementHasName($element, 'cartaporte20:CantidadTransporta');
    }

    public function testDetalleMercancia(): void
    {
        $element = new DetalleMercancia();
        $this->assertElementHasName($element, 'cartaporte20:DetalleMercancia');
    }

    public function testIdentificacionVehicular(): void
    {
        $element = new IdentificacionVehicular();
        $this->assertElementHasName($element, 'cartaporte20:IdentificacionVehicular');
    }

    public function testSeguros(): void
    {
        $element = new Seguros();
        $this->assertElementHasName($element, 'cartaporte20:Seguros');
    }

    public function testRemolques(): void
    {
        $element = new Remolques();
        $this->assertElementHasName($element, 'cartaporte20:Remolques');
        $this->assertElementHasChildMultiple($element, Remolque::class);
    }

    public function testContenedor(): void
    {
        $element = new Contenedor();
        $this->assertElementHasName($element, 'cartaporte20:Contenedor');
    }

    public function testDerechosDePaso(): void
    {
        $element = new DerechosDePaso();
        $this->assertElementHasName($element, 'cartaporte20:DerechosDePaso');
    }

    public function testCarro(): void
    {
        $element = new Carro();
        $this->assertElementHasName($element, 'cartaporte20:Carro');
        $this->assertElementHasChildMultiple($element, Contenedor::class);
    }

    public function testPartesTransporte(): void
    {
        $element = new PartesTransporte();
        $this->assertElementHasName($element, 'cartaporte20:PartesTransporte');
    }

    public function testRemolque(): void
    {
        $element = new Remolque();
        $this->assertElementHasName($element, 'cartaporte20:Remolque');
    }
}
