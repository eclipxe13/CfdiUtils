<?php

namespace CfdiUtils\Elements\Cfdi33\Helpers;

use CfdiUtils\Elements\Cfdi33\Comprobante;
use CfdiUtils\SumasConceptos\SumasConceptos;

class SumasConceptosWriter
{
    /** @var Comprobante */
    private $comprobante;

    /** @var \CfdiUtils\SumasConceptos\SumasConceptos */
    private $sumas;

    /** @var int */
    private $precision;

    /**
     * Writer constructor.
     * @param Comprobante $comprobante
     * @param \CfdiUtils\SumasConceptos\SumasConceptos $sumas
     * @param int $precision
     */
    public function __construct(Comprobante $comprobante, SumasConceptos $sumas, int $precision = 6)
    {
        $this->comprobante = $comprobante;
        $this->sumas = $sumas;
        $this->precision = $precision;
    }

    public function put()
    {
        $this->putComprobanteSumas();
        $this->putImpuestosNode();
    }

    private function putComprobanteSumas()
    {
        $this->comprobante['SubTotal'] = $this->format($this->sumas->getSubTotal());
        $this->comprobante['Total'] = $this->format($this->sumas->getTotal());
        $this->comprobante['Descuento'] = $this->format($this->sumas->getDescuento());
        if (! $this->sumas->foundAnyConceptWithDiscount()
            && ! $this->valueGreaterThanZero($this->sumas->getDescuento())) {
            unset($this->comprobante['Descuento']);
        }
    }

    private function putImpuestosNode()
    {
        // obtain node reference
        $impuestos = $this->comprobante->getImpuestos();
        // if there is nothing to write then remove the children and exit
        if (! $this->sumas->hasTraslados() && ! $this->sumas->hasRetenciones()) {
            $this->comprobante->children()->remove($impuestos);
            return;
        }
        // clear previous values
        $impuestos->clear();
        // add traslados when needed
        if ($this->sumas->hasTraslados()) {
            $impuestos['TotalImpuestosTrasladados'] = $this->format($this->sumas->getImpuestosTrasladados());
            $impuestos->getTraslados()->multiTraslado(
                ...$this->getImpuestosContents($this->sumas->getTraslados())
            );
        }
        // add retenciones when needed
        if ($this->sumas->hasRetenciones()) {
            $impuestos['TotalImpuestosRetenidos'] = $this->format($this->sumas->getImpuestosRetenidos());
            $impuestos->getRetenciones()->multiRetencion(
                ...$this->getImpuestosContents($this->sumas->getRetenciones())
            );
        }
    }

    private function getImpuestosContents(array $impuestos): array
    {
        $return = [];
        foreach ($impuestos as $impuesto) {
            $impuesto['Importe'] = $this->format($impuesto['Importe']);
            $return[] = $impuesto;
        }
        return $return;
    }

    private function valueGreaterThanZero(float $value)
    {
        return (round($value, $this->precision) > 0);
    }

    public function format(float $number): string
    {
        return number_format($number, $this->precision, '.', '');
    }

    public function getComprobante(): Comprobante
    {
        return $this->comprobante;
    }

    public function getSumasConceptos(): SumasConceptos
    {
        return $this->sumas;
    }

    public function getPrecision(): int
    {
        return $this->precision;
    }
}
