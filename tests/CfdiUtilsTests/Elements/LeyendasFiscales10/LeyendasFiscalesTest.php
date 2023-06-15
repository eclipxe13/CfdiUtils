<?php

namespace CfdiUtilsTests\Elements\LeyendasFiscales10;

use CfdiUtils\Elements\LeyendasFiscales10\Leyenda;
use CfdiUtils\Elements\LeyendasFiscales10\LeyendasFiscales;
use CfdiUtilsTests\Elements\ElementTestCase;

final class LeyendasFiscalesTest extends ElementTestCase
{
    public function testLeyendasFiscales(): void
    {
        $element = new LeyendasFiscales();
        $this->assertElementHasName($element, 'leyendasFisc:LeyendasFiscales');
        $this->assertElementHasFixedAttributes($element, [
            'xmlns:leyendasFisc' => 'http://www.sat.gob.mx/leyendasFiscales',
            'xsi:schemaLocation' => 'http://www.sat.gob.mx/leyendasFiscales'
                . ' http://www.sat.gob.mx/sitio_internet/cfd/leyendasFiscales/leyendasFisc.xsd',
            'version' => '1.0',
        ]);
        $this->assertElementHasChildMultiple($element, Leyenda::class);
    }

    public function testLeyenda(): void
    {
        $element = new Leyenda();
        $this->assertElementHasName($element, 'leyendasFisc:Leyenda');
    }
}
