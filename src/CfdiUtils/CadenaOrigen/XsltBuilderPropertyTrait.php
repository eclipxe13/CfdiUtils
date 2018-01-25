<?php
namespace CfdiUtils\CadenaOrigen;

trait XsltBuilderPropertyTrait
{
    /**
     * @var XsltBuilderInterface
     */
    private $xsltBuilder;

    public function getXsltBuilder(): XsltBuilderInterface
    {
        if (! $this->xsltBuilder instanceof XsltBuilderInterface) {
            throw new \LogicException('There is no current xsltBuilder');
        }
        return $this->xsltBuilder;
    }

    public function setXsltBuilder(XsltBuilderInterface $xsltBuilder)
    {
        $this->xsltBuilder = $xsltBuilder;
    }
}
