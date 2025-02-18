<?php

namespace CfdiUtilsTests\Elements\PagosAExtranjeros10;

use CfdiUtils\Elements\PagosAExtranjeros10\NoBeneficiario;
use PHPUnit\Framework\TestCase;

final class NoBeneficiarioTest extends TestCase
{
    /** @var NoBeneficiario */
    public $element;

    public function setUp(): void
    {
        parent::setUp();
        $this->element = new NoBeneficiario();
    }

    public function testGetElementName(): void
    {
        $this->assertSame('pagosaextranjeros:NoBeneficiario', $this->element->getElementName());
    }
}
