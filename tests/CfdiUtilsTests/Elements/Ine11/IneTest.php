<?php

namespace CfdiUtilsTests\Elements\Ine11;

use CfdiUtils\Elements\Ine11\Ine;
use CfdiUtilsTests\Elements\ElementTestCase;

final class IneTest extends ElementTestCase
{
    public function testDonatarias(): void
    {
        $element = new Ine();
        $this->assertElementHasName($element, 'ine:INE');
        $this->assertElementHasFixedAttributes($element, [
            'xmlns:ine' => 'http://www.sat.gob.mx/ine',
            'xsi:schemaLocation' => 'http://www.sat.gob.mx/ine'
                . ' http://www.sat.gob.mx/sitio_internet/cfd/ine/ine11.xsd',
            'Version' => '1.1',
        ]);
    }
}
