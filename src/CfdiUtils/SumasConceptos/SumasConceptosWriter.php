<?php

namespace CfdiUtils\SumasConceptos;

use CfdiUtils\Elements\Cfdi33\Comprobante as Comprobante33;
use CfdiUtils\Elements\Cfdi40\Comprobante as Comprobante40;
use CfdiUtils\Nodes\NodeInterface;
use InvalidArgumentException;

class SumasConceptosWriter
{
    /** @var Comprobante33|Comprobante40 */
    private NodeInterface $comprobante;

    private ?bool $writeImpuestoBase = null;

    private ?bool $writeExentos = null;

    /**
     * Writer constructor.
     * @param Comprobante33|Comprobante40 $comprobante
     */
    public function __construct(
        NodeInterface $comprobante,
        private SumasConceptos $sumas,
        private int $precision = 6,
    ) {
        if ($comprobante instanceof Comprobante33) {
            $this->writeImpuestoBase = false;
            $this->writeExentos = false;
        } elseif ($comprobante instanceof Comprobante40) {
            $this->writeImpuestoBase = true;
            $this->writeExentos = true;
        } else {
            throw new InvalidArgumentException(
                'The argument $comprobante must be a Comprobante (CFDI 3.3 or CFDI 4.0) element'
            );
        }
        $this->comprobante = $comprobante;
    }

    public function put(): void
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
        if (
            ! $this->sumas->foundAnyConceptWithDiscount()
            && ! $this->valueGreaterThanZero($this->sumas->getDescuento())
        ) {
            unset($this->comprobante['Descuento']);
        }
    }

    private function putImpuestosNode(): void
    {
        // obtain node reference
        $impuestos = $this->comprobante->getImpuestos();
        // if there is nothing to write then remove the children and exit
        if (
            ! $this->sumas->hasTraslados()
            && ! $this->sumas->hasRetenciones()
            && ! ($this->writeExentos && $this->sumas->hasExentos())
        ) {
            $this->comprobante->children()->remove($impuestos);
            return;
        }
        // clear previous values
        $impuestos->clear();
        // add traslados when needed
        if ($this->sumas->hasTraslados()) {
            $impuestos['TotalImpuestosTrasladados'] = $this->format($this->sumas->getImpuestosTrasladados());
            $impuestos->getTraslados()->multiTraslado(
                ...$this->getImpuestosContents($this->sumas->getTraslados(), $this->writeImpuestoBase, true)
            );
        }
        if ($this->writeExentos && $this->sumas->hasExentos()) {
            $impuestos->getTraslados()->multiTraslado(
                ...$this->getImpuestosContents($this->sumas->getExentos(), $this->writeImpuestoBase, false)
            );
        }
        // add retenciones when needed
        if ($this->sumas->hasRetenciones()) {
            $impuestos['TotalImpuestosRetenidos'] = $this->format($this->sumas->getImpuestosRetenidos());
            $impuestos->getRetenciones()->multiRetencion(
                ...$this->getImpuestosContents($this->sumas->getRetenciones(), false, true)
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

    private function getImpuestosContents(array $impuestos, bool $hasBase, bool $hasImporte): array
    {
        $return = [];
        foreach ($impuestos as $impuesto) {
            $impuesto['Base'] = ($hasBase) ? $this->format($impuesto['Base'] ?? 0) : null;
            $impuesto['Importe'] = ($hasImporte) ? $this->format($impuesto['Importe']) : null;
            $return[] = array_filter($impuesto, fn ($value): bool => null !== $value);
        }
        return $return;
    }

    private function valueGreaterThanZero(float $value): bool
    {
        return round($value, $this->precision) > 0;
    }

    public function format(float $number): string
    {
        return number_format($number, $this->precision, '.', '');
    }

    /** @return Comprobante33|Comprobante40 */
    public function getComprobante(): NodeInterface
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

    public function hasWriteExentos(): bool
    {
        return $this->writeExentos;
    }
}
