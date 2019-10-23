<?php

namespace CfdiUtils\Elements\Dividendos10;

use CfdiUtils\Elements\Common\AbstractElement;

class Dividendos extends AbstractElement
{
    public function getElementName(): string
    {
        return 'dividendos:Dividendos';
    }

    public function getDividOUtil(): DividOUtil
    {
        return $this->helperGetOrAdd(new DividOUtil());
    }

    public function addDividOUtil(array $attributes = []): DividOUtil
    {
        $dividOUtil = $this->getDividOUtil();
        $dividOUtil->addAttributes($attributes);
        return $dividOUtil;
    }

    public function getRemanente(): Remanente
    {
        return $this->helperGetOrAdd(new Remanente());
    }

    public function addRemanente(array $attributes = []): Remanente
    {
        $remanente = $this->getRemanente();
        $remanente->addAttributes($attributes);
        return $remanente;
    }

    public function getChildrenOrder(): array
    {
        return [
            'dividendos:DividOUtil',
            'dividendos:Remanente',
        ];
    }

    public function getFixedAttributes(): array
    {
        return [
            'xmlns:dividendos' => 'http://www.sat.gob.mx/esquemas/retencionpago/1/dividendos',
            'xsi:schemaLocation' => vsprintf('%s %s', [
                'http://www.sat.gob.mx/esquemas/retencionpago/1/dividendos',
                'http://www.sat.gob.mx/esquemas/retencionpago/1/dividendos/dividendos.xsd',
            ]),
            'Version' => '1.0',
        ];
    }
}
