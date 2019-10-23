<?php

namespace CfdiUtils;

use CfdiUtils\CadenaOrigen\DOMBuilder;
use CfdiUtils\CadenaOrigen\XsltBuilderInterface;
use CfdiUtils\CadenaOrigen\XsltBuilderPropertyInterface;
use CfdiUtils\CadenaOrigen\XsltBuilderPropertyTrait;
use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\Nodes\XmlNodeUtils;
use CfdiUtils\Validate\Asserts;
use CfdiUtils\Validate\Hydrater;
use CfdiUtils\Validate\MultiValidatorFactory;
use CfdiUtils\XmlResolver\XmlResolver;
use CfdiUtils\XmlResolver\XmlResolverPropertyInterface;
use CfdiUtils\XmlResolver\XmlResolverPropertyTrait;

class CfdiValidator33 implements XmlResolverPropertyInterface, XsltBuilderPropertyInterface
{
    use XmlResolverPropertyTrait;
    use XsltBuilderPropertyTrait;

    /**
     * This class uses a default XmlResolver if not provided or null.
     * If you really want to remove the XmlResolver then use the method setXmlResolver after construction.
     *
     * @param XmlResolver|null $xmlResolver
     * @param XsltBuilderInterface|null $xsltBuilder
     */
    public function __construct(XmlResolver $xmlResolver = null, XsltBuilderInterface $xsltBuilder = null)
    {
        $this->setXmlResolver($xmlResolver ? : new XmlResolver());
        $this->setXsltBuilder($xsltBuilder ? : new DOMBuilder());
    }

    /**
     * Validate and return the asserts from the validation process.
     * This method can use a xml string and a NodeInterface,
     * is your responsability that the node is the representation of the content.
     *
     * @param string $xmlString
     * @param NodeInterface $node
     * @return Asserts|\CfdiUtils\Validate\Assert[]
     */
    public function validate(string $xmlString, NodeInterface $node): Asserts
    {
        if ('' === $xmlString) {
            throw new \UnexpectedValueException('The xml string to validate cannot be empty');
        }

        $factory = new MultiValidatorFactory();
        $validator = $factory->newReceived33();

        $hydrater = new Hydrater();
        $hydrater->setXmlString($xmlString);
        $hydrater->setXmlResolver(($this->hasXmlResolver()) ? $this->getXmlResolver() : null);
        $hydrater->setXsltBuilder($this->getXsltBuilder());
        $validator->hydrate($hydrater);

        $asserts = new Asserts();
        $validator->validate($node, $asserts);

        return $asserts;
    }

    /**
     * Validate and return the asserts from the validation process based on a xml string
     *
     * @param string $xmlString
     * @return Asserts|\CfdiUtils\Validate\Assert[]
     */
    public function validateXml(string $xmlString): Asserts
    {
        return $this->validate($xmlString, XmlNodeUtils::nodeFromXmlString($xmlString));
    }

    /**
     * Validate and return the asserts from the validation process based on a node interface object
     *
     * @param NodeInterface $node
     * @return Asserts|\CfdiUtils\Validate\Assert[]
     */
    public function validateNode(NodeInterface $node): Asserts
    {
        return $this->validate(XmlNodeUtils::nodeToXmlString($node), $node);
    }
}
