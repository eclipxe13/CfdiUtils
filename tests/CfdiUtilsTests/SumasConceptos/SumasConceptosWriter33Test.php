<?php

namespace CfdiUtilsTests\SumasConceptos;

use CfdiUtils\Elements\Cfdi33\Comprobante as Comprobante33;
use CfdiUtils\SumasConceptos\SumasConceptos;
use CfdiUtils\SumasConceptos\SumasConceptosWriter;
use PHPUnit\Framework\TestCase;

final class SumasConceptosWriter33Test extends TestCase
{
    use SumasConceptosWriterTestTrait;

    public function createComprobante(array $attributes = []): Comprobante33
    {
        return new Comprobante33($attributes);
    }

    public function testConstructor()
    {
        $precision = 6;
        $comprobante = $this->createComprobante();
        $sumasConceptos = new SumasConceptos($comprobante, $precision);
        $writer = new SumasConceptosWriter($comprobante, $sumasConceptos, $precision);
        $this->assertSame($comprobante, $writer->getComprobante());

        $this->assertSame($precision, $writer->getPrecision());
        $this->assertSame($sumasConceptos, $writer->getSumasConceptos());
        $this->assertSame(false, $writer->hasWriteImpuestoBase());
        $this->assertSame(false, $writer->hasWriteExentos());
    }
}
