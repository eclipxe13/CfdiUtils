<?php

namespace CfdiUtils\Elements\Pagos20;

use CfdiUtils\Elements\Common\AbstractElement;

class ImpuestosP extends AbstractElement
{

    public function getElementName(): string
    {
        return 'pagos20:ImpuestosP';
    }

    public function getChildrenOrder(): array
    {
        return [
            'pagos20:RetencionesP',
            'pagos20:TrasladosP'];
    }

    public function getRetencionesP(): RetencionesP
    {
        return $this->helperGetOrAdd(new RetencionesP());
    }

    public function addRetencionesP(array $attributes = []): RetencionesP
    {
        $subject = $this->getRetencionesP();
        $subject->addAttributes($attributes);
        return $subject;
    }

    public function getTrasladosP(): TrasladosP
    {
        return $this->helperGetOrAdd(new TrasladosP());
    }

    public function addTrasladosP(array $attributes = []): TrasladosP
    {
        $subject = $this->getTrasladosP();
        $subject->addAttributes($attributes);
        return $subject;
    }
}
