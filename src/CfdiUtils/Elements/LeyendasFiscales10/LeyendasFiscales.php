<?php

namespace CfdiUtils\Elements\LeyendasFiscales10;

use CfdiUtils\Elements\Common\AbstractElement;

class LeyendasFiscales extends AbstractElement
{
    public function getElementName(): string
    {
        return 'leyendasFisc:LeyendasFiscales';
    }

    public function getFixedAttributes(): array
    {
        return [
            'xmlns:leyendasFisc' => 'http://www.sat.gob.mx/leyendasFiscales',
            'xsi:schemaLocation' => 'http://www.sat.gob.mx/leyendasFiscales'
                . ' http://www.sat.gob.mx/sitio_internet/cfd/leyendasFiscales/leyendasFisc.xsd',
            'version' => '1.0',
        ];
    }

    public function addLeyenda(array $attributes = []): Leyenda
    {
        $subject = new Leyenda($attributes);
        $this->addChild($subject);
        return $subject;
    }

    public function multiLeyenda(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addLeyenda($attributes);
        }
        return $this;
    }
}
