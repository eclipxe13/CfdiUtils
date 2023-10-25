<?php

namespace CfdiUtilsTests\Elements\CartaPorte30;

use CfdiUtils\Elements\CartaPorte30\Autotransporte;
use CfdiUtils\Elements\CartaPorte30\CantidadTransporta;
use CfdiUtils\Elements\CartaPorte30\Carro;
use CfdiUtils\Elements\CartaPorte30\CartaPorte;
use CfdiUtils\Elements\CartaPorte30\Contenedor;
use CfdiUtils\Elements\CartaPorte30\DerechosDePaso;
use CfdiUtils\Elements\CartaPorte30\DetalleMercancia;
use CfdiUtils\Elements\CartaPorte30\DocumentacionAduanera;
use CfdiUtils\Elements\CartaPorte30\Domicilio;
use CfdiUtils\Elements\CartaPorte30\FiguraTransporte;
use CfdiUtils\Elements\CartaPorte30\GuiasIdentificacion;
use CfdiUtils\Elements\CartaPorte30\IdentificacionVehicular;
use CfdiUtils\Elements\CartaPorte30\Mercancia;
use CfdiUtils\Elements\CartaPorte30\Mercancias;
use CfdiUtils\Elements\CartaPorte30\PartesTransporte;
use CfdiUtils\Elements\CartaPorte30\Remolque;
use CfdiUtils\Elements\CartaPorte30\RemolqueCCP;
use CfdiUtils\Elements\CartaPorte30\Remolques;
use CfdiUtils\Elements\CartaPorte30\RemolquesCCP;
use CfdiUtils\Elements\CartaPorte30\Seguros;
use CfdiUtils\Elements\CartaPorte30\TiposFigura;
use CfdiUtils\Elements\CartaPorte30\TransporteAereo;
use CfdiUtils\Elements\CartaPorte30\TransporteFerroviario;
use CfdiUtils\Elements\CartaPorte30\TransporteMaritimo;
use CfdiUtils\Elements\CartaPorte30\Ubicacion;
use CfdiUtils\Elements\CartaPorte30\Ubicaciones;
use CfdiUtilsTests\Elements\ElementTestCase;

final class CartaPorteTest extends ElementTestCase
{
    public function testCartaPorte(): void
    {
        $element = new CartaPorte();
        $this->assertElementHasName($element, 'cartaporte30:CartaPorte');
        $this->assertElementHasOrder($element, [
            'cartaporte30:Ubicaciones',
            'cartaporte30:Mercancias',
            'cartaporte30:FiguraTransporte',
        ]);
        $this->assertElementHasFixedAttributes($element, [
            'xmlns:cartaporte30' => 'http://www.sat.gob.mx/CartaPorte30',
            'xsi:schemaLocation' => 'http://www.sat.gob.mx/CartaPorte30'
                . ' http://www.sat.gob.mx/sitio_internet/cfd/CartaPorte/CartaPorte30.xsd',
            'Version' => '3.0',
        ]);
        $this->assertElementHasChildSingle($element, Ubicaciones::class);
        $this->assertElementHasChildSingle($element, Mercancias::class);
        $this->assertElementHasChildSingle($element, FiguraTransporte::class);
    }

    public function testUbicaciones(): void
    {
        $element = new Ubicaciones();
        $this->assertElementHasName($element, 'cartaporte30:Ubicaciones');
        $this->assertElementHasChildMultiple($element, Ubicacion::class);
    }

    public function testUbicacion(): void
    {
        $element = new Ubicacion();
        $this->assertElementHasName($element, 'cartaporte30:Ubicacion');
        $this->assertElementHasChildSingle($element, Domicilio::class);
    }

    public function testDomicilio(): void
    {
        $element = new Domicilio();
        $this->assertElementHasName($element, 'cartaporte30:Domicilio');
    }

    public function testMercancias(): void
    {
        $element = new Mercancias();
        $this->assertElementHasName($element, 'cartaporte30:Mercancias');
        $this->assertElementHasOrder($element, [
            'cartaporte30:Mercancia',
            'cartaporte30:Autotransporte',
            'cartaporte30:TransporteMaritimo',
            'cartaporte30:TransporteAereo',
            'cartaporte30:TransporteFerroviario',
        ]);
        $this->assertElementHasChildMultiple($element, Mercancia::class);
        $this->assertElementHasChildSingle($element, Autotransporte::class);
        $this->assertElementHasChildSingle($element, TransporteMaritimo::class);
        $this->assertElementHasChildSingle($element, TransporteAereo::class);
        $this->assertElementHasChildSingle($element, TransporteFerroviario::class);
    }

    public function testMercancia(): void
    {
        $element = new Mercancia();
        $this->assertElementHasName($element, 'cartaporte30:Mercancia');
        $this->assertElementHasOrder($element, [
            'cartaporte30:DocumentacionAduanera',
            'cartaporte30:GuiasIdentificacion',
            'cartaporte30:CantidadTransporta',
            'cartaporte30:DetalleMercancia',
        ]);
        $this->assertElementHasChildMultiple($element, DocumentacionAduanera::class);
        $this->assertElementHasChildMultiple($element, GuiasIdentificacion::class);
        $this->assertElementHasChildMultiple($element, CantidadTransporta::class);
        $this->assertElementHasChildSingle($element, DetalleMercancia::class);
    }

    public function testDocumentacionAduanera(): void
    {
        $element = new DocumentacionAduanera();
        $this->assertElementHasName($element, 'cartaporte30:DocumentacionAduanera');
    }

    public function testGuiasIdentificacion(): void
    {
        $element = new GuiasIdentificacion();
        $this->assertElementHasName($element, 'cartaporte30:GuiasIdentificacion');
    }

    public function testCantidadTransporta(): void
    {
        $element = new CantidadTransporta();
        $this->assertElementHasName($element, 'cartaporte30:CantidadTransporta');
    }

    public function testDetalleMercancia(): void
    {
        $element = new DetalleMercancia();
        $this->assertElementHasName($element, 'cartaporte30:DetalleMercancia');
    }

    public function testAutotransporte(): void
    {
        $element = new Autotransporte();
        $this->assertElementHasName($element, 'cartaporte30:Autotransporte');
        $this->assertElementHasOrder($element, [
            'cartaporte30:IdentificacionVehicular',
            'cartaporte30:Seguros',
            'cartaporte30:Remolques',
        ]);
        $this->assertElementHasChildSingle($element, IdentificacionVehicular::class);
        $this->assertElementHasChildSingle($element, Seguros::class);
        $this->assertElementHasChildSingle($element, Remolques::class);
    }

    public function testIdentificacionVehicular(): void
    {
        $element = new IdentificacionVehicular();
        $this->assertElementHasName($element, 'cartaporte30:IdentificacionVehicular');
    }

    public function testSeguros(): void
    {
        $element = new Seguros();
        $this->assertElementHasName($element, 'cartaporte30:Seguros');
    }

    public function testRemolques(): void
    {
        $element = new Remolques();
        $this->assertElementHasName($element, 'cartaporte30:Remolques');
        $this->assertElementHasChildMultiple($element, Remolque::class);
    }

    public function testRemolque(): void
    {
        $element = new Remolque();
        $this->assertElementHasName($element, 'cartaporte30:Remolque');
    }

    public function testTransporteMaritimo(): void
    {
        $element = new TransporteMaritimo();
        $this->assertElementHasName($element, 'cartaporte30:TransporteMaritimo');
        $this->assertElementHasChildMultiple($element, Contenedor::class);
        $this->assertElementHasChildSingle($element, RemolquesCCP::class);
    }

    public function testRemolquesCPP(): void
    {
        $element = new RemolquesCCP();
        $this->assertElementHasName($element, 'cartaporte30:RemolquesCCP');
        $this->assertElementHasChildMultiple($element, RemolqueCCP::class);
    }

    public function testRemolqueCPP(): void
    {
        $element = new RemolqueCCP();
        $this->assertElementHasName($element, 'cartaporte30:RemolqueCCP');
    }

    public function testTransporteAereo(): void
    {
        $element = new TransporteAereo();
        $this->assertElementHasName($element, 'cartaporte30:TransporteAereo');
    }

    public function testTransporteFerroviario(): void
    {
        $element = new TransporteFerroviario();
        $this->assertElementHasName($element, 'cartaporte30:TransporteFerroviario');
        $this->assertElementHasOrder($element, [
            'cartaporte30:DerechosDePaso',
            'cartaporte30:Carro',
        ]);
        $this->assertElementHasChildMultiple($element, DerechosDePaso::class);
        $this->assertElementHasChildMultiple($element, Carro::class);
    }

    public function testDerechosDePaso(): void
    {
        $element = new DerechosDePaso();
        $this->assertElementHasName($element, 'cartaporte30:DerechosDePaso');
    }

    public function testCarro(): void
    {
        $element = new Carro();
        $this->assertElementHasName($element, 'cartaporte30:Carro');
        $this->assertElementHasChildMultiple($element, Contenedor::class);
    }

    public function testContenedor(): void
    {
        $element = new Contenedor();
        $this->assertElementHasName($element, 'cartaporte30:Contenedor');
    }

    public function testFiguraTransporte(): void
    {
        $element = new FiguraTransporte();
        $this->assertElementHasName($element, 'cartaporte30:FiguraTransporte');
        $this->assertElementHasChildMultiple($element, TiposFigura::class);
    }

    public function testTiposFigura(): void
    {
        $element = new TiposFigura();
        $this->assertElementHasName($element, 'cartaporte30:TiposFigura');
        $this->assertElementHasOrder($element, [
            'cartaporte30:PartesTransporte',
            'cartaporte30:Domicilio',
        ]);
        $this->assertElementHasChildMultiple($element, PartesTransporte::class);
        $this->assertElementHasChildSingle($element, Domicilio::class);
    }

    public function testPartesTransporte(): void
    {
        $element = new PartesTransporte();
        $this->assertElementHasName($element, 'cartaporte30:PartesTransporte');
    }
}
