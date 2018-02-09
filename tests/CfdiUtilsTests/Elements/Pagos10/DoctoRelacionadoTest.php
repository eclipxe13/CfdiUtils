<?php
namespace CfdiUtilsTests\Elements\Pagos10;

use CfdiUtils\Elements\Pagos10\DoctoRelacionado;
use PHPUnit\Framework\TestCase;

class DoctoRelacionadoTest extends TestCase
{
    /** @var DoctoRelacionado */
    public $element;

    protected function setUp()
    {
        parent::setUp();
        $this->element = new DoctoRelacionado();
    }

    public function testConstructedObject()
    {
        $this->assertSame('pagos10:DoctoRelacionado', $this->element->getElementName());
    }
}
