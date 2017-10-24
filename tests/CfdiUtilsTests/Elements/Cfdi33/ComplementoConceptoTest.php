<?php
namespace CfdiUtilsTests\Elements\Cfdi33;

use CfdiUtils\Elements\Cfdi33\ComplementoConcepto;
use PHPUnit\Framework\TestCase;

class ComplementoConceptoTest extends TestCase
{
    /** @var ComplementoConcepto */
    public $element;

    public function setUp()
    {
        parent::setUp();
        $this->element = new ComplementoConcepto();
    }

    public function testGetElementName()
    {
        $this->assertSame('cfdi:ComplementoConcepto', $this->element->getElementName());
    }
}
