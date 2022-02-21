<?php

namespace CfdiUtils\Validate\Cfdi33\Standard;

use CfdiUtils\Validate\Cfdi33\Abstracts\AbstractDiscoverableVersion33;
use CfdiUtils\Validate\Common\TimbreFiscalDigitalSelloValidatorTrait;
use CfdiUtils\Validate\Contracts\RequireXmlResolverInterface;
use CfdiUtils\Validate\Contracts\RequireXsltBuilderInterface;

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
    use TimbreFiscalDigitalSelloValidatorTrait;
}
