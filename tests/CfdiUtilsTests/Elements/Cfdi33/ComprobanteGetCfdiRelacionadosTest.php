<?php

namespace CfdiUtilsTests\Elements\Cfdi33;

use CfdiUtils\Elements\Cfdi33\Comprobante;
use DMS\PHPUnitExtensions\ArraySubset\Assert as ArraySubsetAssert;
use PHPUnit\Framework\TestCase;

/*
 * This test case is created to check the behavior of the method getCfdiRelacionados
 *
 * The method should not receive any parameters but in version 2.4.5 this was added
 * On version 2.6 this was reverted and include the method addCfdiRelacionados that follow the "standard"
 * This test case should be removed on version 3.0.0 and drop that compatibility
 *
 * It uses the notation $comprobante->{'getCfdiRelacionados'}([]); to avoid phpstan errors
 *
 */

class ComprobanteGetCfdiRelacionadosTest extends TestCase
{
    private $errors = [];

    protected function setUp(): void
    {
        parent::setUp();
        set_error_handler(
            [$this, 'errorHandler'],
            E_USER_ERROR | E_USER_WARNING | E_USER_NOTICE | E_USER_DEPRECATED
        );
    }

    protected function tearDown(): void
    {
        restore_error_handler();
        parent::tearDown();
    }

    public function errorHandler(
        int $errno,
        string $errstr,
        string $errfile = '',
        int $errline = 0,
        array $errcontext = []
    ): bool {
        $this->errors[] = compact('errno', 'errstr', 'errfile', 'errline', 'errcontext');
        return true;
    }

    public function testGetCfdiRelacionadoDontTriggerErrorsWhenCallWithoutArgument()
    {
        $comprobante = new Comprobante();
        $comprobante->getCfdiRelacionados();
        $this->assertCount(0, $this->errors);
    }

    public function testErrorWhenPassAnArrayAsArgument()
    {
        $comprobante = new Comprobante();
        $comprobante->{'getCfdiRelacionados'}([]);
        $this->assertCount(1, $this->errors);

        $expectedError = [
            'errno' => E_USER_NOTICE,
            'errstr' => 'El método getCfdiRelacionados ya no admite atributos, use addCfdiRelacionados en su lugar',
        ];

        ArraySubsetAssert::assertArraySubset($expectedError, $this->errors[0]);
    }

    public function testStillIsWorkingWhenPassAnArrayAsArgument()
    {
        $comprobante = new Comprobante();
        $cfdiRelacionados = $comprobante->{'getCfdiRelacionados'}(['foo' => 'bar']);
        $this->assertSame('bar', $cfdiRelacionados['foo']);
    }
}
