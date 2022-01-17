<?php

namespace CfdiUtilsTests\Validate\Cfdi40\Standard;

use CfdiUtils\Validate\Cfdi40\Standard\TimbreFiscalDigitalVersion;
use CfdiUtilsTests\Validate\Common\TimbreFiscalDigital11VersionTestTrait;
use CfdiUtilsTests\Validate\Validate40TestCase;

final class TimbreFiscalDigitalVersionTest extends Validate40TestCase
{
    use TimbreFiscalDigital11VersionTestTrait;

    /** @var  TimbreFiscalDigitalVersion */
    protected $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new TimbreFiscalDigitalVersion();
    }
}
