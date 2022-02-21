<?php

namespace CfdiUtils;

use CfdiUtils\CadenaOrigen\XsltBuilderPropertyInterface;
use CfdiUtils\Validate\CfdiValidatorTrait;
use CfdiUtils\Validate\MultiValidator;
use CfdiUtils\Validate\MultiValidatorFactory;
use CfdiUtils\XmlResolver\XmlResolverPropertyInterface;

class CfdiValidator40 implements XmlResolverPropertyInterface, XsltBuilderPropertyInterface
{
    use CfdiValidatorTrait;

    protected function createVersionedMultiValidator(): MultiValidator
    {
        $factory = new MultiValidatorFactory();
        return $factory->newReceived40();
    }
}
