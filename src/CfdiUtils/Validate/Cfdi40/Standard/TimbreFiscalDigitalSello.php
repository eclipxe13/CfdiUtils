<?php

namespace CfdiUtils\Validate\Cfdi40\Standard;

use CfdiUtils\Validate\Cfdi40\Abstracts\AbstractDiscoverableVersion40;
use CfdiUtils\Validate\Common\TimbreFiscalDigitalSelloValidatorTrait;
use CfdiUtils\Validate\Contracts\RequireXmlResolverInterface;
use CfdiUtils\Validate\Contracts\RequireXsltBuilderInterface;

/**
 * TimbreFiscalDigitalSello
 *
 * Valida que:
 * - TFDSELLO01: El Sello SAT del Timbre Fiscal Digital corresponde al certificado SAT
 */
class TimbreFiscalDigitalSello extends AbstractDiscoverableVersion40 implements
    RequireXmlResolverInterface,
    RequireXsltBuilderInterface
{
    use TimbreFiscalDigitalSelloValidatorTrait;
}
