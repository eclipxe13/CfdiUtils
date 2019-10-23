<?php

namespace CfdiUtils\Validate\Cfdi33\RecepcionPagos\Helpers;

use CfdiUtils\Nodes\NodeInterface;

trait CalculateDocumentAmountTrait
{
    public function calculateDocumentAmount(NodeInterface $doctoRelacionado, NodeInterface $pago): float
    {
        // el importe pagado es el que está en el documento
        if ($doctoRelacionado->offsetExists('ImpPagado')) {
            return (float) $doctoRelacionado['ImpPagado'];
        }

        // el importe pagado es el que está en el pago
        $doctosCount = $pago->searchNodes('pago10:DoctoRelacionado')->count();
        if (1 === $doctosCount && ! $doctoRelacionado->offsetExists('TipoCambioDR')) {
            return (float) $pago['Monto'];
        }

        // no hay importe pagado
        return 0.0;
    }
}
