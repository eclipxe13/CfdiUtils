<?php

namespace CfdiUtils\Elements\ConsumoDeCombustibles11;

use CfdiUtils\Elements\Common\AbstractElement;

class ConsumoDeCombustibles extends AbstractElement
{
    public function getElementName(): string
    {
        return 'consumodecombustibles11:ConsumoDeCombustibles';
    }

    public function getFixedAttributes(): array
    {
        return [
            'xmlns:consumodecombustibles11' => 'http://www.sat.gob.mx/ConsumoDeCombustibles11',
            'xsi:schemaLocation' => 'http://www.sat.gob.mx/ConsumoDeCombustibles11'
                . ' http://www.sat.gob.mx/sitio_internet/cfd/consumodecombustibles/consumodeCombustibles11.xsd',
            'version' => '1.1',
        ];
    }

    public function getConceptos(): Conceptos
    {
        return $this->helperGetOrAdd(new Conceptos());
    }

    public function addConceptos(array $attributes = []): Conceptos
    {
        $subject = $this->getConceptos();
        $subject->addAttributes($attributes);
        return $subject;
    }
}
