<?php

namespace CfdiUtils\Validate;

use CfdiUtils\CadenaOrigen\XsltBuilderPropertyInterface;
use CfdiUtils\CadenaOrigen\XsltBuilderPropertyTrait;
use CfdiUtils\Validate\Contracts\RequireXmlResolverInterface;
use CfdiUtils\Validate\Contracts\RequireXmlStringInterface;
use CfdiUtils\Validate\Contracts\RequireXsltBuilderInterface;
use CfdiUtils\Validate\Contracts\ValidatorInterface;
use CfdiUtils\Validate\Traits\XmlStringPropertyTrait;
use CfdiUtils\XmlResolver\XmlResolverPropertyInterface;
use CfdiUtils\XmlResolver\XmlResolverPropertyTrait;

class Hydrater implements XmlResolverPropertyInterface, XsltBuilderPropertyInterface
{
    use XmlResolverPropertyTrait;
    use XmlStringPropertyTrait;
    use XsltBuilderPropertyTrait;

    public function hydrate(ValidatorInterface $validator)
    {
        if ($validator instanceof RequireXmlStringInterface) {
            $validator->setXmlString($this->getXmlString());
        }
        if ($this->hasXmlResolver() && $validator instanceof RequireXmlResolverInterface) {
            $validator->setXmlResolver($this->getXmlResolver());
        }
        if ($validator instanceof RequireXsltBuilderInterface) {
            $validator->setXsltBuilder($this->getXsltBuilder());
        }
    }
}
