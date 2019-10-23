<?php

namespace CfdiUtils\Validate\Cfdi33\RecepcionPagos;

use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\Validate\Asserts;
use CfdiUtils\Validate\Cfdi33\Abstracts\AbstractRecepcionPagos10;
use CfdiUtils\Validate\Status;

/**
 * Conceptos
 * En un CFDI de recepción de pagos el Concepto del CFDI debe tener datos fijos,
 * puede ver el problema específico en la explicación del issue
 *
 * - PAGCON01: Se debe usar el concepto predefinido (CRP107 - CRP121)
 */
class Conceptos extends AbstractRecepcionPagos10
{
    const REQUIRED_CLAVEPRODSERV = '84111506';

    const REQUIRED_CANTIDAD = '1';

    const REQUIRED_CLAVEUNIDAD = 'ACT';

    const REQUIRED_DESCRIPCION = 'Pago';

    const REQUIRED_VALORUNITARIO = '0';

    const REQUIRED_IMPORTE = '0';

    public function validateRecepcionPagos(NodeInterface $comprobante, Asserts $asserts)
    {
        $assert = $asserts->put('PAGCON01', 'Se debe usar el concepto predefinido (CRP107 - CRP121)');
        // get conceptos
        try {
            $this->checkConceptos($comprobante);
        } catch (\Exception $exception) {
            $assert->setStatus(Status::error(), $exception->getMessage());
            return;
        }
        $assert->setStatus(Status::ok());
    }

    private function checkConceptos(NodeInterface $comprobante)
    {
        $conceptos = $comprobante->searchNode('cfdi:Conceptos');
        if (null === $conceptos) {
            throw new \Exception('No se encontró el nodo Conceptos');
        }
        // check conceptos count
        $conceptosCount = $conceptos->children()->count();
        if (1 !== $conceptosCount) {
            throw new \Exception(
                sprintf('Se esperaba encontrar un solo hijo de conceptos, se encontraron %s', $conceptosCount)
            );
        }
        // check it contains a Concepto
        $concepto = $conceptos->searchNode('cfdi:Concepto');
        if (null === $concepto) {
            throw new \Exception('No se encontró el nodo Concepto');
        }
        // check concepto does not have any children
        $conceptoCount = $concepto->children()->count();
        if (0 !== $conceptoCount) {
            throw new \Exception(
                sprintf('Se esperaba encontrar ningún hijo de concepto, se encontraron %s', $conceptoCount)
            );
        }
        if (static::REQUIRED_CLAVEPRODSERV !== $concepto['ClaveProdServ']) {
            throw new \Exception(sprintf(
                'La clave del producto o servicio debe ser "%s" y se registró "%s"',
                static::REQUIRED_CLAVEPRODSERV,
                $concepto['ClaveProdServ']
            ));
        }
        if ($concepto->offsetExists('NoIdentificacion')) {
            throw new \Exception('No debe existir el número de identificación');
        }
        if (static::REQUIRED_CANTIDAD !== $concepto['Cantidad']) {
            throw new \Exception(sprintf(
                'La cantidad debe ser "%s" y se registró "%s"',
                static::REQUIRED_CANTIDAD,
                $concepto['Cantidad']
            ));
        }
        if (static::REQUIRED_CLAVEUNIDAD !== $concepto['ClaveUnidad']) {
            throw new \Exception(sprintf(
                'La clave de unidad debe ser "%s" y se registró "%s"',
                static::REQUIRED_CLAVEUNIDAD,
                $concepto['ClaveUnidad']
            ));
        }
        if ($concepto->offsetExists('Unidad')) {
            throw new \Exception('No debe existir la unidad');
        }
        if (static::REQUIRED_DESCRIPCION !== $concepto['Descripcion']) {
            throw new \Exception(sprintf(
                'La descripción debe ser "%s" y se registró "%s"',
                static::REQUIRED_DESCRIPCION,
                $concepto['Descripcion']
            ));
        }
        if (static::REQUIRED_VALORUNITARIO !== $concepto['ValorUnitario']) {
            throw new \Exception(sprintf(
                'El valor unitario debe ser "%s" y se registró "%s"',
                static::REQUIRED_VALORUNITARIO,
                $concepto['ValorUnitario']
            ));
        }
        if (static::REQUIRED_IMPORTE !== $concepto['Importe']) {
            throw new \Exception(sprintf(
                'El valor unitario debe ser "%s" y se registró "%s"',
                static::REQUIRED_IMPORTE,
                $concepto['Importe']
            ));
        }
        if ($concepto->offsetExists('Descuento')) {
            throw new \Exception('No debe existir descuento');
        }
    }
}
