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

    /*
     * Constructors
     */

    public function __construct(NodeInterface $comprobante)
    {
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
        $this->total = $this->importes - $this->descuento + $this->impuestosTrasladados - $this->impuestosRetenidos;
    }

    private function addConcepto(NodeInterface $concepto)
    {
        $this->importes += (float) $concepto->searchAttribute('Importe');
        $this->descuento += (float) $concepto->searchAttribute('Descuento');

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
            $traslado->searchAttribute('Impuesto'),
            $traslado->searchAttribute('TipoFactor'),
            $traslado->searchAttribute('TasaOCuota')
        );
        if (! array_key_exists($key, $this->traslados)) {
            $this->traslados[$key] = [
                'Impuesto' => $traslado->searchAttribute('Impuesto'),
                'TipoFactor' => $traslado->searchAttribute('TipoFactor'),
                'TasaOCuota' => $traslado->searchAttribute('TasaOCuota'),
                'Importe' => 0.0,
            ];
        }
        $this->traslados[$key]['Importe'] += (float) $traslado->searchAttribute('Importe');
    }

    private function addRetencion(NodeInterface $retencion)
    {
        $key = $this->impuestoKey($retencion->searchAttribute('Impuesto'));
        if (! array_key_exists($key, $this->retenciones)) {
            $this->retenciones[$key] = [
                'Impuesto' => $retencion->searchAttribute('Impuesto'),
                'Importe' => 0.0,
            ];
        }
        $this->retenciones[$key]['Importe'] += (float) $retencion->searchAttribute('Importe');
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
}
