<?php

namespace CfdiUtils\SumasPagos20;

use CfdiUtils\Elements\Pagos20\Pago as ElementPago;
use CfdiUtils\Elements\Pagos20\Pagos as ElementPagos;
use CfdiUtils\Nodes\NodeInterface;
use LogicException;

final class PagosWriter
{
    public function __construct(private ElementPagos $pagos)
    {
    }

    public static function calculateAndPut(ElementPagos $complementoPagos): Pagos
    {
        $calculator = new Calculator();
        $result = $calculator->calculate($complementoPagos);

        $writer = new self($complementoPagos);
        $writer->put($result);

        return $result;
    }

    public function put(Pagos $result): void
    {
        $this->writeTotales($result);
        $this->writePagos($result);
    }

    private function writeTotales(Pagos $pagoElement): void
    {
        $this->removeNodeIfExists($this->pagos, 'pago20:Totales');
        $totales = $pagoElement->getTotales();
        $this->pagos->addTotales([
            'MontoTotalPagos' => $totales->getTotal(),
            'TotalRetencionesIVA' => $totales->getRetencionIva(),
            'TotalRetencionesISR' => $totales->getRetencionIsr(),
            'TotalRetencionesIEPS' => $totales->getRetencionIeps(),
            'TotalTrasladosBaseIVA16' => $totales->getTrasladoIva16Base(),
            'TotalTrasladosImpuestoIVA16' => $totales->getTrasladoIva16Importe(),
            'TotalTrasladosBaseIVA8' => $totales->getTrasladoIva08Base(),
            'TotalTrasladosImpuestoIVA8' => $totales->getTrasladoIva08Importe(),
            'TotalTrasladosBaseIVA0' => $totales->getTrasladoIva00Base(),
            'TotalTrasladosImpuestoIVA0' => $totales->getTrasladoIva00Importe(),
            'TotalTrasladosBaseIVAExento' => $totales->getTrasladoIvaExento(),
        ]);
    }

    private function writePagos(Pagos $pagos): void
    {
        foreach ($this->pagos->searchNodes('pago20:Pago') as $index => $pagoElement) {
            if (! $pagoElement instanceof ElementPago) {
                throw new LogicException(
                    sprintf('Cannot work with a pago20:Pago of class %s', $pagoElement::class)
                );
            }
            $pagoData = $pagos->getPago($index);
            $this->writePago($pagoElement, $pagoData);
        }
    }

    public function writePago(ElementPago $pagoElement, Pago $pagoData): void
    {
        if (! isset($pagoElement['Monto'])) {
            $pagoElement['Monto'] = $pagoData->getMontoMinimo();
        }

        $this->removeNodeIfExists($pagoElement, 'pago20:ImpuestosP');

        $retenciones = $pagoData->getImpuestos()->getRetenciones();
        if ([] !== $retenciones) {
            $retencionesElement = $pagoElement->getImpuestosP()->getRetencionesP();
            $retencionesElement->clear();
            foreach ($retenciones as $retencion) {
                $retencionesElement->addRetencionP([
                    'ImpuestoP' => $retencion->getImpuesto(),
                    'ImporteP' => $retencion->getImporte(),
                ]);
            }
        }

        $traslados = $pagoData->getImpuestos()->getTraslados();
        if ([] !== $traslados) {
            $trasladosElement = $pagoElement->getImpuestosP()->getTrasladosP();
            $trasladosElement->clear();
            foreach ($traslados as $traslado) {
                $trasladosElement->addTrasladoP([
                    'ImpuestoP' => $traslado->getImpuesto(),
                    'TipoFactorP' => $traslado->getTipoFactor(),
                    'TasaOCuotaP' => ('Exento' === $traslado->getTipoFactor()) ? null : $traslado->getTasaCuota(),
                    'BaseP' => $traslado->getBase(),
                    'ImporteP' => ('Exento' === $traslado->getTipoFactor()) ? null : $traslado->getImporte(),
                ]);
            }
        }
    }

    private function removeNodeIfExists(NodeInterface $node, string ...$searchPath): void
    {
        $elements = $node->searchNodes(...$searchPath);
        foreach ($elements as $element) {
            $node->children()->remove($element);
        }
    }
}
