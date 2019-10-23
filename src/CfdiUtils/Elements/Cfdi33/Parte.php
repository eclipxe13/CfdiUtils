<?php

namespace CfdiUtils\Elements\Cfdi33;

use CfdiUtils\Elements\Cfdi33\Traits\InformacionAduaneraTrait;
use CfdiUtils\Elements\Common\AbstractElement;

class Parte extends AbstractElement
{
    use InformacionAduaneraTrait;

    public function getElementName(): string
    {
        return 'cfdi:Parte';
    }
}
