<?php

namespace CfdiUtils\Validate\Cfdi33\Standard;

use CfdiUtils\CadenaOrigen\XsltBuilderPropertyTrait;
use CfdiUtils\Certificado\SatCertificateNumber;
use CfdiUtils\Internals\TemporaryFile;
use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\Nodes\XmlNodeUtils;
use CfdiUtils\TimbreFiscalDigital\TfdCadenaDeOrigen;
use CfdiUtils\Validate\Asserts;
use CfdiUtils\Validate\Cfdi33\Abstracts\AbstractDiscoverableVersion33;
use CfdiUtils\Validate\Contracts\RequireXmlResolverInterface;
use CfdiUtils\Validate\Contracts\RequireXsltBuilderInterface;
use CfdiUtils\Validate\Status;
use CfdiUtils\XmlResolver\XmlResolverPropertyTrait;
use PhpCfdi\Credentials\Certificate;

/**
 * TimbreFiscalDigitalSello
 *
 * Valida que:
 * - TFDSELLO01: El Sello SAT del Timbre Fiscal Digital corresponde al certificado SAT
 */
class TimbreFiscalDigitalSello extends AbstractDiscoverableVersion33 implements
    RequireXmlResolverInterface,
    RequireXsltBuilderInterface
{
    use XmlResolverPropertyTrait;
    use XsltBuilderPropertyTrait;

    public function validate(NodeInterface $comprobante, Asserts $asserts)
    {
        $assert = $asserts->put(
            'TFDSELLO01',
            'El Sello SAT del Timbre Fiscal Digital corresponde al certificado SAT'
        );

        if (! $this->hasXmlResolver()) {
            $assert->setExplanation('No se puede hacer la validación porque carece de un objeto resolvedor');
            return;
        }

        $tfd = $comprobante->searchNode('cfdi:Complemento', 'tfd:TimbreFiscalDigital');
        if (null === $tfd) {
            $assert->setExplanation('El CFDI no contiene un Timbre Fiscal Digital');
            return;
        }

        if ('1.1' !== $tfd['Version']) {
            $assert->setExplanation('La versión del timbre fiscal digital no es 1.1');
            return;
        }

        $validationSellosMatch = $comprobante['Sello'] !== $tfd['SelloCFD'];
        if ($validationSellosMatch) {
            $assert->setStatus(
                Status::error(),
                'El atributo SelloCFD del Timbre Fiscal Digital no coincide con el atributo Sello del Comprobante'
            );
            return;
        }

        $certificadoSAT = $tfd['NoCertificadoSAT'];
        if (! SatCertificateNumber::isValidCertificateNumber($certificadoSAT)) {
            $assert->setStatus(
                Status::error(),
                sprintf('El atributo NoCertificadoSAT con el valor "%s" no es válido', $certificadoSAT)
            );
            return;
        }

        try {
            $resolver = $this->getXmlResolver();
            $certificadoUrl = (new SatCertificateNumber($certificadoSAT))->remoteUrl();
            if (! $resolver->hasLocalPath()) {
                $temporaryFile = TemporaryFile::create();
                $certificadoFile = $temporaryFile->getPath();
                $resolver->getDownloader()->downloadTo($certificadoUrl, $certificadoFile);
                $certificado = Certificate::openFile($certificadoFile);
                $temporaryFile->remove();
            } else {
                $certificadoFile = $resolver->resolve($certificadoUrl, $resolver::TYPE_CER);
                $certificado = Certificate::openFile($certificadoFile);
            }
        } catch (\Throwable $ex) {
            $assert->setStatus(
                Status::error(),
                sprintf('No se ha podido obtener el certificado %s: %s', $certificadoSAT, $ex->getMessage())
            );
            return;
        }

        $tfdCadenaOrigen = new TfdCadenaDeOrigen($resolver, $this->getXsltBuilder());
        $source = $tfdCadenaOrigen->build(XmlNodeUtils::nodeToXmlString($tfd), $tfd['Version']);
        $signature = strval(base64_decode($tfd['SelloSAT']));

        $verification = $certificado->publicKey()->verify($source, $signature, OPENSSL_ALGO_SHA256);
        if (! $verification) {
            $assert->setStatus(
                Status::error(),
                'La verificación del timbrado fue negativa,'
                    . ' posiblemente el CFDI fue modificado después de general el sello'
            );
            return;
        }
        $assert->setStatus(Status::ok());
    }
}
