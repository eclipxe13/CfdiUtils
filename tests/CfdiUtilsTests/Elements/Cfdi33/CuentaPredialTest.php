<?php
namespace CfdiUtilsTests\Elements\Cfdi33;

use CfdiUtils\Elements\Cfdi33\CuentaPredial;
use PHPUnit\Framework\TestCase;

class CuentaPredialTest extends TestCase
{
    /** @var CuentaPredial */
    public $element;

    public function setUp()
    {
        parent::setUp();
        $this->element = new CuentaPredial();
    }

    public function testGetElementName()
    {
        $this->assertSame('cfdi:CuentaPredial', $this->element->getElementName());
    }
}
