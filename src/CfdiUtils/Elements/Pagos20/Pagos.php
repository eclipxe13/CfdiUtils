<?php

namespace CfdiUtils\Elements\Pagos20;

use CfdiUtils\Elements\Common\AbstractElement;

class Pagos extends AbstractElement
{
    public function getElementName(): string
    {
        return 'pago20:Pagos';
    }

    public function getChildrenOrder(): array
    {
        return [
        'pago20:Totales',
        'pago20:Pago',
        ];
    }

    public function getFixedAttributes(): array
    {
        return [
            'xmlns:pago20' => 'http://www.sat.gob.mx/Pagos20',
            'xsi:schemaLocation' => 'http://www.sat.gob.mx/Pagos20'
                . ' http://www.sat.gob.mx/sitio_internet/cfd/Pagos/Pagos20.xsd',
            'Version' => '2.0',
        ];
    }

    public function getTotales(): Totales
    {
        return $this->helperGetOrAdd(new Totales());
    }

    public function addTotales(array $attributes = []): Totales
    {
        $subject = $this->getTotales();
        $subject->addAttributes($attributes);
        return $subject;
    }

    public function addPago(array $attributes = []): Pago
    {
        $subject = new Pago($attributes);
        $this->addChild($subject);
        return $subject;
    }

    public function multiPago(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addPago($attributes);
        }
        return $this;
    }
}
