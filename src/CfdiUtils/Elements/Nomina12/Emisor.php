<?php

namespace CfdiUtils\Elements\Nomina12;

use CfdiUtils\Elements\Common\AbstractElement;

class Emisor extends AbstractElement
{
    public function getElementName(): string
    {
        return 'nomina12:Emisor';
    }

    public function getEntidadSNCF(): EntidadSNCF
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->helperGetOrAdd(new EntidadSNCF());
    }

    public function addEntidadSNCF(array $attributes = []): EntidadSNCF
    {
        $entidadSncf = $this->getEntidadSNCF();
        $entidadSncf->addAttributes($attributes);
        return $entidadSncf;
    }
}
