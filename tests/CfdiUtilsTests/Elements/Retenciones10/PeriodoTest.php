<?php
namespace CfdiUtilsTests\Elements\Retenciones10;

use CfdiUtils\Elements\Retenciones10\Periodo;
use PHPUnit\Framework\TestCase;

class PeriodoTest extends TestCase
{
    /** @var Periodo */
    public $element;

    public function setUp()
    {
        parent::setUp();
        $this->element = new Periodo();
    }

    public function testGetElementName()
    {
        $this->assertSame('retenciones:Periodo', $this->element->getElementName());
    }
}
