<?php

namespace CfdiUtils\Validate\Cfdi33\Standard;

use CfdiUtils\CadenaOrigen\CfdiDefaultLocations;
use CfdiUtils\CadenaOrigen\XsltBuilderPropertyTrait;
use CfdiUtils\Certificado\NodeCertificado;
use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\Validate\Asserts;
use CfdiUtils\Validate\Cfdi33\Abstracts\AbstractDiscoverableVersion33;
use CfdiUtils\Validate\Contracts\RequireXmlResolverInterface;
use CfdiUtils\Validate\Contracts\RequireXmlStringInterface;
use CfdiUtils\Validate\Contracts\RequireXsltBuilderInterface;
use CfdiUtils\Validate\Status;
use CfdiUtils\Validate\Traits\XmlStringPropertyTrait;
use CfdiUtils\XmlResolver\XmlResolverPropertyTrait;
use DateTimeImmutable;
use PhpCfdi\Credentials\Certificate;

/**
 * SelloDigitalCertificado
 *
 * Valida que:
 * - SELLO01: Se puede obtener el certificado del comprobante
 * - SELLO02: El número de certificado del comprobante igual al encontrado en el certificado
 * - SELLO03: El RFC del comprobante igual al encontrado en el certificado
 * - SELLO04: El nombre del emisor del comprobante es igual al encontrado en el certificado
 * - SELLO05: La fecha del documento es mayor o igual a la fecha de inicio de vigencia del certificado
 * - SELLO06: La fecha del documento menor o igual a la fecha de fin de vigencia del certificado
 * - SELLO07: El sello del comprobante está en base 64
 * - SELLO08: El sello del comprobante coincide con el certificado y la cadena de origen generada
 */
class SelloDigitalCertificado extends AbstractDiscoverableVersion33 implements
    RequireXmlStringInterface,
    RequireXmlResolverInterface,
    RequireXsltBuilderInterface
{
    /** @var Asserts */
    private $asserts;

    /** @var Certificate */
    private $certificado;

    use XmlResolverPropertyTrait;
    use XmlStringPropertyTrait;
    use XsltBuilderPropertyTrait;

    private function registerAsserts()
    {
        $asserts = [
            'SELLO01' => 'Se puede obtener el certificado del comprobante',
            'SELLO02' => 'El número de certificado del comprobante igual al encontrado en el certificado',
            'SELLO03' => 'El RFC del comprobante igual al encontrado en el certificado',
            'SELLO04' => 'El nombre del emisor del comprobante es igual al encontrado en el certificado',
            'SELLO05' => 'La fecha del documento es mayor o igual a la fecha de inicio de vigencia del certificado',
            'SELLO06' => 'La fecha del documento menor o igual a la fecha de fin de vigencia del certificado',
            'SELLO07' => 'El sello del comprobante está en base 64',
            'SELLO08' => 'El sello del comprobante coincide con el certificado y la cadena de origen generada',
        ];
        foreach ($asserts as $code => $title) {
            $this->asserts->put($code, $title);
        }
    }

    public function validate(NodeInterface $comprobante, Asserts $asserts)
    {
        $this->asserts = $asserts;
        $this->registerAsserts();

        // create the certificate
        $extractor = new NodeCertificado($comprobante);
        try {
            $certificado = $extractor->obtain();
        } catch (\Exception $exception) {
            $this->asserts->putStatus('SELLO01', Status::error(), $exception->getMessage());
            return;
        }
        $this->certificado = $certificado;
        $this->asserts->putStatus('SELLO01', Status::ok());

        // start validations
        $this->validateNoCertificado($comprobante['NoCertificado']);
        $hasRegistroFiscal = $comprobante->searchNodes(
            'cfdi:Complemento',
            'registrofiscal:CFDIRegistroFiscal'
        )->count() > 0;
        $noCertificadoSAT = $comprobante->searchAttribute(
            'cfdi:Complemento',
            'tfd:TimbreFiscalDigital',
            'NoCertificadoSAT'
        );
        if (! $hasRegistroFiscal || $comprobante['NoCertificado'] !== $noCertificadoSAT) {
            // validate emisor rfc
            $this->validateRfc($comprobante->searchAttribute('cfdi:Emisor', 'Rfc'));
            // validate emisor nombre
            $this->validateNombre($comprobante->searchAttribute('cfdi:Emisor', 'Nombre'));
        }
        $this->validateFecha($comprobante['Fecha']);

        $this->validateSello($comprobante['Sello']);
    }

    private function buildCadenaOrigen(): string
    {
        $xmlResolver = $this->getXmlResolver();
        $xsltLocation = $xmlResolver->resolve(CfdiDefaultLocations::location('3.3'), $xmlResolver::TYPE_XSLT);
        return $this->getXsltBuilder()->build($this->getXmlString(), $xsltLocation);
    }

    private function validateNoCertificado(string $noCertificado)
    {
        $expectedNumber = $this->certificado->serialNumber()->bytes();
        $this->asserts->putStatus(
            'SELLO02',
            Status::when($expectedNumber === $noCertificado),
            sprintf('Certificado: %s, Comprobante: %s', $expectedNumber, $noCertificado)
        );
    }

    private function validateRfc(string $emisorRfc)
    {
        $expectedRfc = $this->certificado->rfc();
        $this->asserts->put(
            'SELLO03',
            'El RFC del comprobante igual al encontrado en el certificado',
            Status::when($expectedRfc === $emisorRfc),
            sprintf('Rfc certificado: %s, Rfc comprobante: %s', $expectedRfc, $emisorRfc)
        );
    }

    private function validateNombre(string $emisorNombre)
    {
        if ('' === $emisorNombre) {
            return;
        }
        $this->asserts->putStatus(
            'SELLO04',
            Status::when($this->compareNames($this->certificado->legalName(), $emisorNombre)),
            sprintf('Nombre certificado: %s, Nombre comprobante: %s', $this->certificado->legalName(), $emisorNombre)
        );
    }

    private function validateFecha(string $fechaSource)
    {
        $fechaTimeStamp = ('' === $fechaSource) ? 0 : intval(strtotime($fechaSource));
        if (0 === $fechaTimeStamp) {
            return;
        }
        $fecha = (new DateTimeImmutable())->setTimestamp($fechaTimeStamp);
        $validFrom = $this->certificado->validFromDateTime();
        $validTo = $this->certificado->validToDateTime();
        $explanation = vsprintf('Validez del certificado: %s hasta %s, Fecha comprobante: %s', [
            $validFrom->format('Y-m-d H:i:s'),
            $validTo->format('Y-m-d H:i:s'),
            $fecha->format('Y-m-d H:i:s'),
        ]);
        $this->asserts->putStatus('SELLO05', Status::when($fecha >= $validFrom), $explanation);
        $this->asserts->putStatus('SELLO06', Status::when($fecha <= $validTo), $explanation);
    }

    private function validateSello(string $selloBase64)
    {
        $sello = $this->obtainSello($selloBase64);
        if ('' === $sello) {
            return;
        }
        $cadena = $this->buildCadenaOrigen();
        $selloIsValid = $this->certificado->publicKey()->verify($cadena, $sello, OPENSSL_ALGO_SHA256);
        $this->asserts->putStatus(
            'SELLO08',
            Status::when($selloIsValid),
            'La verificación del sello del CFDI no coincide, probablemente el CFDI fue alterado o mal generado'
        );
    }

    private function obtainSello(string $selloBase64): string
    {
        // this silence error operator is intentional, if $selloBase64 is malformed
        // then it will return false, and I will recognize the error
        $sello = @base64_decode($selloBase64, true);
        $this->asserts->putStatus('SELLO07', Status::when(false !== $sello));
        return (string) $sello;
    }

    protected function compareNames(string $first, string $second): bool
    {
        return ($this->castNombre($first) === $this->castNombre($second));
    }

    protected function castNombre(string $nombre): string
    {
        $nombre = iconv('UTF-8', 'ASCII//TRANSLIT', $nombre) ?: '';
        $nombre = str_replace([' ', '-', ',', '.', '#', '&', "'", '"', '~', '¨', '^'], '', $nombre);
        return mb_strtoupper($nombre);
    }
}
