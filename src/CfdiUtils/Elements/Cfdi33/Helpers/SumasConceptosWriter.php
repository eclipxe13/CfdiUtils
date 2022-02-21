<?php

namespace CfdiUtils\Elements\Cfdi33\Helpers;

use CfdiUtils\Elements\Cfdi33\Comprobante;
use CfdiUtils\SumasConceptos\SumasConceptos;
use CfdiUtils\SumasConceptos\SumasConceptosWriter as BaseSumasConceptosWriter;

/**
 * @deprecated :3.0.0
 * @see \CfdiUtils\SumasConceptos\SumasConceptosWriter
 */
class SumasConceptosWriter extends BaseSumasConceptosWriter
{
    // @phpcs:ignore Generic.CodeAnalysis.UselessOverridingMethod.Found
    public function __construct(Comprobante $comprobante, SumasConceptos $sumas, int $precision = 6)
    {
        parent::__construct($comprobante, $sumas, $precision);
    }

    /** @codeCoverageIgnore */
    public function getComprobante(): Comprobante
    {
        $comprobante = parent::getComprobante();
        if (! $comprobante instanceof Comprobante) {
            throw new \LogicException(
                sprintf('Property comprobante (%s) is not %s', get_class($comprobante), Comprobante::class)
            );
        }

        return $comprobante;
    }
}
