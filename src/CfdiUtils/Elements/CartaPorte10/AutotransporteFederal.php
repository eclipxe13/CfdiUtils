<?php

namespace CfdiUtils\Elements\CartaPorte10;

use CfdiUtils\Elements\Common\AbstractElement;

class AutotransporteFederal extends AbstractElement
{
    public function getElementName(): string
    {
        return 'cartaporte:AutotransporteFederal';
    }

    public function addIdentificacionVehicular(array $attributes = []): IdentificacionVehicular
    {
        $identificacionVehicular = new IdentificacionVehicular($attributes);
        $this->addChild($identificacionVehicular);

        return $identificacionVehicular;
    }

    public function getIdentificacionVehicular(): IdentificacionVehicular
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->helperGetOrAdd(new IdentificacionVehicular());
    }

    public function addRemolques(array $attributes = []): Remolques
    {
        $remolques = new Remolques($attributes);
        $this->addChild($remolques);

        return $remolques;
    }

    public function getRemolques(): Remolques
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->helperGetOrAdd(new Remolques());
    }
}
