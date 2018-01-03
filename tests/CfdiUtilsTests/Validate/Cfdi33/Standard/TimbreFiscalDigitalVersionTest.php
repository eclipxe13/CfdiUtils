<?php
namespace CfdiUtilsTests\Validate\Cfdi33\Standard;

use CfdiUtils\Nodes\Node;
use CfdiUtils\Validate\Cfdi33\Standard\TimbreFiscalDigitalVersion;
use CfdiUtils\Validate\Status;
use CfdiUtilsTests\Validate\ValidateTestCase;

class TimbreFiscalDigitalVersionTest extends ValidateTestCase
{
    /** @var  TimbreFiscalDigitalVersion */
    protected $validator;

    protected function setUp()
    {
        parent::setUp();
        $this->validator = new TimbreFiscalDigitalVersion();
    }

    public function testValidCase()
    {
        $this->comprobante->addChild(new Node('cfdi:Complemento', [], [
            new Node('tfd:TimbreFiscalDigital', [
                'Version' => '1.1',
            ]),
        ]));

        $this->runValidate();
        $this->assertStatusEqualsCode(Status::ok(), 'TFDVERSION01');
    }

    public function providerInvalidVersion()
    {
        return[
            ['1.0'],
            ['1.2'],
            ['0.1'],
            ['1.10'],
            ['ASD'],
            [''],
            ['0'],
            [null],
        ];
    }

    /**
     * @param $version
     * @dataProvider providerInvalidVersion
     */
    public function testInvalidCase($version)
    {
        $this->comprobante->addChild(new Node('cfdi:Complemento', [], [
            new Node('tfd:TimbreFiscalDigital', [
                'Version' => $version,
            ]),
        ]));
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'TFDVERSION01');
    }

    public function testNoneCase()
    {
        $this->comprobante->addChild(new Node('cfdi:Complemento', [], []));
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::none(), 'TFDVERSION01');
    }
}
