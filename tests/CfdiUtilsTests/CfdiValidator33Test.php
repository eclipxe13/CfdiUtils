<?php

namespace CfdiUtilsTests;

use CfdiUtils\Cfdi;
use CfdiUtils\CfdiCreator33;
use CfdiUtils\CfdiValidator33;
use CfdiUtils\Nodes\Node;
use CfdiUtils\Nodes\XmlNodeUtils;

final class CfdiValidator33Test extends TestCase
{
    public function testConstructWithoutArguments()
    {
        $validator = new CfdiValidator33();
        $this->assertTrue($validator->hasXmlResolver());
    }

    public function testConstructWithResolver()
    {
        $xmlResolver = $this->newResolver();
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
        $cfdi = Cfdi::newFromString(strval(file_get_contents($cfdiFile)));

        $validator = new CfdiValidator33();
        $asserts = $validator->validate($cfdi->getSource(), $cfdi->getNode());
        // Is already known that TFDSELLO01 is failing.
        // We are not creating the SelloSAT for cfdi33-valid.xml file
        $asserts->removeByCode('TFDSELLO01');
        $this->assertFalse(
            $asserts->hasErrors(),
            'The validation of an expected cfdi33 valid file fails,'
                . ' maybe you are creating a new discoverable standard validator that found a bug. Excelent!'
        );
    }

    /**
     * Developer: Use this procedure to change the cfdi on the file 'asserts/cfdi33-valid.xml'
     * and show in screen the value of the Sello again
     * @ test
     */
    public function procedureCreateSelloAgainOnValidCdfi33()
    {
        $cfdiFile = $this->utilAsset('cfdi33-valid.xml');
        $pemKeyFile = $this->utilAsset('certs/EKU9003173C9.key.pem');
        $node = XmlNodeUtils::nodeFromXmlString(strval(file_get_contents($cfdiFile)));
        $creator = CfdiCreator33::newUsingNode($node);
        $comprobante = $creator->comprobante();
        $previous = $comprobante['Sello'];

        // developer: change here what you need
        $comprobante['TipoCambio'] = '1';

        $creator->addSello('file://' . $pemKeyFile);
        print_r([
            'old' => $previous,
            'new' => $comprobante['Sello'],
        ]);
        // echo $creator->asXml();
        $this->assertTrue(false, 'This procedure must not run in real testing');
    }

    public function testValidateThrowsExceptionIfEmptyContent()
    {
        $validator = new CfdiValidator33();
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('empty');
        $validator->validate('', new Node('root'));
    }

    public function testValidateCfdi33Real()
    {
        $cfdiFile = $this->utilAsset('cfdi33-real.xml');
        $cfdi = Cfdi::newFromString(strval(file_get_contents($cfdiFile)));

        $validator = new CfdiValidator33($this->newResolver());
        $asserts = $validator->validate($cfdi->getSource(), $cfdi->getNode());
        // $asserts->hasErrors() && print_r($asserts->errors());
        $this->assertFalse(
            $asserts->hasErrors(),
            'The validation of an expected cfdi33 real file fails'
        );
    }
}
