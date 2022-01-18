<?php

namespace CfdiUtilsTests\Validate\Cfdi40\Standard;

use CfdiUtils\Validate\Cfdi40\Standard\TimbreFiscalDigitalSello;
use CfdiUtilsTests\Validate\Common\TimbreFiscalDigital11SelloTestTrait;
use CfdiUtilsTests\Validate\Validate40TestCase;

final class TimbreFiscalDigitalSelloTest extends Validate40TestCase
{
    use TimbreFiscalDigital11SelloTestTrait;

    /** @var TimbreFiscalDigitalSello */
    protected $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new TimbreFiscalDigitalSello();
        $this->hydrater->hydrate($this->validator);
    }
}
