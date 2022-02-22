<?php

namespace CfdiUtils\Elements\Pagos20;

use CfdiUtils\Elements\Common\AbstractElement;

class DoctoRelacionado extends AbstractElement
{
    public function getElementName(): string
    {
        return 'pago20:DoctoRelacionado';
    }

    public function getImpuestosDR(): ImpuestosDR
    {
        return $this->helperGetOrAdd(new ImpuestosDR());
    }

    public function addImpuestosDR(array $attributes = []): ImpuestosDR
    {
        $subject = $this->getImpuestosDR();
        $subject->addAttributes($attributes);
        return $subject;
    }
}
