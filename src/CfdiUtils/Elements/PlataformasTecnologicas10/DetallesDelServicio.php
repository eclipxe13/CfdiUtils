<?php

namespace CfdiUtils\Elements\PlataformasTecnologicas10;

use CfdiUtils\Elements\Common\AbstractElement;

class DetallesDelServicio extends AbstractElement
{
    public function getElementName(): string
    {
        return 'plataformasTecnologicas:DetallesDelServicio';
    }

    public function getChildrenOrder(): array
    {
        return [
            'plataformasTecnologicas:ImpuestosTrasladadosdelServicio',
            'plataformasTecnologicas:ContribucionGubernamental',
            'plataformasTecnologicas:ComisionDelServicio',
        ];
    }

    public function getImpuestosTrasladadosdelServicio(): ImpuestosTrasladadosdelServicio
    {
        return $this->helperGetOrAdd(new ImpuestosTrasladadosdelServicio());
    }

    public function addImpuestosTrasladadosdelServicio(array $attributes = []): ImpuestosTrasladadosdelServicio
    {
        $subject = $this->getImpuestosTrasladadosdelServicio();
        $subject->addAttributes($attributes);
        return $subject;
    }

    public function getContribucionGubernamental(): ContribucionGubernamental
    {
        return $this->helperGetOrAdd(new ContribucionGubernamental());
    }

    public function addContribucionGubernamental(array $attributes = []): ContribucionGubernamental
    {
        $subject = $this->getContribucionGubernamental();
        $subject->addAttributes($attributes);
        return $subject;
    }

    public function getComisionDelServicio(): ComisionDelServicio
    {
        return $this->helperGetOrAdd(new ComisionDelServicio());
    }

    public function addComisionDelServicio(array $attributes = []): ComisionDelServicio
    {
        $subject = $this->getComisionDelServicio();
        $subject->addAttributes($attributes);
        return $subject;
    }
}
