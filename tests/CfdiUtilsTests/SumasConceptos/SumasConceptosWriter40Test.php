<?php

namespace CfdiUtilsTests\SumasConceptos;

use CfdiUtils\Elements\Cfdi40\Comprobante as Comprobante40;
use CfdiUtils\SumasConceptos\SumasConceptos;
use CfdiUtils\SumasConceptos\SumasConceptosWriter;
use PHPUnit\Framework\TestCase;

final class SumasConceptosWriter40Test extends TestCase
{
    use SumasConceptosWriterTestTrait;

    public function createComprobante(array $attributes = []): Comprobante40
    {
        return new Comprobante40($attributes);
    }

    public function testConstructor(): void
    {
        $precision = 6;
        $comprobante = $this->createComprobante();
        $sumasConceptos = new SumasConceptos($comprobante, $precision);
        $writer = new SumasConceptosWriter($comprobante, $sumasConceptos, $precision);
        $this->assertSame($comprobante, $writer->getComprobante());

        $this->assertSame($precision, $writer->getPrecision());
        $this->assertSame($sumasConceptos, $writer->getSumasConceptos());
        $this->assertSame(true, $writer->hasWriteImpuestoBase());
        $this->assertSame(true, $writer->hasWriteExentos());
    }
}
