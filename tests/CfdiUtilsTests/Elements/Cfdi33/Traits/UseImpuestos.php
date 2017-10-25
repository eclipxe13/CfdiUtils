<?php
namespace CfdiUtilsTests\Elements\Cfdi33\Traits;

use CfdiUtils\Elements\Cfdi33\Traits\ImpuestosTrait;
use CfdiUtils\Elements\Common\AbstractElement;

class UseImpuestos extends AbstractElement
{
    use ImpuestosTrait;

    public function getElementName(): string
    {
        return 'X';
    }
}
