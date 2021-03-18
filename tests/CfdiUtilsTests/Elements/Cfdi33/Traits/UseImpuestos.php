<?php

namespace CfdiUtilsTests\Elements\Cfdi33\Traits;

use CfdiUtils\Elements\Cfdi33\Impuestos;
use CfdiUtils\Elements\Cfdi33\Traits\ImpuestosTrait;
use CfdiUtils\Elements\Common\AbstractElement;

final class UseImpuestos extends AbstractElement
{
    use ImpuestosTrait;

    public function getImpuestos(): Impuestos
    {
        return $this->helperGetOrAdd(new Impuestos());
    }

    public function getElementName(): string
    {
        return 'X';
    }
}
