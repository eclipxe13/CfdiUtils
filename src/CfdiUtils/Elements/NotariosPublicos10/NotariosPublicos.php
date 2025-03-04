<?php

namespace CfdiUtils\Elements\NotariosPublicos10;

use CfdiUtils\Elements\Common\AbstractElement;

class NotariosPublicos extends AbstractElement
{
    public function getElementName(): string
    {
        return 'notariospublicos:NotariosPublicos';
    }

    public function getChildrenOrder(): array
    {
        return [
            'notariospublicos:DescInmuebles',
            'notariospublicos:DatosOperacion',
            'notariospublicos:DatosNotario',
            'notariospublicos:DatosEnajenante',
            'notariospublicos:DatosAdquiriente',
        ];
    }

    public function getFixedAttributes(): array
    {
        return [
            'xmlns:notariospublicos' => 'http://www.sat.gob.mx/notariospublicos',
            'xsi:schemaLocation' => 'http://www.sat.gob.mx/notariospublicos'
                . ' http://www.sat.gob.mx/sitio_internet/cfd/notariospublicos/notariospublicos.xsd',
            'Version' => '1.0',
        ];
    }

    public function getDescInmuebles(): DescInmuebles
    {
        return $this->helperGetOrAdd(new DescInmuebles());
    }

    public function addDescInmuebles(array $attributes = []): DescInmuebles
    {
        $subject = $this->getDescInmuebles();
        $subject->addAttributes($attributes);
        return $subject;
    }

    public function getDatosOperacion(): DatosOperacion
    {
        return $this->helperGetOrAdd(new DatosOperacion());
    }

    public function addDatosOperacion(array $attributes = []): DatosOperacion
    {
        $subject = $this->getDatosOperacion();
        $subject->addAttributes($attributes);
        return $subject;
    }

    public function getDatosNotario(): DatosNotario
    {
        return $this->helperGetOrAdd(new DatosNotario());
    }

    public function addDatosNotario(array $attributes = []): DatosNotario
    {
        $subject = $this->getDatosNotario();
        $subject->addAttributes($attributes);
        return $subject;
    }

    public function getDatosEnajenante(): DatosEnajenante
    {
        return $this->helperGetOrAdd(new DatosEnajenante());
    }

    public function addDatosEnajenante(array $attributes = []): DatosEnajenante
    {
        $subject = $this->getDatosEnajenante();
        $subject->addAttributes($attributes);
        return $subject;
    }

    public function getDatosAdquiriente(): DatosAdquiriente
    {
        return $this->helperGetOrAdd(new DatosAdquiriente());
    }

    public function addDatosAdquiriente(array $attributes = []): DatosAdquiriente
    {
        $subject = $this->getDatosAdquiriente();
        $subject->addAttributes($attributes);
        return $subject;
    }
}
