<?php

namespace CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos;

use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\Validate\Asserts;

class DoctoRelacionado extends AbstractPagoValidator
{
    /** @var DoctoRelacionado\AbstractDoctoRelacionadoValidator[] */
    protected $validators;

    public function __construct()
    {
        $this->validators = $this->createValidators();
    }

    /**
     * @return DoctoRelacionado\AbstractDoctoRelacionadoValidator[]
     */
    public function getValidators()
    {
        return $this->validators;
    }

    /**
     * @return DoctoRelacionado\AbstractDoctoRelacionadoValidator[]
     */
    public function createValidators()
    {
        return [
            new DoctoRelacionado\Moneda(), // PAGO23
            new DoctoRelacionado\TipoCambioRequerido(), // PAGO24
            new DoctoRelacionado\TipoCambioValor(), // PAGO25
            new DoctoRelacionado\ImporteSaldoAnteriorValor(), // PAGO26
            new DoctoRelacionado\ImportePagadoValor(), // PAGO27
            new DoctoRelacionado\ImporteSaldoInsolutoValor(), // PAGO28
            new DoctoRelacionado\ImportesDecimales(), // PAGO29
            new DoctoRelacionado\ImportePagadoRequerido(), // PAGO30
            new DoctoRelacionado\NumeroParcialidadRequerido(), // PAGO31
            new DoctoRelacionado\ImporteSaldoAnteriorRequerido(), // PAGO32
            new DoctoRelacionado\ImporteSaldoInsolutoRequerido(), // PAGO33
        ];
    }

    // override registerInAssets to add validators instead of itself
    public function registerInAssets(Asserts $asserts)
    {
        foreach ($this->validators as $validator) {
            $validator->registerInAssets($asserts);
        }
    }

    public function validatePago(NodeInterface $pago): bool
    {
        // when validate pago perform validators on all documents
        $validators = $this->getValidators();
        foreach ($pago->searchNodes('pago10:DoctoRelacionado') as $index => $doctoRelacionado) {
            foreach ($validators as $validator) {
                $validator->setPago($pago);
                $validator->setIndex($index);
                $validator->validateDoctoRelacionado($doctoRelacionado);
            }
        }

        return true;
    }
}
