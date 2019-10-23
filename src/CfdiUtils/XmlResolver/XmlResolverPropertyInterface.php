<?php

namespace CfdiUtils\XmlResolver;

interface XmlResolverPropertyInterface
{
    public function hasXmlResolver(): bool;

    public function getXmlResolver(): XmlResolver;

    public function setXmlResolver(XmlResolver $xmlResolver = null);
}
