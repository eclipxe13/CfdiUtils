<?php

namespace CfdiUtils\Elements\Pagos20;

use CfdiUtils\Elements\Common\AbstractElement;

class DoctoRelacionado extends AbstractElement {

    public function getElementName(): string {
        return 'pagos20:DoctoRelacionado';
    }

    public function getChildrenOrder(): array {
        return [
            'pagos20:ImpuestosDR',
            'pagos20:TrasladosDR'];
    }

    public function getImpuestosDR(): ImpuestosDR {
        return $this->helperGetOrAdd(new ImpuestosDR());
    }

    public function addImpuestosDR(array $attributes = []): ImpuestosDR {
        $subject = $this->getImpuestosDR();
        $subject->addAttributes($attributes);
        return $subject;
    }

    public function getTrasladosDR(): TrasladosDR {
        return $this->helperGetOrAdd(new TrasladosDR());
    }

    public function addTrasladosDR(array $attributes = []): TrasladosDR {
        $subject = $this->getTrasladosDR();
        $subject->addAttributes($attributes);
        return $subject;
    }

}
