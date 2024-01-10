<?php

namespace CfdiUtils\Elements\Cce20;

use CfdiUtils\Elements\Common\AbstractElement;

class Domicilio extends AbstractElement
{
    public function getElementName(): string
    {
        return 'cce20:Domicilio';
    }
}
