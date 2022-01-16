<?php

namespace CfdiUtilsTests\Validate\Cfdi33\Standard;

use CfdiUtils\Elements\Tfd11\TimbreFiscalDigital;
use CfdiUtils\Nodes\Node;
use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\Validate\Cfdi33\Standard\TimbreFiscalDigitalSello;
use CfdiUtils\Validate\Status;
use CfdiUtilsTests\Validate\Validate33TestCase;

final class TimbreFiscalDigitalSelloTest extends Validate33TestCase
{
    /** @var TimbreFiscalDigitalSello */
    protected $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new TimbreFiscalDigitalSello();
        $this->hydrater->hydrate($this->validator);
    }

    public function testValidCase()
    {
        $selloCfdi = $this->validSelloCfdi();
        $selloSat = $this->validSelloSat();
        $this->comprobante['Sello'] = $selloCfdi;
        $tfd = $this->newTimbreFiscalDigital([
            'NoCertificadoSAT' => $this->validCertificadoSAT(),
            'SelloSAT' => $selloSat,
            // these are required to create the source string
            'SelloCFD' => $selloCfdi,
            'FechaTimbrado' => '2018-01-12T08:17:54',
            'RfcProvCertif' => 'DCD090706E42',
            'UUID' => 'CEE4BE01-ADFA-4DEB-8421-ADD60F0BEDAC',
        ]);
        $this->comprobante->addChild(new Node('cfdi:Complemento', [], [$tfd]));

        $this->runValidate();
        $this->assertStatusEqualsCode(Status::ok(), 'TFDSELLO01');
    }

    public function testValidatorDontHaveXmlResolver()
    {
        $this->validator->setXmlResolver(null);
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::none(), 'TFDSELLO01');
        $this->assertStringContainsString(
            'No se puede hacer la validación',
            $this->asserts->get('TFDSELLO01')->getExplanation()
        );
    }

    public function testValidatorDontHaveTimbreFiscalDigital()
    {
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::none(), 'TFDSELLO01');
        $this->assertStringContainsString('no contiene un Timbre', $this->asserts->get('TFDSELLO01')->getExplanation());
    }

    public function testValidatorTimbreFiscalDigitalVersionIsNotPresent()
    {
        $tfd = $this->newTimbreFiscalDigital();
        unset($tfd['Version']);
        $this->comprobante->addChild(new Node('cfdi:Complemento', [], [$tfd]));

        $this->runValidate();
        $this->assertStatusEqualsCode(Status::none(), 'TFDSELLO01');
        $this->assertStringContainsString('La versión del timbre', $this->asserts->get('TFDSELLO01')->getExplanation());
    }

    public function testValidatorTimbreFiscalDigitalVersionIsNotValid()
    {
        $tfd = $this->newTimbreFiscalDigital();
        $tfd['Version'] = '1.0';
        $this->comprobante->addChild(new Node('cfdi:Complemento', [], [$tfd]));

        $this->runValidate();
        $this->assertStatusEqualsCode(Status::none(), 'TFDSELLO01');
        $this->assertStringContainsString('La versión del timbre', $this->asserts->get('TFDSELLO01')->getExplanation());
    }

    public function testValidatorTimbreFiscalDigitalSelloSatDoesNotMatchWithComprobante()
    {
        $tfd = $this->newTimbreFiscalDigital();
        $tfd['SelloCFD'] = 'foo';
        $this->comprobante->addChild(new Node('cfdi:Complemento', [], [$tfd]));

        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'TFDSELLO01');
        $this->assertStringContainsString('no coincide', $this->asserts->get('TFDSELLO01')->getExplanation());
    }

    public function testValidatorNoCertificadoSatEmpty()
    {
        $tfd = $this->newTimbreFiscalDigital();
        $tfd['SelloCFD'] = 'foo';
        $this->comprobante['Sello'] = $tfd['SelloCFD'];
        $this->comprobante->addChild(new Node('cfdi:Complemento', [], [$tfd]));

        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'TFDSELLO01');
        $this->assertStringContainsString('NoCertificadoSAT', $this->asserts->get('TFDSELLO01')->getExplanation());
    }

    public function testValidatorNoCertificadoSatInvalid()
    {
        $tfd = $this->newTimbreFiscalDigital();
        $tfd['SelloCFD'] = 'foo';
        $tfd['NoCertificadoSAT'] = '9876543210987654321A';
        $this->comprobante['Sello'] = $tfd['SelloCFD'];
        $this->comprobante->addChild(new Node('cfdi:Complemento', [], [$tfd]));

        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'TFDSELLO01');
        $this->assertStringContainsString('NoCertificadoSAT', $this->asserts->get('TFDSELLO01')->getExplanation());
    }

    public function testValidatorNoCertificadoSatNonExistent()
    {
        $tfd = $this->newTimbreFiscalDigital();
        $tfd['SelloCFD'] = 'foo';
        $tfd['NoCertificadoSAT'] = '98765432109876543210';
        $this->comprobante['Sello'] = $tfd['SelloCFD'];
        $this->comprobante->addChild(new Node('cfdi:Complemento', [], [$tfd]));

        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'TFDSELLO01');
        $this->assertStringContainsString(
            'obtener el certificado',
            $this->asserts->get('TFDSELLO01')->getExplanation()
        );
    }

    public function testValidatorSelloSatInvalid()
    {
        // to make it fail we change the FechaTimbrado
        $selloCfdi = $this->validSelloCfdi();
        $selloSat = $this->validSelloSat();
        $this->comprobante['Sello'] = $selloCfdi;
        $tfd = $this->newTimbreFiscalDigital([
            'NoCertificadoSAT' => $this->validCertificadoSAT(),
            'SelloSAT' => $selloSat,
            // these are required to create the source string
            'SelloCFD' => $selloCfdi,
            'FechaTimbrado' => '2018-01-12T08:17:53', // this was 54 seconds
            'RfcProvCertif' => 'DCD090706E42',
            'UUID' => 'CEE4BE01-ADFA-4DEB-8421-ADD60F0BEDAC',
        ]);
        $this->comprobante->addChild(new Node('cfdi:Complemento', [], [$tfd]));

        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'TFDSELLO01');
        $this->assertStringContainsString(
            'La verificación del timbrado fue negativa',
            $this->asserts->get('TFDSELLO01')->getExplanation()
        );
    }

    private function newTimbreFiscalDigital(array $attributes = []): NodeInterface
    {
        return new TimbreFiscalDigital($attributes);
    }

    private function validCertificadoSAT(): string
    {
        return '00001000000406258094';
    }

    private function validSelloCfdi(): string
    {
        return 'Xt7tK83WumikNMyx4Y/Z3R7D0rOjqTrrLu8wBlCnvXrpMFgWtyrcFUttGnevvUqCnQjuVUSpFcXqbzIQEUYNKFjxmtjwGHN+b'
            . '15xUvcnfqpJRBoJe2IKd5YMZqYp9NhTJIMBYsE7+fhP1+mHcKdKn9WwXrar8uXzISqPgZ97AORBsMWmXxbVWYRtqT4MX/Xq4yhbT4jao'
            . 'aut5AwhVzE1TUyZ10/C2gGySQeFVyEp9aqNScIxPotVDb7fMIWxsV26XODf6GK14B0TJNmRlCIfmfb2rQeskiYeiF5AQPb6Z2gmGLHcN'
            . 'ks7qC+eO3EsGVr1/ntmGcwTurbGXmE4/OAgdg==';
    }

    private function validSelloSat(): string
    {
        return 'IRy7wQnKnlIsN/pSZSR7qEm/SOJuLIbNjj/S3EAd278T2uo0t73KXfXzUbbfWOwpdZEAZeosq/yEiStTaf44ZonqRS1fq6oYk1'
            . '2udMmT4NFrEYbPEEKLn4lqdhuW4v8ZK2Vos/pjCtYtpT+/oVIXiWg9KrGVGuMvygRPWSmd+YJq3Jm7qTz0ON0vzBOvXralSZ4Q14xUvt'
            . '6ZDM9gYqIzTtCjIWaNrAdEYyqfZjvfy0uCyThh6HvCbMsX9gq4RsQj3SIoA56g+1SJevoZ6Jr722mDCLcPox3KCN75Bk8ALJI6G0weP7'
            . 'rQO5jEtulTRNWN3w+tlryZWElkD79MDZA6Zg==';
    }
}
