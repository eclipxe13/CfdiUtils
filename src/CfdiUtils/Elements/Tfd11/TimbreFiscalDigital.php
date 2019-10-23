<?php

namespace CfdiUtils\Elements\Tfd11;

use CfdiUtils\Elements\Common\AbstractElement;

class TimbreFiscalDigital extends AbstractElement
{
    public function getElementName(): string
    {
        return 'tfd:TimbreFiscalDigital';
    }

    public function getFixedAttributes(): array
    {
        return [
            'xmlns:tfd' => 'http://www.sat.gob.mx/TimbreFiscalDigital',
            'xsi:schemaLocation' => 'http://www.sat.gob.mx/TimbreFiscalDigital'
                . ' http://www.sat.gob.mx/sitio_internet/cfd/TimbreFiscalDigital/TimbreFiscalDigitalv11.xsd',
            'Version' => '1.1',
        ];
    }
}
