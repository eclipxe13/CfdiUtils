<?php

namespace CfdiUtils\Utils;

use CfdiUtils\Nodes\NodeInterface;

/**
 * This utility class change the /pago20:Pagos/pago20:Pago/pago20:DoctoRelacionado@EquivalenciaDR attribute
 * from '1' to '1.0000000000' as defined by the rule CRP20277.
 *
 * La regla CRP20277 está definida en los Documentos técnicos del complemento de recepción de pagos 2.0, revisión B,
 * vigente a partir del 15 de enero de 2024, en la Matriz de errores, y dice:
 * Validación:
 *   Cuando existan operaciones con más de un Documento relacionado en donde al menos uno de ellos contenga
 *   la misma moneda que la del Pago, para la fórmula en el cálculo del margen de variación se deben
 *   considerar 10 decimales en la EquivalenciaDR cuando el valor sea 1.
 * Código de error:
 *   El campo EquivalenciaDR debe contener el valor "1.0000000000".
 * Esta regla cambia lo especificado en la regla CRP20238.
 *
 * @see http://omawww.sat.gob.mx/tramitesyservicios/Paginas/recepcion_de_pagos.htm
 */
final class Crp20277Fixer
{
    public static function staticFix(NodeInterface $complemento): void
    {
        $fixer = new self();
        $fixer->fixPagos($complemento);
    }

    public function fixPagos(NodeInterface $complemento): void
    {
        $pagos = $complemento->searchNodes('pago20:Pago');
        foreach ($pagos as $pago) {
            $this->fixPago($pago);
        }
    }

    public function fixPago(NodeInterface $pago): void
    {
        $doctoRelacionados = $pago->searchNodes('pago20:DoctoRelacionado');

        // más de un Documento relacionado
        if ($doctoRelacionados->count() < 2) {
            return;
        }

        // al menos uno de ellos contenga la misma moneda que la del Pago
        $hasDocumentsWithSameCurrency = false;
        foreach ($doctoRelacionados as $doctoRelacionado) {
            if ($doctoRelacionado['MonedaDR'] === $pago['MonedaP']) {
                $hasDocumentsWithSameCurrency = true;
                break;
            }
        }
        if (! $hasDocumentsWithSameCurrency) {
            return;
        }

        // se deben considerar 10 decimales en la EquivalenciaDR cuando el valor sea 1
        foreach ($doctoRelacionados as $doctoRelacionado) {
            if ('1' === $doctoRelacionado['EquivalenciaDR']) {
                $doctoRelacionado['EquivalenciaDR'] = '1.0000000000'; // CRP20277
            }
        }
    }
}
