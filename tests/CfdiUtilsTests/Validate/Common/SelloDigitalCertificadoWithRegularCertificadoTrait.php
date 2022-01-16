<?php

namespace CfdiUtilsTests\Validate\Common;

use CfdiUtils\Utils\Format;
use CfdiUtils\Validate\Cfdi33\Standard\SelloDigitalCertificado;
use CfdiUtils\Validate\Contracts\DiscoverableCreateInterface;
use CfdiUtils\Validate\Contracts\RequireXmlResolverInterface;
use CfdiUtils\Validate\Contracts\RequireXmlStringInterface;
use CfdiUtils\Validate\Status;

trait SelloDigitalCertificadoWithRegularCertificadoTrait
{
    public function testObjectSpecification()
    {
        $this->assertInstanceOf(DiscoverableCreateInterface::class, $this->validator);
        $this->assertInstanceOf(RequireXmlStringInterface::class, $this->validator);
        $this->assertInstanceOf(RequireXmlResolverInterface::class, $this->validator);
    }

    public function testValidateWithoutCertificado()
    {
        $this->runValidate();

        $this->assertStatusEqualsCode(Status::error(), 'SELLO01');
        $this->assertCount(8, $this->asserts);
        foreach (range(2, 8) as $i) {
            $this->assertStatusEqualsCode(Status::none(), 'SELLO0' . $i);
        }
    }

    public function testValidateBadCertificadoNumber()
    {
        $this->setUpCertificado([
            'NoCertificado' => 'X',
        ]);

        $this->runValidate();

        $this->assertStatusEqualsCode(Status::error(), 'SELLO02');
    }

    public function testValidateBadRfcAndNameNumber()
    {
        $this->setUpCertificado([], [
            'Rfc' => null,
            'Nombre' => 'Foo Bar',
        ]);

        $this->runValidate();

        $this->assertStatusEqualsCode(Status::error(), 'SELLO03');
        $this->assertStatusEqualsCode(Status::error(), 'SELLO04');
    }

    public function testValidateWithEqualButNotIdenticalName()
    {
        //    change case, and punctuation to original name
        //                   ESCUELA KEMPER URGATE SA DE CV
        $this->setUpCertificado([], [
            'Nombre' => 'ESCUELA - Kemper Urgate, S.A. DE C.V.',
        ]);

        $this->runValidate();

        $this->assertStatusEqualsCode(Status::ok(), 'SELLO04');
    }

    public function testValidateBadLowerFecha()
    {
        $validLowerDate = strtotime('2019-06-17T19:44:13+00:00');
        $this->setUpCertificado(['Fecha' => Format::datetime($validLowerDate - 1)]);
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'SELLO05');
        $this->assertStatusEqualsCode(Status::ok(), 'SELLO06');
    }

    public function testValidateOkLowerFecha()
    {
        $validLowerDate = strtotime('2019-06-17T19:44:14+00:00');
        $this->setUpCertificado(['Fecha' => Format::datetime($validLowerDate)]);
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::ok(), 'SELLO05');
        $this->assertStatusEqualsCode(Status::ok(), 'SELLO06');
    }

    public function testValidateBadHigherFecha()
    {
        $validHigherDate = strtotime('2023-06-17T19:44:15+00:00');
        $this->setUpCertificado(['Fecha' => Format::datetime($validHigherDate + 1)]);
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::ok(), 'SELLO05');
        $this->assertStatusEqualsCode(Status::error(), 'SELLO06');
    }

    public function testValidateOkHigherFecha()
    {
        $validHigherDate = strtotime('2023-06-17T19:44:14+00:00');
        $this->setUpCertificado(['Fecha' => Format::datetime($validHigherDate)]);
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::ok(), 'SELLO05');
        $this->assertStatusEqualsCode(Status::ok(), 'SELLO06');
    }

    public function testValidateBadSelloBase64()
    {
        $this->setUpCertificado(['Sello' => 'ñ']);
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'SELLO07');
    }

    public function testValidateBadSello()
    {
        $this->setupCfdiFile('cfdi33-valid.xml');
        $this->comprobante['Sello'] = $this->comprobante['Certificado'];
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'SELLO08');
    }

    public function testValidateOk()
    {
        $this->setupCfdiFile('cfdi33-valid.xml');
        $this->runValidate();
        foreach (range(1, 8) as $i) {
            $this->assertStatusEqualsCode(Status::ok(), 'SELLO0' . $i);
        }
        $this->assertCount(8, $this->asserts, 'All 8 were are tested');
    }

    /**
     * This test does not care about locales
     *
     * @param bool $expected
     * @param string $first
     * @param string $second
     * @testWith [true, "ABC", "ABC"]
     *           [true, "Empresa \"Equis\"", "Empresa Equis"]
     *           [false, "Empresa Equis Sa de Cv", "Empresa Equis SA CV"]
     */
    public function testCompareNamesBasicChars(bool $expected, string $first, string $second)
    {
        $validator = new class() extends SelloDigitalCertificado {
            public function testCompareNames(string $first, string $second): bool
            {
                return $this->compareNames($first, $second);
            }
        };
        $this->assertSame($expected, $validator->testCompareNames($first, $second));
    }

    /**
     * This test will perform comparison only when locales are set up or can be set,
     * otherwise the test will be skipped.
     *
     * @param string $first
     * @param string $second
     * @testWith ["Cesar Gomez Aguero", "César Gómez Agüero"]
     *           ["Cesar Gomez Aguero", "CÉSAR GÓMEZ AGÜERO"]
     *           ["CAÑA SA", "Cana SA"]
     */
    public function testCompareNamesExtendedChars(string $first, string $second)
    {
        $validator = new class() extends SelloDigitalCertificado {
            public function testCompareNames(string $first, string $second): bool
            {
                return $this->compareNames($first, $second);
            }

            public function testCastNombre(string $name): string
            {
                return $this->castNombre($name);
            }
        };

        $currentLocale = setlocale(LC_CTYPE, '0') ?: 'C';
        if ('C' === $currentLocale || 'POSIX' === $currentLocale) {
            if (false === setlocale(LC_CTYPE, 'es_MX.utf8', 'en_US.utf8', 'es_MX', 'en_US', 'spanish', 'english')) {
                $this->markTestSkipped('Cannot compare names without LC_CTYPE configured');
            }
        }

        try {
            $this->assertTrue($validator->testCompareNames($first, $second), sprintf(
                'Unable to assert name equals (%s, %s) [%s like %s] with locale %s',
                $first,
                $second,
                $validator->testCastNombre($first),
                $validator->testCastNombre($second),
                setlocale(LC_CTYPE, '0') ?: 'C'
            ));
        } finally {
            setlocale(LC_CTYPE, $currentLocale);
        }
    }
}
