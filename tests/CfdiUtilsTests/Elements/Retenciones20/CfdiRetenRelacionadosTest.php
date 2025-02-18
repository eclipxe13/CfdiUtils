<?php

namespace CfdiUtilsTests\Elements\Retenciones20;

use CfdiUtils\Elements\Retenciones20\CfdiRetenRelacionados;
use PHPUnit\Framework\TestCase;

final class CfdiRetenRelacionadosTest extends TestCase
{
    /** @var CfdiRetenRelacionados */
    public $element;

    public function setUp(): void
    {
        parent::setUp();
        $this->element = new CfdiRetenRelacionados();
    }

    public function testGetElementName(): void
    {
        $this->assertSame('retenciones:CfdiRetenRelacionados', $this->element->getElementName());
    }
}
