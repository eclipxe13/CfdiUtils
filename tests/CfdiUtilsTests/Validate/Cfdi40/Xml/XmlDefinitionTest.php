<?php

namespace CfdiUtilsTests\Validate\Cfdi40\Xml;

use CfdiUtils\Nodes\Node;
use CfdiUtils\Validate\Cfdi40\Xml\XmlDefinition;
use CfdiUtils\Validate\Status;
use CfdiUtilsTests\Validate\ValidateTestCase;

final class XmlDefinitionTest extends ValidateTestCase
{
    /** @var XmlDefinition */
    protected $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new XmlDefinition();
    }

    public function testCorrectDefinition(): void
    {
        $this->comprobante = new Node('cfdi:Comprobante', [
            'xmlns:cfdi' => 'http://www.sat.gob.mx/cfd/4',
            'Version' => '4.0',
        ]);
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::ok(), 'XML01');
        $this->assertStatusEqualsCode(Status::ok(), 'XML02');
        $this->assertStatusEqualsCode(Status::ok(), 'XML03');
    }

    public function testInCorrectDefinitionNamespacePrefix(): void
    {
        $this->comprobante = new Node('cfdi:Comprobante', [
            'xmlns:cfdi4' => 'http://www.sat.gob.mx/cfd/4',
            'Version' => '4.0',
        ]);
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'XML01');
    }

    public function testInCorrectDefinitionNamespaceValue(): void
    {
        $this->comprobante = new Node('cfdi:Comprobante', [
            'xmlns:cfdi' => 'http://www.sat.gob.mx/cfd/40',
            'Version' => '4.0',
        ]);
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'XML01');
    }

    public function testInCorrectDefinitionRootPrefix(): void
    {
        $this->comprobante = new Node('cfdi4:Comprobante', [
            'xmlns:cfdi' => 'http://www.sat.gob.mx/cfd/4',
            'Version' => '4.0',
        ]);
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'XML02');
    }

    public function testInCorrectDefinitionRootName(): void
    {
        $this->comprobante = new Node('cfdi:Cfdi', [
            'xmlns:cfdi' => 'http://www.sat.gob.mx/cfd/4',
            'Version' => '4.0',
        ]);
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'XML02');
    }

    public function testInCorrectDefinitionVersionName(): void
    {
        $this->comprobante = new Node('cfdi:Comprobante', [
            'xmlns:cfdi' => 'http://www.sat.gob.mx/cfd/4',
            'version' => '4.0',
        ]);
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'XML03');
    }

    public function testInCorrectDefinitionVersionValue(): void
    {
        $this->comprobante = new Node('cfdi:Comprobante', [
            'xmlns:cfdi' => 'http://www.sat.gob.mx/cfd/4',
            'Version' => '4.1',
        ]);
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'XML03');
    }
}
