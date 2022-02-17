<?php

namespace CfdiUtils\Elements\Pagos20;

use CfdiUtils\Elements\Common\AbstractElement;

class ImpuestosDR extends AbstractElement
{
    public function getElementName(): string
    {
        return 'pagos20:ImpuestosDR';
    }
    public function getChildrenOrder(): array
    {
        return [
'pagos20:RetencionesDR',
'pagos20:TrasladosDR'];
    }      public function addRetencionesDR(array $attributes = []): RetencionesDR
    {
        $subject = new RetencionesDR($attributes);
        $this->addChild($subject);
        return $subject;
    }

    public function multiRetencionesDR(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addRetencionesDR($attributes);
        }
        return $this;
    }   public function getTrasladosDR(): TrasladosDR
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