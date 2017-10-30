<?php
namespace CfdiUtilsTests\Elements\Cce11\Traits;

use CfdiUtils\Elements\Cce11\Traits\DomicilioTrait;
use CfdiUtils\Elements\Common\AbstractElement;

class UseDomicilio extends AbstractElement
{
    use DomicilioTrait;

    public function getElementName(): string
    {
        return 'X';
    }
}
