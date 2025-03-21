<?php

namespace CfdiUtils\Validate;

use CfdiUtils\CadenaOrigen\DOMBuilder;
use CfdiUtils\CadenaOrigen\XsltBuilderInterface;
use CfdiUtils\CadenaOrigen\XsltBuilderPropertyTrait;
use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\Nodes\XmlNodeUtils;
use CfdiUtils\XmlResolver\XmlResolver;
use CfdiUtils\XmlResolver\XmlResolverPropertyTrait;

trait CfdiValidatorTrait
{
    use XmlResolverPropertyTrait;
    use XsltBuilderPropertyTrait;

    abstract protected function createVersionedMultiValidator(): MultiValidator;

    /**
     * This class uses a default XmlResolver if not provided or null.
     * If you really want to remove the XmlResolver then use the method setXmlResolver after construction.
     */
    public function __construct(?XmlResolver $xmlResolver = null, ?XsltBuilderInterface $xsltBuilder = null)
    {
        $this->setXmlResolver($xmlResolver ?: new XmlResolver());
        $this->setXsltBuilder($xsltBuilder ?: new DOMBuilder());
    }

    /**
     * Validate and return the asserts from the validation process.
     * This method can use a xml string and a NodeInterface,
     * is your responsibility that the node is the representation of the content.
     *
     * @return Asserts|Assert[]
     */
    public function validate(string $xmlString, NodeInterface $node): Asserts
    {
        if ('' === $xmlString) {
            throw new \UnexpectedValueException('The xml string to validate cannot be empty');
        }

        $validator = $this->createVersionedMultiValidator();

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
     * @return Asserts|Assert[]
     */
    public function validateXml(string $xmlString): Asserts
    {
        return $this->validate($xmlString, XmlNodeUtils::nodeFromXmlString($xmlString));
    }

    /**
     * Validate and return the asserts from the validation process based on a node interface object
     *
     * @return Asserts|Assert[]
     */
    public function validateNode(NodeInterface $node): Asserts
    {
        return $this->validate(XmlNodeUtils::nodeToXmlString($node), $node);
    }
}
