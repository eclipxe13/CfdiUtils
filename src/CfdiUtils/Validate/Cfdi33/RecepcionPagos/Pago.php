<?php

namespace CfdiUtils\Validate\Cfdi33\RecepcionPagos;

use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\Validate\Asserts;
use CfdiUtils\Validate\Cfdi33\Abstracts\AbstractRecepcionPagos10;
use CfdiUtils\Validate\Status;

/**
 * Pago - Valida los nodos de pago dentro del complemento de pagos
 *
 * Se generan mensajes de error en los pagos con clave:
 * PAGO??-XX donde ?? es el número de validación general y XX es el número del nodo con problemas
 *
 * Se generan mensajes de error en los documentos relacionados con clave:
 * PAGO??-XX-YY donde YY es el número del nodo con problemas
 */
class Pago extends AbstractRecepcionPagos10
{
    /** @var Asserts This is the asserts object used in the validation process */
    private $asserts;

    /** @var Pagos\AbstractPagoValidator[] */
    private $validators;

    /**
     * @param Pagos\AbstractPagoValidator[]|null $validators
     */
    public function __construct(array $validators = null)
    {
        if (null === $validators) {
            $validators = $this->createValidators();
        }
        $this->validators = $validators;
    }

    /**
     * @return Pagos\AbstractPagoValidator[]
     */
    public function createValidators(): array
    {
        return [
            new Pagos\Fecha(), // PAGO02
            new Pagos\FormaDePago(), // PAGO03
            new Pagos\MonedaPago(), // PAGO04
            new Pagos\TipoCambioExists(), // PAGO05
            new Pagos\TipoCambioValue(), // PAGO6
            new Pagos\MontoGreaterThanZero(), // PAGO07
            new Pagos\MontoDecimals(), // PAGO08
            new Pagos\MontoBetweenIntervalSumOfDocuments(), // PAGO09
            new Pagos\BancoOrdenanteRfcCorrecto(), // PAGO10
            new Pagos\BancoOrdenanteNombreRequerido(), // PAGO11
            new Pagos\BancoOrdenanteRfcProhibido(), // PAGO12
            new Pagos\CuentaOrdenanteProhibida(), // PAGO13
            new Pagos\CuentaOrdenantePatron(), // PAGO14
            new Pagos\BancoBeneficiarioRfcCorrecto(), // PAGO15
            new Pagos\BancoBeneficiarioRfcProhibido(), // PAGO16
            new Pagos\CuentaBeneficiariaProhibida(), // PAGO17
            new Pagos\CuentaBeneficiariaPatron(), // PAGO18
            new Pagos\TipoCadenaPagoProhibido(), // PAGO19
            new Pagos\TipoCadenaPagoCertificado(), // PAGO20
            new Pagos\TipoCadenaPagoCadena(), // PAGO21
            new Pagos\TipoCadenaPagoSello(), // PAGO22
            new Pagos\DoctoRelacionado(), // PAGO23 ... PAGO29
            new Pagos\MontoGreaterOrEqualThanSumOfDocuments(), // PAGO30
        ];
    }

    /**
     * @return Pagos\AbstractPagoValidator[]
     */
    public function getValidators()
    {
        return $this->validators;
    }

    public function validateRecepcionPagos(NodeInterface $comprobante, Asserts $asserts)
    {
        $this->asserts = $asserts;

        // create pago validators array
        $validators = $this->createValidators();

        // register pago validators array into asserts
        foreach ($validators as $validator) {
            $validator->registerInAssets($asserts);
        }

        // obtain the pago nodes
        $pagoNodes = $comprobante->searchNodes('cfdi:Complemento', 'pago10:Pagos', 'pago10:Pago');
        foreach ($pagoNodes as $index => $pagoNode) {
            // pass each pago node thru validators
            foreach ($validators as $validator) {
                try {
                    if (! $validator->validatePago($pagoNode)) {
                        throw new \Exception(
                            sprintf('The validation of pago %s %s return false', $index, get_class($validator))
                        );
                    }
                } catch (Pagos\DoctoRelacionado\ValidateDoctoException $exception) {
                    $this->setDoctoRelacionadoStatus(
                        $exception->getValidatorCode(),
                        $index,
                        $exception->getIndex(),
                        $exception->getStatus(),
                        $exception->getMessage()
                    );
                } catch (Pagos\ValidatePagoException $exception) {
                    $this->setPagoStatus(
                        $validator->getCode(),
                        $index,
                        $exception->getStatus(),
                        $exception->getMessage()
                    );
                }
            }
        }
    }

    private function setPagoStatus(string $code, int $index, Status $errorStatus, string $explanation = '')
    {
        $assert = $this->asserts->get($code);
        $assert->setStatus($errorStatus);
        $this->asserts->put(
            sprintf('%s-%02d', $assert->getCode(), $index),
            $assert->getTitle(),
            $errorStatus,
            $explanation
        );
    }

    private function setDoctoRelacionadoStatus(
        string $code,
        int $pagoIndex,
        int $doctoIndex,
        Status $errorStatus,
        string $explanation = ''
    ) {
        $assert = $this->asserts->get($code);
        $doctoCode = sprintf('%s-%02d-%02d', $assert->getCode(), $pagoIndex, $doctoIndex);
        $this->setPagoStatus($code, $pagoIndex, $errorStatus);
        $this->asserts->put(
            $doctoCode,
            $assert->getTitle(),
            $errorStatus,
            $explanation
        );
    }
}
