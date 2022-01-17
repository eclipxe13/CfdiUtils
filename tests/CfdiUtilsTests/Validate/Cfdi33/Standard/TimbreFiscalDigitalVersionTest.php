<?php

namespace CfdiUtilsTests\Validate\Cfdi33\Standard;

use CfdiUtils\Validate\Cfdi33\Standard\TimbreFiscalDigitalVersion;
use CfdiUtilsTests\Validate\Common\TimbreFiscalDigital11VersionTestTrait;
use CfdiUtilsTests\Validate\Validate33TestCase;

final class TimbreFiscalDigitalVersionTest extends Validate33TestCase
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
