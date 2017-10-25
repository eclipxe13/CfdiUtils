<?php
namespace CfdiUtilsTests;

use CfdiUtils\Cfdi;
use CfdiUtils\CfdiValidator33;
use CfdiUtils\Nodes\Node;
use CfdiUtils\XmlResolver\XmlResolver;

class CfdiValidator33Test extends TestCase
{
    public function testConstructWithoutArguments()
    {
        $validator = new CfdiValidator33();
        $this->assertTrue($validator->hasXmlResolver());
    }

    public function testConstructWithResolver()
    {
        $xmlResolver = new XmlResolver();
        $validator = new CfdiValidator33($xmlResolver);
        $this->assertSame($xmlResolver, $validator->getXmlResolver());
    }

    public function testValidateWithIncorrectXmlString()
    {
        $validator = new CfdiValidator33();
        $asserts = $validator->validateXml('<not-a-cfdi/>');
        $this->assertTrue($asserts->hasErrors());
    }

    public function testValidateWithIncorrectNode()
    {
        $validator = new CfdiValidator33();
        $asserts = $validator->validateNode(new Node('not-a-cfdi'));
        $this->assertTrue($asserts->hasErrors());
    }

    public function testValidateWithCorrectData()
    {
        $cfdiFile = $this->utilAsset('cfdi33-valid.xml');
        $cfdi = Cfdi::newFromString(file_get_contents($cfdiFile));

        $validator = new CfdiValidator33();
        $asserts = $validator->validate($cfdi->getSource(), $cfdi->getNode());
        $this->assertFalse($asserts->hasErrors());
    }

    public function testValidateThrowsExceptionIfEmptyContent()
    {
        $validator = new CfdiValidator33();
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('empty');
        $validator->validate('', new Node('root'));
    }
}
