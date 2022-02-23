<?php

namespace CfdiUtils\Elements\Pagos20;

use CfdiUtils\Elements\Common\AbstractElement;

class ImpuestosDR extends AbstractElement
{
    public function getElementName(): string
    {
        return 'pago20:ImpuestosDR';
    }

    public function getChildrenOrder(): array
    {
        return [
        'pago20:RetencionesDR',
        'pago20:TrasladosDR',
        ];
    }

    public function getRetencionesDR(): RetencionesDR
    {
        return $this->helperGetOrAdd(new RetencionesDR());
    }

    public function addRetencionesDR(array $attributes = []): RetencionesDR
    {
        $subject = $this->getRetencionesDR();
        $subject->addAttributes($attributes);
        return $subject;
    }

    public function getTrasladosDR(): TrasladosDR
    {
        return $this->helperGetOrAdd(new TrasladosDR());
    }

    public function addTrasladosDR(array $attributes = []): TrasladosDR
    {
        $subject = $this->getTrasladosDR();
        $subject->addAttributes($attributes);
        return $subject;
    }
}
