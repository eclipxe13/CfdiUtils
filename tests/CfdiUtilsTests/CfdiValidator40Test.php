<?php

namespace CfdiUtilsTests;

use CfdiUtils\Cfdi;
use CfdiUtils\CfdiValidator40;
use CfdiUtils\Nodes\Node;

final class CfdiValidator40Test extends TestCase
{
    public function testConstructWithoutArguments(): void
    {
        $validator = new CfdiValidator40();
        $this->assertTrue($validator->hasXmlResolver());
    }

    public function testConstructWithResolver(): void
    {
        $xmlResolver = $this->newResolver();
        $validator = new CfdiValidator40($xmlResolver);
        $this->assertSame($xmlResolver, $validator->getXmlResolver());
    }

    public function testValidateWithIncorrectXmlString(): void
    {
        $validator = new CfdiValidator40();
        $asserts = $validator->validateXml('<not-a-cfdi/>');
        $this->assertTrue($asserts->hasErrors());
    }

    public function testValidateWithIncorrectNode(): void
    {
        $validator = new CfdiValidator40();
        $asserts = $validator->validateNode(new Node('not-a-cfdi'));
        $this->assertTrue($asserts->hasErrors());
    }

    public function testValidateWithCorrectData(): void
    {
        $cfdiFile = $this->utilAsset('cfdi40-valid.xml');
        $cfdi = Cfdi::newFromString(strval(file_get_contents($cfdiFile)));

        // install PAC testing certificate
        $this->installCertificate($this->utilAsset('certs/30001000000500003456.cer'));

        $validator = new CfdiValidator40();
        $asserts = $validator->validate($cfdi->getSource(), $cfdi->getNode());
        // print_r($asserts->errors());
        $this->assertFalse(
            $asserts->hasErrors(),
            'The validation of an expected cfdi40 valid file fails,'
                . ' maybe you are creating a new discoverable standard validator that found a bug. Excelent!'
        );
    }

    public function testValidateThrowsExceptionIfEmptyContent(): void
    {
        $validator = new CfdiValidator40();
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('empty');
        $validator->validate('', new Node('root'));
    }

    public function testValidateCfdi40Real(): void
    {
        $cfdiFile = $this->utilAsset('cfdi40-real.xml');
        $cfdi = Cfdi::newFromString(strval(file_get_contents($cfdiFile)));

        // install PAC certificate, prevent if SAT service is down
        $this->installCertificate($this->utilAsset('certs/00001000000708361114.cer'));

        $validator = new CfdiValidator40($this->newResolver());
        $asserts = $validator->validate($cfdi->getSource(), $cfdi->getNode());
        // $asserts->hasErrors() && print_r($asserts->errors());
        $this->assertFalse(
            $asserts->hasErrors(),
            'The validation of an expected cfdi40 real file fails'
        );
    }
}
