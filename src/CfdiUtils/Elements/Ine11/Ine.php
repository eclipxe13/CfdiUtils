<?php

namespace CfdiUtils\Elements\Ine11;

use CfdiUtils\Elements\Common\AbstractElement;

class Ine extends AbstractElement
{
    public function getElementName(): string
    {
        return 'ine:INE';
    }

    public function getFixedAttributes(): array
    {
        return [
            'xmlns:ine' => 'http://www.sat.gob.mx/ine',
            'xsi:schemaLocation' => 'http://www.sat.gob.mx/ine'
                . ' http://www.sat.gob.mx/sitio_internet/cfd/ine/ine11.xsd',
            'Version' => '1.1',
        ];
    }
}
