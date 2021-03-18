<?php

namespace CfdiUtilsTests\Validate\Cfdi33\Xml;

use CfdiUtils\Cfdi;
use CfdiUtils\Validate\Cfdi33\Xml\XmlFollowSchema;
use CfdiUtils\Validate\Status;
use CfdiUtilsTests\Validate\ValidateTestCase;

final class XmlFollowSchemaTest extends ValidateTestCase
{
    /** @var XmlFollowSchema */
    protected $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new XmlFollowSchema();
        $this->validator->setXmlResolver($this->newResolver());
    }

    public function testUsingRealCfdi33()
    {
        $xmlContent = strval(file_get_contents($this->utilAsset('cfdi33-real.xml')));
        $this->comprobante = Cfdi::newFromString($xmlContent)->getNode();
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::ok(), 'XSD01');
    }

    public function testWithMissingElement()
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

    public function testWithXsdUriNotFound()
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
