<?php
namespace CfdiUtils\Validate;

use CfdiUtils\Validate\Contracts\RequireXmlResolverInterface;
use CfdiUtils\Validate\Contracts\RequireXmlStringInterface;
use CfdiUtils\Validate\Contracts\ValidatorInterface;
use CfdiUtils\Validate\Traits\XmlStringPropertyTrait;
use CfdiUtils\XmlResolver\XmlResolverPropertyInterface;
use CfdiUtils\XmlResolver\XmlResolverPropertyTrait;

class Hydrater implements XmlResolverPropertyInterface
{
    use XmlResolverPropertyTrait;
    use XmlStringPropertyTrait;

    public function hydrate(ValidatorInterface $validator)
    {
        if ($validator instanceof RequireXmlStringInterface) {
            $validator->setXmlString($this->getXmlString());
        }
        if ($this->hasXmlResolver() && $validator instanceof RequireXmlResolverInterface) {
            $validator->setXmlResolver($this->getXmlResolver());
        }
    }
}
