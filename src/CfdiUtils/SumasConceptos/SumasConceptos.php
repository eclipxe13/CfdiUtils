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

    /** @var float */
    private $localesImpuestosTrasladados;

    /** @var float */
    private $localesImpuestosRetenidos;

    /** @var array */
    private $localesTraslados = [];

    /** @var array */
    private $localesRetenciones = [];

    /** @var int */
    private $precision;

    /** @var bool */
    private $foundAnyConceptWithDiscount = false;

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

        $this->localesTraslados = $this->populateImpuestosLocales($comprobante, 'TrasladosLocales', 'Traslado');
        $this->localesImpuestosTrasladados = array_sum(array_column($this->localesTraslados, 'Importe'));
        $this->localesRetenciones = $this->populateImpuestosLocales($comprobante, 'RetencionesLocales', 'Retenido');
        $this->localesImpuestosRetenidos = array_sum(array_column($this->localesRetenciones, 'Importe'));

        $this->traslados = $this->roundImpuestosGroup($this->traslados);
        $this->retenciones = $this->roundImpuestosGroup($this->retenciones);
        $this->impuestosTrasladados = (float) array_sum(array_column($this->traslados, 'Importe'));
        $this->impuestosRetenidos = (float) array_sum(array_column($this->retenciones, 'Importe'));

        $this->impuestosTrasladados = round($this->impuestosTrasladados, $this->precision);
        $this->impuestosRetenidos = round($this->impuestosRetenidos, $this->precision);
        $this->importes = round($this->importes, $this->precision);
        $this->descuento = round($this->descuento, $this->precision);

        $this->total = round(array_sum([
            $this->importes,
            - $this->descuento,
            $this->impuestosTrasladados,
            - $this->impuestosRetenidos,
            $this->localesImpuestosTrasladados,
            - $this->localesImpuestosRetenidos,
        ]), $this->precision);
    }

    private function addConcepto(NodeInterface $concepto)
    {
        $this->importes += (float) $concepto['Importe'];
        if ($concepto->offsetExists('Descuento')) {
            $this->foundAnyConceptWithDiscount = true;
        }
        $this->descuento += (float) $concepto['Descuento'];

        $traslados = $concepto->searchNodes('cfdi:Impuestos', 'cfdi:Traslados', 'cfdi:Traslado');
        foreach ($traslados as $traslado) {
            if ('Exento' !== $traslado['TipoFactor']) {
                $this->addTraslado($traslado);
            }
        }

        $retenciones = $concepto->searchNodes('cfdi:Impuestos', 'cfdi:Retenciones', 'cfdi:Retencion');
        foreach ($retenciones as $retencion) {
            $this->addRetencion($retencion);
        }
    }

    private function populateImpuestosLocales(NodeInterface $comprobante, string $plural, string $singular): array
    {
        $locales = $comprobante->searchNodes('cfdi:Complemento', 'implocal:ImpuestosLocales', 'implocal:' . $plural);
        $list = [];
        foreach ($locales as $local) {
            $list[] = [
                'Impuesto' => $local['ImpLoc' . $singular],
                'Tasa' => (float) $local['Tasade' . $singular],
                'Importe' => (float) $local['Importe'],
            ];
        }
        return $list;
    }

    private function roundImpuestosGroup(array $group): array
    {
        foreach (array_keys($group) as $key) {
            $group[$key]['Importe'] = round($group[$key]['Importe'], $this->getPrecision());
        }
        return $group;
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

    public function getLocalesImpuestosTrasladados(): float
    {
        return $this->localesImpuestosTrasladados;
    }

    public function getLocalesImpuestosRetenidos(): float
    {
        return $this->localesImpuestosRetenidos;
    }

    public function getLocalesTraslados(): array
    {
        return $this->localesTraslados;
    }

    public function getLocalesRetenciones(): array
    {
        return $this->localesRetenciones;
    }

    public function hasLocalesTraslados(): bool
    {
        return (count($this->localesTraslados) > 0);
    }

    public function hasLocalesRetenciones(): bool
    {
        return (count($this->localesRetenciones) > 0);
    }

    public function foundAnyConceptWithDiscount(): bool
    {
        return $this->foundAnyConceptWithDiscount;
    }
}
