<?php

namespace CfdiUtils\Elements\Cce11\Traits;

use CfdiUtils\Elements\Cce11\Domicilio;
use CfdiUtils\Elements\Common\ElementInterface;

trait DomicilioTrait
{
    /* This method comes from AbstractElement */
    abstract protected function helperGetOrAdd(ElementInterface $element);

    public function getDomicilio(): Domicilio
    {
        return $this->helperGetOrAdd(new Domicilio());
    }

    public function addDomicilio(array $attributes = []): Domicilio
    {
        $Domicilio = $this->getDomicilio();
        $Domicilio->addAttributes($attributes);
        return $Domicilio;
    }
}
