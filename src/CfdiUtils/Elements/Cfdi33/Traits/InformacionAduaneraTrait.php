<?php

namespace CfdiUtils\Elements\Cfdi33\Traits;

use CfdiUtils\Elements\Cfdi33\InformacionAduanera;
use CfdiUtils\Nodes\NodeInterface;

trait InformacionAduaneraTrait
{
    /* This method comes from NodeInterface */
    abstract public function addChild(NodeInterface $node): NodeInterface;

    public function addInformacionAduanera(array $attributes = []): InformacionAduanera
    {
        $informacionAduanera = new InformacionAduanera($attributes);
        $this->addChild($informacionAduanera);
        return $informacionAduanera;
    }

    public function multiInformacionAduanera(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addInformacionAduanera($attributes);
        }
        return $this;
    }
}
