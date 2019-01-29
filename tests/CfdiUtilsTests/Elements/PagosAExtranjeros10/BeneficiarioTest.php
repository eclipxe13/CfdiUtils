<?php
namespace CfdiUtilsTests\Elements\PagosAExtranjeros10;

use CfdiUtils\Elements\PagosAExtranjeros10\Beneficiario;
use PHPUnit\Framework\TestCase;

class BeneficiarioTest extends TestCase
{
    /** @var Beneficiario */
    public $element;

    public function setUp()
    {
        parent::setUp();
        $this->element = new Beneficiario();
    }

    public function testGetElementName()
    {
        $this->assertSame('pagosaextranjeros:Beneficiario', $this->element->getElementName());
    }
}
