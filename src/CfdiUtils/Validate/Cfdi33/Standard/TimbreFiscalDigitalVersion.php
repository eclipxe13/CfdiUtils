<?php

namespace CfdiUtils\Validate\Cfdi33\Standard;

use CfdiUtils\Validate\Cfdi33\Abstracts\AbstractDiscoverableVersion33;
use CfdiUtils\Validate\Common\TimbreFiscalDigitalVersionValidatorTrait;

/**
 * TimbreFiscalDigitalVersion
 *
 * Valida que:
 * - TFDVERSION01: Si existe el complemento timbre fiscal digital, entonces su versión debe ser 1.1
 */
class TimbreFiscalDigitalVersion extends AbstractDiscoverableVersion33
{
    use TimbreFiscalDigitalVersionValidatorTrait;
}
