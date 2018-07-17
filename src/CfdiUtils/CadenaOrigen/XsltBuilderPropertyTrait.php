<?php
namespace CfdiUtils\CadenaOrigen;

trait XsltBuilderPropertyTrait
{
    /** @var XsltBuilderInterface|null */
    private $xsltBuilder;

    public function getXsltBuilder(): XsltBuilderInterface
    {
        if (! $this->xsltBuilder instanceof XsltBuilderInterface) {
            throw new \LogicException('There is no current xsltBuilder');
        }
        return $this->xsltBuilder;
    }

    public function hasXsltBuilder(): bool
    {
        return ($this->xsltBuilder instanceof XsltBuilderInterface);
    }

    public function setXsltBuilder(XsltBuilderInterface $xsltBuilder)
    {
        $this->xsltBuilder = $xsltBuilder;
    }
}
