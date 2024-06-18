<?php

namespace CfdiUtils\Elements\CartaPorte31;

use CfdiUtils\Elements\Common\AbstractElement;

class Mercancia extends AbstractElement
{
    public function getElementName(): string
    {
        return 'cartaporte31:Mercancia';
    }

    public function getChildrenOrder(): array
    {
        return [
        'cartaporte31:DocumentacionAduanera',
        'cartaporte31:GuiasIdentificacion',
        'cartaporte31:CantidadTransporta',
        'cartaporte31:DetalleMercancia'];
    }

    public function addDocumentacionAduanera(array $attributes = []): DocumentacionAduanera
    {
        $subject = new DocumentacionAduanera($attributes);
        $this->addChild($subject);
        return $subject;
    }

    public function multiDocumentacionAduanera(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addDocumentacionAduanera($attributes);
        }
        return $this;
    }

    public function addGuiasIdentificacion(array $attributes = []): GuiasIdentificacion
    {
        $subject = new GuiasIdentificacion($attributes);
        $this->addChild($subject);
        return $subject;
    }

    public function multiGuiasIdentificacion(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addGuiasIdentificacion($attributes);
        }
        return $this;
    }

    public function addCantidadTransporta(array $attributes = []): CantidadTransporta
    {
        $subject = new CantidadTransporta($attributes);
        $this->addChild($subject);
        return $subject;
    }

    public function multiCantidadTransporta(array ...$elementAttributes): self
    {
        foreach ($elementAttributes as $attributes) {
            $this->addCantidadTransporta($attributes);
        }
        return $this;
    }

    public function getDetalleMercancia(): DetalleMercancia
    {
        return $this->helperGetOrAdd(new DetalleMercancia());
    }

    public function addDetalleMercancia(array $attributes = []): DetalleMercancia
    {
        $subject = $this->getDetalleMercancia();
        $subject->addAttributes($attributes);
        return $subject;
    }
}
