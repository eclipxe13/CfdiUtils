<?php

namespace CfdiUtilsTests\Validate\FakeObjects;

use CfdiUtils\Validate\Contracts\RequireXmlStringInterface;
use CfdiUtils\Validate\Traits\XmlStringPropertyTrait;

class ImplementationRequireXmlStringInterface extends ImplementationValidatorInterface implements
    RequireXmlStringInterface
{
    use XmlStringPropertyTrait;
}
