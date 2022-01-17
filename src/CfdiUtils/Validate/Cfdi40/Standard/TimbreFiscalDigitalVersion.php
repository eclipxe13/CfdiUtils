<?php

namespace CfdiUtils\Validate\Cfdi40\Standard;

use CfdiUtils\Validate\Cfdi40\Abstracts\AbstractDiscoverableVersion40;
use CfdiUtils\Validate\Common\TimbreFiscalDigitalVersionValidatorTrait;

/**
 * TimbreFiscalDigitalVersion
 *
 * Valida que:
 * - TFDVERSION01: Si existe el complemento timbre fiscal digital, entonces su versión debe ser 1.1
 */
class TimbreFiscalDigitalVersion extends AbstractDiscoverableVersion40
{
    use TimbreFiscalDigitalVersionValidatorTrait;
}
