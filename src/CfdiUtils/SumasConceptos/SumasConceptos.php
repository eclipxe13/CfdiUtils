<?php
namespace CfdiUtils\SumasConceptos;

use CfdiUtils\Nodes\NodeInterface;

class SumasConceptos
{
    /** @var float */
    private $importes = 0.0;
    /** @var float */
    private $descuento = 0.0;
    /** @var float */
    private $total;
    /** @var float */
    private $impuestosTrasladados;
    /** @var float */
    private $impuestosRetenidos;
    /** @var array */
    private $traslados = [];
    /** @var array */
    private $retenciones = [];
    /** @var int */
    private $precision;

    /*
     * Constructors
     */

    public function __construct(NodeInterface $comprobante, int $precision = 2)
    {
        $this->precision = $precision;
        $this->addComprobante($comprobante);
    }

    /*
     * Helper functions to populate the object
     */

    private function addComprobante(NodeInterface $comprobante)
    {
        $conceptos = $comprobante->searchNodes('cfdi:Conceptos', 'cfdi:Concepto');
        foreach ($conceptos as $concepto) {
            $this->addConcepto($concepto);
        }
        $this->impuestosTrasladados = (float) array_sum(array_column($this->traslados, 'Importe'));
        $this->impuestosRetenidos = (float) array_sum(array_column($this->retenciones, 'Importe'));

        $this->impuestosTrasladados = round($this->impuestosTrasladados, $this->precision);
        $this->impuestosRetenidos = round($this->impuestosRetenidos, $this->precision);
        $this->importes = round($this->importes, $this->precision);
        $this->descuento = round($this->descuento, $this->precision);

        $this->total = $this->importes - $this->descuento + $this->impuestosTrasladados - $this->impuestosRetenidos;
    }

    private function addConcepto(NodeInterface $concepto)
    {
        $this->importes += (float) $concepto['Importe'];
        $this->descuento += (float) $concepto['Descuento'];

        $traslados = $concepto->searchNodes('cfdi:Impuestos', 'cfdi:Traslados', 'cfdi:Traslado');
        foreach ($traslados as $traslado) {
            $this->addTraslado($traslado);
        }

        $retenciones = $concepto->searchNodes('cfdi:Impuestos', 'cfdi:Retenciones', 'cfdi:Retencion');
        foreach ($retenciones as $retencion) {
            $this->addRetencion($retencion);
        }
    }

    private function addTraslado(NodeInterface $traslado)
    {
        $key = $this->impuestoKey(
            $traslado['Impuesto'],
            $traslado['TipoFactor'],
            $traslado['TasaOCuota']
        );
        if (! array_key_exists($key, $this->traslados)) {
            $this->traslados[$key] = [
                'Impuesto' => $traslado['Impuesto'],
                'TipoFactor' => $traslado['TipoFactor'],
                'TasaOCuota' => $traslado['TasaOCuota'],
                'Importe' => 0.0,
            ];
        }
        $this->traslados[$key]['Importe'] += (float) $traslado['Importe'];
    }

    private function addRetencion(NodeInterface $retencion)
    {
        $key = $this->impuestoKey($retencion['Impuesto']);
        if (! array_key_exists($key, $this->retenciones)) {
            $this->retenciones[$key] = [
                'Impuesto' => $retencion['Impuesto'],
                'Importe' => 0.0,
            ];
        }
        $this->retenciones[$key]['Importe'] += (float) $retencion['Importe'];
    }

    public static function impuestoKey(string $impuesto, string $tipoFactor = '', string $tasaOCuota = ''): string
    {
        return implode(':', [$impuesto, $tipoFactor, $tasaOCuota]);
    }

    /*
     * Getters
     */

    public function getTotal(): float
    {
        return $this->total;
    }

    public function getSubTotal(): float
    {
        return $this->importes;
    }

    public function getDescuento(): float
    {
        return $this->descuento;
    }

    public function getTraslados(): array
    {
        return $this->traslados;
    }

    public function getRetenciones(): array
    {
        return $this->retenciones;
    }

    public function hasTraslados(): bool
    {
        return (count($this->traslados) > 0);
    }

    public function hasRetenciones(): bool
    {
        return (count($this->retenciones) > 0);
    }

    public function getImpuestosTrasladados(): float
    {
        return $this->impuestosTrasladados;
    }

    public function getImpuestosRetenidos(): float
    {
        return $this->impuestosRetenidos;
    }

    public function getPrecision(): int
    {
        return $this->precision;
    }
}
