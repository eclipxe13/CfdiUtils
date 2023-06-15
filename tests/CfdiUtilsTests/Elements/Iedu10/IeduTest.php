<?php

namespace CfdiUtilsTests\Elements\Iedu10;

use CfdiUtils\Elements\Iedu10\InstEducativas;
use CfdiUtilsTests\Elements\ElementTestCase;

final class IeduTest extends ElementTestCase
{
    public function testInstEducativas(): void
    {
        $element = new InstEducativas();
        $this->assertElementHasName($element, 'iedu:instEducativas');
        $this->assertElementHasFixedAttributes($element, [
            'xmlns:iedu' => 'http://www.sat.gob.mx/iedu',
            'xsi:schemaLocation' => 'http://www.sat.gob.mx/iedu'
                . ' http://www.sat.gob.mx/sitio_internet/cfd/iedu/iedu.xsd',
            'version' => '1.0',
        ]);
    }
}
