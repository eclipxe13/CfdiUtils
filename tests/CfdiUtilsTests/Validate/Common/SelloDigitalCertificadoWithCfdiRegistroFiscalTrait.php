<?php

namespace CfdiUtilsTests\Validate\Common;

use CfdiUtils\Elements\Tfd11\TimbreFiscalDigital;
use CfdiUtils\Nodes\Node;
use CfdiUtils\Validate\Status;

trait SelloDigitalCertificadoWithCfdiRegistroFiscalTrait
{
    public function testFailWhenHasNotCfdiRegistroFiscalAndCertificadosDoNotMatch(): void
    {
        $this->runValidate();

        $this->assertStatusEqualsCode(Status::error(), 'SELLO03');
        $this->assertStatusEqualsCode(Status::error(), 'SELLO04');
    }

    public function testFailWhenHasNotCfdiRegistroFiscalAndCertificadosMatch(): void
    {
        $this->comprobante->addChild(new Node('cfdi:Complemento', [], [
            new TimbreFiscalDigital(['NoCertificadoSAT' => '00001000000403258748']),
        ]));

        $this->runValidate();

        $this->assertStatusEqualsCode(Status::error(), 'SELLO03');
        $this->assertStatusEqualsCode(Status::error(), 'SELLO04');
    }

    public function testFailWhenHasCfdiRegistroFiscalAndCertificadosDoNotMatch(): void
    {
        $this->comprobante->addChild(new Node('cfdi:Complemento', [], [
            new Node('registrofiscal:CFDIRegistroFiscal'),
        ]));

        $this->runValidate();

        $this->assertStatusEqualsCode(Status::error(), 'SELLO03');
        $this->assertStatusEqualsCode(Status::error(), 'SELLO04');
    }

    public function testPassWhenHasCfdiRegistroFiscalAndCertificadosMatch(): void
    {
        $this->comprobante->addChild(new Node('cfdi:Complemento', [], [
            new Node('registrofiscal:CFDIRegistroFiscal'),
            new TimbreFiscalDigital(['NoCertificadoSAT' => '00001000000403258748']),
        ]));

        $this->runValidate();

        $this->assertStatusEqualsCode(Status::none(), 'SELLO03');
        $this->assertStatusEqualsCode(Status::none(), 'SELLO04');
    }
}
