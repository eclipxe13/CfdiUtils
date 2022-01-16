<?php

namespace CfdiUtilsTests;

use CfdiUtils\Cfdi;
use CfdiUtils\CfdiValidator40;
use CfdiUtils\Nodes\Node;

final class CfdiValidator40Test extends TestCase
{
    public function testConstructWithoutArguments()
    {
        $validator = new CfdiValidator40();
        $this->assertTrue($validator->hasXmlResolver());
    }

    public function testConstructWithResolver()
    {
        $xmlResolver = $this->newResolver();
        $validator = new CfdiValidator40($xmlResolver);
        $this->assertSame($xmlResolver, $validator->getXmlResolver());
    }

    public function testValidateWithIncorrectXmlString()
    {
        $validator = new CfdiValidator40();
        $asserts = $validator->validateXml('<not-a-cfdi/>');
        $this->assertTrue($asserts->hasErrors());
    }

    public function testValidateWithIncorrectNode()
    {
        $validator = new CfdiValidator40();
        $asserts = $validator->validateNode(new Node('not-a-cfdi'));
        $this->assertTrue($asserts->hasErrors());
    }

    public function testValidateWithCorrectData()
    {
        $cfdiFile = $this->utilAsset('cfdi40-valid.xml');
        $cfdi = Cfdi::newFromString(strval(file_get_contents($cfdiFile)));

        $validator = new CfdiValidator40();
        $asserts = $validator->validate($cfdi->getSource(), $cfdi->getNode());
        $this->assertFalse(
            $asserts->hasErrors(),
            'The validation of an expected cfdi40 valid file fails,'
                . ' maybe you are creating a new discoverable standard validator that found a bug. Excelent!'
        );
    }

    public function testValidateThrowsExceptionIfEmptyContent()
    {
        $validator = new CfdiValidator40();
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('empty');
        $validator->validate('', new Node('root'));
    }
}
