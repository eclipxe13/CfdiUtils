<?php

namespace CfdiUtils\Elements\Nomina12;

use CfdiUtils\Elements\Common\AbstractElement;

class Nomina extends AbstractElement
{
    public function getElementName(): string
    {
        return 'nomina12:Nomina';
    }

    public function getChildrenOrder(): array
    {
        return [
            'nomina12:Emisor',
            'nomina12:Receptor',
            'nomina12:Percepciones',
            'nomina12:Deducciones',
            'nomina12:OtrosPagos',
            'nomina12:Incapacidades',
        ];
    }

    public function getFixedAttributes(): array
    {
        return [
            'xmlns:nomina12' => 'http://www.sat.gob.mx/nomina12',
            'xsi:schemaLocation' => 'http://www.sat.gob.mx/nomina12'
                . ' http://www.sat.gob.mx/sitio_internet/cfd/nomina/nomina12.xsd',
            'Version' => '1.2',
        ];
    }

    public function getEmisor(): Emisor
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->helperGetOrAdd(new Emisor());
    }

    public function addEmisor(array $attributes = []): Emisor
    {
        $emisor = $this->getEmisor();
        $emisor->addAttributes($attributes);
        return $emisor;
    }

    public function getReceptor(): Receptor
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->helperGetOrAdd(new Receptor());
    }

    public function addReceptor(array $attributes = []): Receptor
    {
        $receptor = $this->getReceptor();
        $receptor->addAttributes($attributes);
        return $receptor;
    }

    public function getPercepciones(): Percepciones
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->helperGetOrAdd(new Percepciones());
    }

    public function addPercepciones(array $attributes = []): Percepciones
    {
        $percepciones = $this->getPercepciones();
        $percepciones->addAttributes($attributes);
        return $percepciones;
    }

    public function getDeducciones(): Deducciones
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->helperGetOrAdd(new Deducciones());
    }

    public function addDeducciones(array $attributes = []): Deducciones
    {
        $deducciones = $this->getDeducciones();
        $deducciones->addAttributes($attributes);
        return $deducciones;
    }

    public function getOtrosPagos(): OtrosPagos
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->helperGetOrAdd(new OtrosPagos());
    }

    public function addOtrosPagos(array $attributes = []): OtrosPagos
    {
        $otrosPagos = $this->getOtrosPagos();
        $otrosPagos->addAttributes($attributes);
        return $otrosPagos;
    }

    public function getIncapacidades(): Incapacidades
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->helperGetOrAdd(new Incapacidades());
    }

    public function addIncapacidades(array $attributes = []): Incapacidades
    {
        $incapacidades = $this->getIncapacidades();
        $incapacidades->addAttributes($attributes);
        return $incapacidades;
    }
}
