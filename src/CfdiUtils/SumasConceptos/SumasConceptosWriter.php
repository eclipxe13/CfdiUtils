<?php

namespace CfdiUtils\SumasConceptos;

use CfdiUtils\Elements\Cfdi33\Comprobante as Comprobante33;
use CfdiUtils\Elements\Cfdi40\Comprobante as Comprobante40;
use CfdiUtils\Nodes\NodeInterface;
use InvalidArgumentException;

class SumasConceptosWriter
{
    /** @var Comprobante33|Comprobante40 */
    private $comprobante;

    /** @var SumasConceptos */
    private $sumas;

    /** @var int */
    private $precision;

    /** @var bool */
    private $writeImpuestoBase;

    /**
     * Writer constructor.
     * @param Comprobante33|Comprobante40 $comprobante
     * @param SumasConceptos $sumas
     * @param int $precision
     */
    public function __construct(
        NodeInterface $comprobante,
        SumasConceptos $sumas,
        int $precision = 6
    ) {
        if ($comprobante instanceof Comprobante33) {
            $this->writeImpuestoBase = false;
        } elseif ($comprobante instanceof Comprobante40) {
            $this->writeImpuestoBase = true;
        } else {
            throw new InvalidArgumentException(
                'The argument $comprobante must be a Comprobante (CFDI 3.3 or CFDI 4.0) element'
            );
        }
        $this->comprobante = $comprobante;
        $this->sumas = $sumas;
        $this->precision = $precision;
    }

    public function put()
    {
        $this->putComprobanteSumas();
        $this->putImpuestosNode();
        $this->putComplementoImpuestoLocalSumas();
    }

    private function putComprobanteSumas(): void
    {
        $this->comprobante['SubTotal'] = $this->format($this->sumas->getSubTotal());
        $this->comprobante['Total'] = $this->format($this->sumas->getTotal());
        $this->comprobante['Descuento'] = $this->format($this->sumas->getDescuento());
        if (! $this->sumas->foundAnyConceptWithDiscount()
            && ! $this->valueGreaterThanZero($this->sumas->getDescuento())) {
            unset($this->comprobante['Descuento']);
        }
    }

    private function putImpuestosNode(): void
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
                ...$this->getImpuestosContents($this->sumas->getTraslados(), $this->writeImpuestoBase)
            );
        }
        // add retenciones when needed
        if ($this->sumas->hasRetenciones()) {
            $impuestos['TotalImpuestosRetenidos'] = $this->format($this->sumas->getImpuestosRetenidos());
            $impuestos->getRetenciones()->multiRetencion(
                ...$this->getImpuestosContents($this->sumas->getRetenciones(), false)
            );
        }
    }

    private function putComplementoImpuestoLocalSumas(): void
    {
        // search for implocal node
        $impLocal = $this->comprobante->searchNode('cfdi:Complemento', 'implocal:ImpuestosLocales');
        if (! $impLocal) {
            return;
        }
        if (! $this->sumas->hasLocalesTraslados() && ! $this->sumas->hasLocalesRetenciones()) {
            $complemento = $this->comprobante->getComplemento();
            $complemento->children()->remove($impLocal);
            if (0 === $complemento->count()) {
                $this->comprobante->children()->remove($complemento);
            }
            return;
        }
        $impLocal->attributes()->set('TotaldeRetenciones', $this->format($this->sumas->getLocalesImpuestosRetenidos()));
        $impLocal->attributes()->set('TotaldeTraslados', $this->format($this->sumas->getLocalesImpuestosTrasladados()));
    }

    private function getImpuestosContents(array $impuestos, bool $hasBase): array
    {
        $return = [];
        foreach ($impuestos as $impuesto) {
            $impuesto['Base'] = $this->format($impuesto['Base'] ?? 0);
            $impuesto['Importe'] = $this->format($impuesto['Importe']);
            if (! $hasBase) {
                unset($impuesto['Base']);
            }
            $return[] = $impuesto;
        }
        return $return;
    }

    private function valueGreaterThanZero(float $value): bool
    {
        return (round($value, $this->precision) > 0);
    }

    public function format(float $number): string
    {
        return number_format($number, $this->precision, '.', '');
    }

    /** @return Comprobante33|Comprobante40 */
    public function getComprobante()
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

    public function hasWriteImpuestoBase(): bool
    {
        return $this->writeImpuestoBase;
    }
}
