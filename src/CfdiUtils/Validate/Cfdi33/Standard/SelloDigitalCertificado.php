<?php

namespace CfdiUtils\Validate\Cfdi33\Standard;

use CfdiUtils\Validate\Cfdi33\Abstracts\AbstractDiscoverableVersion33;
use CfdiUtils\Validate\Common\SelloDigitalCertificadoValidatorTrait;
use CfdiUtils\Validate\Contracts\RequireXmlResolverInterface;
use CfdiUtils\Validate\Contracts\RequireXmlStringInterface;
use CfdiUtils\Validate\Contracts\RequireXsltBuilderInterface;
use CfdiUtils\Validate\Status;

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
    use SelloDigitalCertificadoValidatorTrait;

    protected function validateNombre(string $emisorNombre, string $rfc): void
    {
        if ('' === $emisorNombre) {
            return; // name is optional
        }
        $this->asserts->putStatus(
            'SELLO04',
            Status::when($this->compareNames($this->certificado->getName(), $emisorNombre)),
            sprintf('Nombre certificado: %s, Nombre comprobante: %s.', $this->certificado->getName(), $emisorNombre)
        );
    }
}
