<?php

namespace CfdiUtilsTests\Elements\CartaPorte31;

use CfdiUtils\Elements\CartaPorte31\Autotransporte;
use CfdiUtils\Elements\CartaPorte31\CantidadTransporta;
use CfdiUtils\Elements\CartaPorte31\Carro;
use CfdiUtils\Elements\CartaPorte31\CartaPorte;
use CfdiUtils\Elements\CartaPorte31\ContenedorFerroviario;
use CfdiUtils\Elements\CartaPorte31\ContenedorMaritimo;
use CfdiUtils\Elements\CartaPorte31\DerechosDePaso;
use CfdiUtils\Elements\CartaPorte31\DetalleMercancia;
use CfdiUtils\Elements\CartaPorte31\DocumentacionAduanera;
use CfdiUtils\Elements\CartaPorte31\Domicilio;
use CfdiUtils\Elements\CartaPorte31\FiguraTransporte;
use CfdiUtils\Elements\CartaPorte31\GuiasIdentificacion;
use CfdiUtils\Elements\CartaPorte31\IdentificacionVehicular;
use CfdiUtils\Elements\CartaPorte31\Mercancia;
use CfdiUtils\Elements\CartaPorte31\Mercancias;
use CfdiUtils\Elements\CartaPorte31\PartesTransporte;
use CfdiUtils\Elements\CartaPorte31\RegimenAduaneroCCP;
use CfdiUtils\Elements\CartaPorte31\RegimenesAduaneros;
use CfdiUtils\Elements\CartaPorte31\Remolque;
use CfdiUtils\Elements\CartaPorte31\RemolqueCCP;
use CfdiUtils\Elements\CartaPorte31\Remolques;
use CfdiUtils\Elements\CartaPorte31\RemolquesCCP;
use CfdiUtils\Elements\CartaPorte31\Seguros;
use CfdiUtils\Elements\CartaPorte31\TiposFigura;
use CfdiUtils\Elements\CartaPorte31\TransporteAereo;
use CfdiUtils\Elements\CartaPorte31\TransporteFerroviario;
use CfdiUtils\Elements\CartaPorte31\TransporteMaritimo;
use CfdiUtils\Elements\CartaPorte31\Ubicacion;
use CfdiUtils\Elements\CartaPorte31\Ubicaciones;
use CfdiUtilsTests\Elements\ElementTestCase;

final class CartaPorteTest extends ElementTestCase
{
    public function testCartaPorte(): void
    {
        $element = new CartaPorte();
        $this->assertElementHasName($element, 'cartaporte31:CartaPorte');
        $this->assertElementHasOrder($element, [
            'cartaporte31:RegimenesAduaneros',
            'cartaporte31:Ubicaciones',
            'cartaporte31:Mercancias',
            'cartaporte31:FiguraTransporte',
        ]);
        $this->assertElementHasFixedAttributes($element, [
            'xmlns:cartaporte31' => 'http://www.sat.gob.mx/CartaPorte31',
            'xsi:schemaLocation' => 'http://www.sat.gob.mx/CartaPorte31'
                . ' http://www.sat.gob.mx/sitio_internet/cfd/CartaPorte/CartaPorte31.xsd',
            'Version' => '3.1',
        ]);
        $this->assertElementHasChildSingle($element, Ubicaciones::class);
        $this->assertElementHasChildSingle($element, Mercancias::class);
        $this->assertElementHasChildSingle($element, FiguraTransporte::class);
    }

    public function testRegimenesAduaneros(): void
    {
        $element = new RegimenesAduaneros();
        $this->assertElementHasName($element, 'cartaporte31:RegimenesAduaneros');
        $this->assertElementHasChildMultiple($element, RegimenAduaneroCCP::class);
    }

    public function testRegimenAduaneroCCP(): void
    {
        $element = new RegimenAduaneroCCP();
        $this->assertElementHasName($element, 'cartaporte31:RegimenAduaneroCCP');
    }

    public function testUbicaciones(): void
    {
        $element = new Ubicaciones();
        $this->assertElementHasName($element, 'cartaporte31:Ubicaciones');
        $this->assertElementHasChildMultiple($element, Ubicacion::class);
    }

    public function testUbicacion(): void
    {
        $element = new Ubicacion();
        $this->assertElementHasName($element, 'cartaporte31:Ubicacion');
        $this->assertElementHasChildSingle($element, Domicilio::class);
    }

    public function testDomicilio(): void
    {
        $element = new Domicilio();
        $this->assertElementHasName($element, 'cartaporte31:Domicilio');
    }

    public function testMercancias(): void
    {
        $element = new Mercancias();
        $this->assertElementHasName($element, 'cartaporte31:Mercancias');
        $this->assertElementHasOrder($element, [
            'cartaporte31:Mercancia',
            'cartaporte31:Autotransporte',
            'cartaporte31:TransporteMaritimo',
            'cartaporte31:TransporteAereo',
            'cartaporte31:TransporteFerroviario',
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
        $this->assertElementHasName($element, 'cartaporte31:Mercancia');
        $this->assertElementHasOrder($element, [
            'cartaporte31:DocumentacionAduanera',
            'cartaporte31:GuiasIdentificacion',
            'cartaporte31:CantidadTransporta',
            'cartaporte31:DetalleMercancia',
        ]);
        $this->assertElementHasChildMultiple($element, DocumentacionAduanera::class);
        $this->assertElementHasChildMultiple($element, GuiasIdentificacion::class);
        $this->assertElementHasChildMultiple($element, CantidadTransporta::class);
        $this->assertElementHasChildSingle($element, DetalleMercancia::class);
    }

    public function testDocumentacionAduanera(): void
    {
        $element = new DocumentacionAduanera();
        $this->assertElementHasName($element, 'cartaporte31:DocumentacionAduanera');
    }

    public function testGuiasIdentificacion(): void
    {
        $element = new GuiasIdentificacion();
        $this->assertElementHasName($element, 'cartaporte31:GuiasIdentificacion');
    }

    public function testCantidadTransporta(): void
    {
        $element = new CantidadTransporta();
        $this->assertElementHasName($element, 'cartaporte31:CantidadTransporta');
    }

    public function testDetalleMercancia(): void
    {
        $element = new DetalleMercancia();
        $this->assertElementHasName($element, 'cartaporte31:DetalleMercancia');
    }

    public function testAutotransporte(): void
    {
        $element = new Autotransporte();
        $this->assertElementHasName($element, 'cartaporte31:Autotransporte');
        $this->assertElementHasOrder($element, [
            'cartaporte31:IdentificacionVehicular',
            'cartaporte31:Seguros',
            'cartaporte31:Remolques',
        ]);
        $this->assertElementHasChildSingle($element, IdentificacionVehicular::class);
        $this->assertElementHasChildSingle($element, Seguros::class);
        $this->assertElementHasChildSingle($element, Remolques::class);
    }

    public function testIdentificacionVehicular(): void
    {
        $element = new IdentificacionVehicular();
        $this->assertElementHasName($element, 'cartaporte31:IdentificacionVehicular');
    }

    public function testSeguros(): void
    {
        $element = new Seguros();
        $this->assertElementHasName($element, 'cartaporte31:Seguros');
    }

    public function testRemolques(): void
    {
        $element = new Remolques();
        $this->assertElementHasName($element, 'cartaporte31:Remolques');
        $this->assertElementHasChildMultiple($element, Remolque::class);
    }

    public function testRemolque(): void
    {
        $element = new Remolque();
        $this->assertElementHasName($element, 'cartaporte31:Remolque');
    }

    public function testTransporteMaritimo(): void
    {
        $element = new TransporteMaritimo();
        $this->assertElementHasName($element, 'cartaporte31:TransporteMaritimo');
        $this->assertElementHasChildMultiple($element, ContenedorMaritimo::class, 'Contenedor');
    }

    public function testContenedorMaritimo(): void
    {
        $element = new ContenedorMaritimo();
        $this->assertElementHasName($element, 'cartaporte31:Contenedor');
        $this->assertElementHasChildSingle($element, RemolquesCCP::class);
    }

    public function testRemolquesCPP(): void
    {
        $element = new RemolquesCCP();
        $this->assertElementHasName($element, 'cartaporte31:RemolquesCCP');
        $this->assertElementHasChildMultiple($element, RemolqueCCP::class);
    }

    public function testRemolqueCPP(): void
    {
        $element = new RemolqueCCP();
        $this->assertElementHasName($element, 'cartaporte31:RemolqueCCP');
    }

    public function testTransporteAereo(): void
    {
        $element = new TransporteAereo();
        $this->assertElementHasName($element, 'cartaporte31:TransporteAereo');
    }

    public function testTransporteFerroviario(): void
    {
        $element = new TransporteFerroviario();
        $this->assertElementHasName($element, 'cartaporte31:TransporteFerroviario');
        $this->assertElementHasOrder($element, [
            'cartaporte31:DerechosDePaso',
            'cartaporte31:Carro',
        ]);
        $this->assertElementHasChildMultiple($element, DerechosDePaso::class);
        $this->assertElementHasChildMultiple($element, Carro::class);
    }

    public function testDerechosDePaso(): void
    {
        $element = new DerechosDePaso();
        $this->assertElementHasName($element, 'cartaporte31:DerechosDePaso');
    }

    public function testCarro(): void
    {
        $element = new Carro();
        $this->assertElementHasName($element, 'cartaporte31:Carro');
        $this->assertElementHasChildMultiple($element, ContenedorFerroviario::class, 'Contenedor');
    }

    public function testContenedorFerroviario(): void
    {
        $element = new ContenedorFerroviario();
        $this->assertElementHasName($element, 'cartaporte31:Contenedor');
    }

    public function testFiguraTransporte(): void
    {
        $element = new FiguraTransporte();
        $this->assertElementHasName($element, 'cartaporte31:FiguraTransporte');
        $this->assertElementHasChildMultiple($element, TiposFigura::class);
    }

    public function testTiposFigura(): void
    {
        $element = new TiposFigura();
        $this->assertElementHasName($element, 'cartaporte31:TiposFigura');
        $this->assertElementHasOrder($element, [
            'cartaporte31:PartesTransporte',
            'cartaporte31:Domicilio',
        ]);
        $this->assertElementHasChildMultiple($element, PartesTransporte::class);
        $this->assertElementHasChildSingle($element, Domicilio::class);
    }

    public function testPartesTransporte(): void
    {
        $element = new PartesTransporte();
        $this->assertElementHasName($element, 'cartaporte31:PartesTransporte');
    }
}
