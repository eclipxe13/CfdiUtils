<?php

namespace CfdiUtilsTests\Elements\Donatarias11;

use CfdiUtils\Elements\Donatarias11\Donatarias;
use CfdiUtilsTests\Elements\ElementTestCase;

final class DonatariasTest extends ElementTestCase
{
    public function testDonatarias(): void
    {
        $element = new Donatarias();
        $this->assertElementHasName($element, 'donat:Donatarias');
        $this->assertElementHasFixedAttributes($element, [
            'xmlns:donat' => 'http://www.sat.gob.mx/donat',
            'xsi:schemaLocation' => 'http://www.sat.gob.mx/donat'
                . ' http://www.sat.gob.mx/sitio_internet/cfd/donat/donat11.xsd',
            'version' => '1.1',
        ]);
    }
}
