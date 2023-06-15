<?php

namespace CfdiUtils\Elements\Iedu10;

use CfdiUtils\Elements\Common\AbstractElement;

class InstEducativas extends AbstractElement
{
    public function getElementName(): string
    {
        return 'iedu:instEducativas';
    }

    public function getFixedAttributes(): array
    {
        return [
            'xmlns:iedu' => 'http://www.sat.gob.mx/iedu',
            'xsi:schemaLocation' => 'http://www.sat.gob.mx/iedu'
                . ' http://www.sat.gob.mx/sitio_internet/cfd/iedu/iedu.xsd',
            'version' => '1.0',
        ];
    }
}
