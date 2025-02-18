<?php

namespace CfdiUtilsTests\Elements\PagosAExtranjeros10;

use CfdiUtils\Elements\PagosAExtranjeros10\Beneficiario;
use PHPUnit\Framework\TestCase;

final class BeneficiarioTest extends TestCase
{
    /** @var Beneficiario */
    public $element;

    public function setUp(): void
    {
        parent::setUp();
        $this->element = new Beneficiario();
    }

    public function testGetElementName(): void
    {
        $this->assertSame('pagosaextranjeros:Beneficiario', $this->element->getElementName());
    }
}
