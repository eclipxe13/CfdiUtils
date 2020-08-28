<?php

namespace CfdiUtils\Elements\Nomina12;

use CfdiUtils\Elements\Common\AbstractElement;

class OtroPago extends AbstractElement
{
    public function getElementName(): string
    {
        return 'nomina12:OtroPago';
    }

    public function getChildrenOrder(): array
    {
        return ['nomina12:SubsidioAlEmpleo', 'nomina12:CompensacionSaldosAFavor'];
    }

    public function getSubsidioAlEmpleo(): SubsidioAlEmpleo
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->helperGetOrAdd(new SubsidioAlEmpleo());
    }

    public function addSubsidioAlEmpleo(array $attributes = []): SubsidioAlEmpleo
    {
        $subsidioAlEmpleo = $this->getSubsidioAlEmpleo();
        $subsidioAlEmpleo->addAttributes($attributes);
        return $subsidioAlEmpleo;
    }

    public function getCompensacionSaldosAFavor(): CompensacionSaldosAFavor
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->helperGetOrAdd(new CompensacionSaldosAFavor());
    }

    public function addCompensacionSaldosAFavor(array $attributes = []): CompensacionSaldosAFavor
    {
        $compensacionSaldosAFavor = $this->getCompensacionSaldosAFavor();
        $compensacionSaldosAFavor->addAttributes($attributes);
        return $compensacionSaldosAFavor;
    }
}
