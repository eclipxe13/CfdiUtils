<?php

namespace CfdiUtils\Elements\Cce11;

use CfdiUtils\Elements\Cce11\Traits\DomicilioTrait;
use CfdiUtils\Elements\Common\AbstractElement;

class Emisor extends AbstractElement
{
    use DomicilioTrait;

    public function getElementName(): string
    {
        return 'cce11:Emisor';
    }
}
