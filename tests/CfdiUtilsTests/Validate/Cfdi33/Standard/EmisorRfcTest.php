<?php
namespace CfdiUtilsTests\Validate\Cfdi33\Standard;

use CfdiUtils\Nodes\Node;
use CfdiUtils\Utils\Rfc;
use CfdiUtils\Validate\Cfdi33\Standard\EmisorRfc;
use CfdiUtils\Validate\Status;
use CfdiUtilsTests\Validate\ValidateTestCase;

class EmisorRfcTest extends ValidateTestCase
{
    /** @var EmisorRfc */
    protected $validator;

    protected function setUp()
    {
        parent::setUp();
        $this->validator = new EmisorRfc();
    }

    public function providerValidCases()
    {
        return [
            'person' => ['COSC8001137NA'],
            'moral' => ['DIM8701081LA'],
        ];
    }

    /**
     * @param string $rfc
     * @dataProvider providerValidCases
     */
    public function testValidCases(string $rfc)
    {
        $this->comprobante->addChild(new Node('cfdi:Emisor', [
            'Rfc' => $rfc,
        ]));
        $this->runValidate();
        $this->assertFalse($this->asserts->hasErrors());
        $this->assertStatusEqualsCode(Status::ok(), 'EMISORRFC01');
    }

    public function providerInvalidCases()
    {
        return [
            'none' => [null],
            'empty' => [''],
            'wrong' => ['COSC8001137N0'],
            'generic' => [Rfc::RFC_GENERIC],
            'foreign' => [Rfc::RFC_FOREIGN],
            'testing' => ['AAA010101AAA'],
        ];
    }

    /**
     * @param string $rfc
     * @dataProvider providerInvalidCases
     */
    public function testInvalidCases($rfc)
    {
        $this->comprobante->addChild(new Node('cfdi:Emisor', [
            'Rfc' => $rfc,
        ]));
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'EMISORRFC01');
    }
}
