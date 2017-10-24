<?php
namespace CfdiUtils\XmlResolver;

trait XmlResolverPropertyTrait
{
    /**
     * @var XmlResolver|null
     */
    private $xmlResolver;

    public function hasXmlResolver(): bool
    {
        return (null !== $this->xmlResolver);
    }

    public function getXmlResolver(): XmlResolver
    {
        if (null === $this->xmlResolver) {
            throw new \LogicException('There is no current xmlResolver');
        }
        return $this->xmlResolver;
    }

    public function setXmlResolver(XmlResolver $xmlResolver = null)
    {
        $this->xmlResolver = $xmlResolver;
    }
}
