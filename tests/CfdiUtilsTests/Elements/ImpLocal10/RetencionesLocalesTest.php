<?php
namespace CfdiUtilsTests\Elements\ImpLocal10;

use CfdiUtils\Elements\ImpLocal10\RetencionesLocales;
use PHPUnit\Framework\TestCase;

class RetencionesLocalesTest extends TestCase
{
    /** @var RetencionesLocales */
    public $element;

    protected function setUp()
    {
        parent::setUp();
        $this->element = new RetencionesLocales();
    }

    public function testGetElementName()
    {
        $this->assertSame('implocal:RetencionesLocales', $this->element->getElementName());
    }
}
