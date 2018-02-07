<?php
namespace CfdiUtils\Elements\Pagos10;

use CfdiUtils\Elements\Common\AbstractElement;

class Impuestos extends AbstractElement
{
    public function getElementName(): string
    {
        return 'pagos10:Impuestos';
    }

    public function getTraslados(): Traslados
    {
        return $this->helperGetOrAdd(new Traslados());
    }

    public function getRetenciones(): Retenciones
    {
        return $this->helperGetOrAdd(new Retenciones());
    }
}
