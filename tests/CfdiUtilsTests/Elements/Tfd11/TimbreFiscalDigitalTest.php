<?php

namespace CfdiUtilsTests\Elements\Tfd11;

use CfdiUtils\Elements\Cfdi33\Comprobante;
use CfdiUtils\Elements\Tfd11\TimbreFiscalDigital;
use PHPUnit\Framework\TestCase;

class TimbreFiscalDigitalTest extends TestCase
{
    /**@var Comprobante */
    public $element;

    protected function setUp()
    {
        parent::setUp();
        $this->element = new TimbreFiscalDigital();
    }

    public function testGetElementName()
    {
        $this->assertSame('tfd:TimbreFiscalDigital', $this->element->getElementName());
    }

    public function testHasFixedAttributes()
    {
        $namespace = 'http://www.sat.gob.mx/TimbreFiscalDigital';
        $this->assertSame('1.1', $this->element['Version']);
        $this->assertSame($namespace, $this->element['xmlns:tfd']);
        $this->assertStringStartsWith($namespace . ' http://', $this->element['xsi:schemaLocation']);
    }
}
