<?php
namespace CfdiUtilsTests\Validate\FakeObjects;

use CfdiUtils\Validate\Contracts\RequireXmlResolverInterface;
use CfdiUtils\XmlResolver\XmlResolverPropertyTrait;

class ImplementationRequireXmlResolverInterface extends ImplementationValidatorInterface implements
    RequireXmlResolverInterface
{
    use XmlResolverPropertyTrait;
}
