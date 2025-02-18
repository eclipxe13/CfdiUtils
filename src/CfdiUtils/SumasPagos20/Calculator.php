<?php

namespace CfdiUtils\SumasPagos20;

use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\Nodes\Nodes;

class Calculator
{
    private int $paymentTaxesPrecision;

    private Currencies $currencies;

    public function __construct(int $paymentTaxesPrecision = 6, ?Currencies $currencies = null)
    {
        $this->setPaymentTaxesPrecision($paymentTaxesPrecision);
        $this->currencies = $currencies ?? new Currencies(['MXN' => 2, 'USD' => 2]);
    }

    public function getPaymentTaxesPrecision(): int
    {
        return $this->paymentTaxesPrecision;
    }

    public function setPaymentTaxesPrecision(int $paymentTaxesPrecision): void
    {
        $this->paymentTaxesPrecision = min(6, max(0, $paymentTaxesPrecision));
    }

    public function getCurrencies(): Currencies
    {
        return $this->currencies;
    }

    public function setCurrencies(Currencies $currencies): void
    {
        $this->currencies = $currencies;
    }

    public function calculate(NodeInterface $nodePagos): Pagos
    {
        $pagos = [];
        foreach ($nodePagos->searchNodes('pago20:Pago') as $nodePago) {
            $pagos[] = $this->buildPago($nodePago);
        }

        $totales = $this->buildTotales($pagos);
        return new Pagos($totales, ...$pagos);
    }

    private function buildPago(NodeInterface $nodePago): Pago
    {
        $sumMonto = new Decimal('0');
        $impuestos = new Impuestos();
        foreach ($nodePago->searchNodes('pago20:DoctoRelacionado') as $nodeDoctoRelacionado) {
            $doctoRelacionado = $this->buildDoctoRelacionado($nodeDoctoRelacionado);
            $sumMonto = $sumMonto->sum($doctoRelacionado->getImpPagado());
            $impuestos = $impuestos->aggregate($doctoRelacionado->getImpuestos());
        }
        $montoMinimo = $sumMonto->truncate($this->currencies->get($nodePago['MonedaP']));
        $monto = (isset($nodePago['Monto'])) ? new Decimal($nodePago['Monto']) : $montoMinimo;
        $impuestos = $impuestos->round($this->paymentTaxesPrecision);
        $tipoCambioP = new Decimal($nodePago['TipoCambioP']);
        return new Pago($monto, $montoMinimo, $tipoCambioP, $impuestos);
    }

    private function buildDoctoRelacionado(NodeInterface $nodeDoctoRelacionado): DoctoRelacionado
    {
        $equivalenciaDr = new Decimal($nodeDoctoRelacionado['EquivalenciaDR']);

        $impPagado = new Decimal($nodeDoctoRelacionado['ImpPagado']);
        $impPagado = $impPagado->divide($equivalenciaDr);

        $traslados = $this->processImpuestosTraslados(
            $equivalenciaDr,
            $nodeDoctoRelacionado->searchNodes('pago20:ImpuestosDR', 'pago20:TrasladosDR', 'pago20:TrasladoDR')
        );
        $retenciones = $this->processImpuestosRetenciones(
            $equivalenciaDr,
            $nodeDoctoRelacionado->searchNodes('pago20:ImpuestosDR', 'pago20:RetencionesDR', 'pago20:RetencionDR')
        );
        $impuestos = new Impuestos(...$traslados, ...$retenciones);

        return new DoctoRelacionado($impPagado, $impuestos);
    }

    /** @return list<Impuesto> */
    private function processImpuestosTraslados(Decimal $equivalenciaDr, Nodes $nodeImpuestos): array
    {
        $impuestos = [];
        foreach ($nodeImpuestos as $nodeImpuesto) {
            $impuesto = new Impuesto(
                'Traslado',
                $nodeImpuesto['ImpuestoDR'],
                $nodeImpuesto['TipoFactorDR'],
                $nodeImpuesto['TasaOCuotaDR'],
                new Decimal($nodeImpuesto['BaseDR']),
                new Decimal($nodeImpuesto['ImporteDR'])
            );
            $impuesto = $impuesto->divide($equivalenciaDr);
            $impuestos[] = $impuesto;
        }
        return $impuestos;
    }

    /** @return list<Impuesto> */
    private function processImpuestosRetenciones(Decimal $equivalenciaDr, Nodes $nodeImpuestos): array
    {
        $impuestos = [];
        foreach ($nodeImpuestos as $nodeImpuesto) {
            $impuesto = new Impuesto(
                'Retencion',
                $nodeImpuesto['ImpuestoDR'],
                '',
                '',
                new Decimal('0'),
                new Decimal($nodeImpuesto['ImporteDR'])
            );
            $impuesto = $impuesto->divide($equivalenciaDr);
            $impuestos[] = $impuesto;
        }
        return $impuestos;
    }

    /** @param Pago[] $pagos */
    private function buildTotales(array $pagos): Totales
    {
        $total = new Decimal('0');
        $impuestos = new Impuestos();
        foreach ($pagos as $pago) {
            $tipoCambioP = $pago->getTipoCambioP();
            $impuestos = $impuestos->aggregate($pago->getImpuestos()->multiply($tipoCambioP));
            $total = $total->sum($pago->getMonto()->multiply($tipoCambioP));
        }
        $impuestos = $impuestos->round(2); // MXN

        $retencionIva = $impuestos->find('Retencion', '002');
        $retencionIsr = $impuestos->find('Retencion', '001');
        $retencionIeps = $impuestos->find('Retencion', '003');
        $trasladoIva16 = $impuestos->find('Traslado', '002', 'Tasa', '0.160000');
        $trasladoIva08 = $impuestos->find('Traslado', '002', 'Tasa', '0.080000');
        $trasladoIva00 = $impuestos->find('Traslado', '002', 'Tasa', '0.000000');
        $trasladoIvaEx = $impuestos->find('Traslado', '002', 'Exento');

        return new Totales(
            $retencionIva ? $retencionIva->getImporte() : null,
            $retencionIsr ? $retencionIsr->getImporte() : null,
            $retencionIeps ? $retencionIeps->getImporte() : null,
            $trasladoIva16 ? $trasladoIva16->getBase() : null,
            $trasladoIva16 ? $trasladoIva16->getImporte() : null,
            $trasladoIva08 ? $trasladoIva08->getBase() : null,
            $trasladoIva08 ? $trasladoIva08->getImporte() : null,
            $trasladoIva00 ? $trasladoIva00->getBase() : null,
            $trasladoIva00 ? $trasladoIva00->getImporte() : null,
            $trasladoIvaEx ? $trasladoIvaEx->getBase() : null,
            $total->round(2) // MXN
        );
    }
}
