<?php

namespace CfdiUtils\Validate\Cfdi33\Standard;

use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\Validate\Asserts;
use CfdiUtils\Validate\Cfdi33\Abstracts\AbstractDiscoverableVersion33;
use CfdiUtils\Validate\Cfdi33\Utils\AssertFechaFormat;
use CfdiUtils\Validate\Status;

/**
 * FechaComprobante
 *
 * Valida que:
 * - FECHA01: La fecha del comprobante cumple con el formato
 * - FECHA02: La fecha existe en el comprobante y es mayor que 2017-07-01 y menor que el futuro
 *      - La fecha en el futuro se puede configurar a un valor determinado
 *      - La fecha en el futuro es por defecto el momento de validación más una tolerancia
 *      - La tolerancia puede ser configurada y es por defecto 300 segundos
 */
class FechaComprobante extends AbstractDiscoverableVersion33
{
    /** @var int|null */
    private $maximumDate;

    /** @var int Tolerancia en segundos */
    private $tolerance = 300;

    public function getMinimumDate(): int
    {
        return mktime(0, 0, 0, 7, 1, 2017);
    }

    public function getMaximumDate(): int
    {
        if (null === $this->maximumDate) {
            return time() + $this->getTolerance();
        }
        return $this->maximumDate;
    }

    public function setMaximumDate(int $maximumDate = null)
    {
        $this->maximumDate = $maximumDate;
    }

    public function getTolerance(): int
    {
        return $this->tolerance;
    }

    public function setTolerance(int $tolerance)
    {
        $this->tolerance = $tolerance;
    }

    public function validate(NodeInterface $comprobante, Asserts $asserts)
    {
        $fechaSource = $comprobante['Fecha'];
        $hasFormat = AssertFechaFormat::assertFormat($asserts, 'FECHA01', 'del comprobante', $fechaSource);
        $assertBetween = $asserts->put(
            'FECHA02',
            'La fecha existe en el comprobante y es mayor que 2017-07-01 y menor que el futuro'
        );
        if (! $hasFormat) {
            return;
        }

        $exists = $comprobante->offsetExists('Fecha');
        $testDate = ('' !== $fechaSource) ? strtotime($fechaSource) : 0;

        $minimumDate = $this->getMinimumDate();
        $maximumDate = $this->getMaximumDate();

        $assertBetween->setStatus(
            Status::when($testDate >= $minimumDate && $testDate <= $maximumDate),
            vsprintf('Fecha: "%s" (%s), Máxima: %s', [
                $fechaSource,
                ($exists) ? 'Existe' : 'No existe',
                date('Y-m-d H:i:s', $maximumDate),
            ])
        );
    }
}
