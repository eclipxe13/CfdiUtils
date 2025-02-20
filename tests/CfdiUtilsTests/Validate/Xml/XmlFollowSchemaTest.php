<?php

namespace CfdiUtilsTests\Validate\Xml;

use CfdiUtils\Cfdi;
use CfdiUtils\Validate\Contracts\ValidatorInterface;
use CfdiUtils\Validate\Status;
use CfdiUtils\Validate\Xml\XmlFollowSchema;
use CfdiUtilsTests\Validate\Validate33TestCase;

final class XmlFollowSchemaTest extends Validate33TestCase
{
    /** @var XmlFollowSchema */
    protected ValidatorInterface $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new XmlFollowSchema();
        $this->validator->setXmlResolver($this->newResolver());
    }

    public function testUsingRealCfdi33(): void
    {
        $xmlContent = strval(file_get_contents($this->utilAsset('cfdi33-real.xml')));
        $this->comprobante = Cfdi::newFromString($xmlContent)->getNode();
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::ok(), 'XSD01');
    }

    public function testWithMissingElement(): void
    {
        $xmlContent = strval(file_get_contents($this->utilAsset('cfdi33-real.xml')));
        $comprobante = Cfdi::newFromString($xmlContent)->getNode();
        $emisor = $comprobante->children()->firstNodeWithName('cfdi:Emisor');
        if (null === $emisor) {
            throw new \LogicException('CFDI33 (real) does not contains an cfdi:Emisor node!');
        }
        $comprobante->children()->remove($emisor);
        $this->comprobante = $comprobante;
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'XSD01');
        $this->assertStringContainsString('Emisor', $this->asserts->get('XSD01')->getExplanation());
    }

    public function testWithXsdUriNotFound(): void
    {
        $xmlContent = strval(file_get_contents($this->utilAsset('cfdi33-real.xml')));
        $xmlContent = str_replace(
            'http://www.sat.gob.mx/sitio_internet/cfd/TimbreFiscalDigital/TimbreFiscalDigitalv11.xsd',
            'http://www.sat.gob.mx/sitio_internet/not-found/TimbreFiscalDigital/TimbreFiscalDigitalv11.xsd',
            $xmlContent
        );
        $this->comprobante = Cfdi::newFromString($xmlContent)->getNode();
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'XSD01');
    }
}
