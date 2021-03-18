<?php

namespace CfdiUtilsTests\Validate\Cfdi33\Standard;

use CfdiUtils\Elements\Tfd11\TimbreFiscalDigital;
use CfdiUtils\Nodes\Node;
use CfdiUtils\Validate\Cfdi33\Standard\TimbreFiscalDigitalVersion;
use CfdiUtils\Validate\Status;
use CfdiUtilsTests\Validate\ValidateTestCase;

final class TimbreFiscalDigitalVersionTest extends ValidateTestCase
{
    /* @var \CfdiUtils\Elements\Cfdi33\Comprobante */
    protected $comprobante;

    /** @var  TimbreFiscalDigitalVersion */
    protected $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new TimbreFiscalDigitalVersion();
    }

    public function testValidCase()
    {
        $this->getComprobante()->addComplemento(new TimbreFiscalDigital());

        $this->runValidate();
        $this->assertStatusEqualsCode(Status::ok(), 'TFDVERSION01');
    }

    public function providerInvalidVersion(): array
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
     * @param string|null $version
     * @dataProvider providerInvalidVersion
     */
    public function testInvalidCase(?string $version)
    {
        $tfd = new TimbreFiscalDigital();
        $tfd->addAttributes(['Version' => $version]); // override version
        $this->getComprobante()->addComplemento($tfd);
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
