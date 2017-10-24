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
    private $decimals;

    /**
     * Writer constructor.
     * @param Comprobante $comprobante
     * @param \CfdiUtils\SumasConceptos\SumasConceptos $sumas
     * @param int $decimals
     */
    public function __construct(Comprobante $comprobante, SumasConceptos $sumas, int $decimals = 6)
    {
        $this->comprobante = $comprobante;
        $this->sumas = $sumas;
        $this->decimals = $decimals;
    }

    public function put()
    {
        $this->putComprobanteSumas();
        $this->putImpuestosSumas();
    }

    public function putComprobanteSumas()
    {
        unset($this->comprobante['Descuento']);
        $this->comprobante['SubTotal'] = $this->format($this->sumas->getSubTotal());
        $this->comprobante['Total'] = $this->format($this->sumas->getTotal());
        if ($this->valueGreaterThanZero($this->sumas->getDescuento())) {
            $this->comprobante['Descuento'] = $this->format($this->sumas->getDescuento());
        }
    }

    public function putImpuestosSumas()
    {
        $impuestos = $this->comprobante->getImpuestos();
        $impuestos->clear();

        if ($this->valueGreaterThanZero($this->sumas->getImpuestosTrasladados())) {
            $impuestos['TotalImpuestosTrasladados'] = $this->format($this->sumas->getImpuestosTrasladados());
        }
        if ($this->valueGreaterThanZero($this->sumas->getImpuestosRetenidos())) {
            $impuestos['TotalImpuestosRetenidos'] = $this->format($this->sumas->getImpuestosRetenidos());
        }

        foreach ($this->sumas->getTraslados() as $impuesto) {
            $data = $impuesto;
            $data['Importe'] = $this->format($data['Importe']);
            $impuestos->getTraslados()->addTraslado($data);
        }

        foreach ($this->sumas->getRetenciones() as $impuesto) {
            $data = $impuesto;
            $data['Importe'] = $this->format($data['Importe']);
            $impuestos->getRetenciones()->addRetencion($data);
        }
    }

    private function valueGreaterThanZero(float $value)
    {
        return (round($value, $this->decimals) > 0);
    }

    public function format(float $number): string
    {
        return number_format($number, $this->decimals, '.', '');
    }
}
