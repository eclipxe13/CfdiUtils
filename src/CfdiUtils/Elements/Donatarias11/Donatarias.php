<?php

namespace CfdiUtils\Elements\Donatarias11;

use CfdiUtils\Elements\Common\AbstractElement;

class Donatarias extends AbstractElement
{
    public function getElementName(): string
    {
        return 'donat:Donatarias';
    }

    public function getFixedAttributes(): array
    {
        return [
            'xmlns:donat' => 'http://www.sat.gob.mx/donat',
            'xsi:schemaLocation' => 'http://www.sat.gob.mx/donat'
                . ' http://www.sat.gob.mx/sitio_internet/cfd/donat/donat11.xsd',
            'version' => '1.1',
        ];
    }
}
