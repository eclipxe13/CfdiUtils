<?php

namespace CfdiUtilsTests\Validate\Cfdi33\Standard;

use CfdiUtils\Validate\Cfdi33\Standard\TimbreFiscalDigitalVersion;
use CfdiUtils\Validate\Contracts\ValidatorInterface;
use CfdiUtilsTests\Validate\Common\TimbreFiscalDigital11VersionTestTrait;
use CfdiUtilsTests\Validate\Validate33TestCase;

final class TimbreFiscalDigitalVersionTest extends Validate33TestCase
{
    use TimbreFiscalDigital11VersionTestTrait;

    /** @var TimbreFiscalDigitalVersion */
    protected ValidatorInterface $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new TimbreFiscalDigitalVersion();
    }
}
