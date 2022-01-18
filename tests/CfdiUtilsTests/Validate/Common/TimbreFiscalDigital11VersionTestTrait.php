<?php

namespace CfdiUtilsTests\Validate\Common;

use CfdiUtils\Elements\Tfd11\TimbreFiscalDigital;
use CfdiUtils\Nodes\Node;
use CfdiUtils\Validate\Status;

trait TimbreFiscalDigital11VersionTestTrait
{
    public function testValidCase()
    {
        $this->comprobante->addChild(new Node('cfdi:Complemento', [], [
            new TimbreFiscalDigital(),
        ]));

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
        $this->comprobante->addChild(new Node('cfdi:Complemento', [], [$tfd]));
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
