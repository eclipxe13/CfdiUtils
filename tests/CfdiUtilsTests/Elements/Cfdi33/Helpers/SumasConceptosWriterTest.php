<?php

namespace CfdiUtilsTests\Elements\Cfdi33\Helpers;

use CfdiUtils\Elements\Cfdi33\Comprobante;
use CfdiUtils\Elements\Cfdi33\Helpers\SumasConceptosWriter;
use CfdiUtils\SumasConceptos\SumasConceptos;
use CfdiUtilsTests\SumasConceptos\SumasConceptosWriterTestTrait;
use PHPUnit\Framework\TestCase;

final class SumasConceptosWriterTest extends TestCase
{
    use SumasConceptosWriterTestTrait;

    public function createComprobante(array $attributes = []): Comprobante
    {
        return new Comprobante($attributes);
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
    }
}
