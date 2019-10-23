<?php

namespace CfdiUtils\Elements\Pagos10;

use CfdiUtils\Elements\Common\AbstractElement;

class Pagos extends AbstractElement
{
    public function addPago(array $attributes = []): Pago
    {
        $pago = new Pago($attributes);
        $this->addChild($pago);
        return $pago;
    }

    public function multiPago(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addPago($attributes);
        }
        return $this;
    }

    public function getElementName(): string
    {
        return 'pago10:Pagos';
    }

    public function getFixedAttributes(): array
    {
        return [
            'xmlns:pago10' => 'http://www.sat.gob.mx/Pagos',
            'xsi:schemaLocation' => 'http://www.sat.gob.mx/Pagos'
                . ' http://www.sat.gob.mx/sitio_internet/cfd/Pagos/Pagos10.xsd',
            'Version' => '1.0',
        ];
    }
}
