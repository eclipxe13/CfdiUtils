<?php

namespace CfdiUtils\Validate;

use CfdiUtils\Validate\Cfdi33\Xml\XmlFollowSchema;

class MultiValidatorFactory
{
    /** @var Discoverer */
    private $discoverer;

    public function __construct(Discoverer $discoverer = null)
    {
        $this->discoverer = $discoverer ? : new Discoverer();
    }

    public function getDiscoverer(): Discoverer
    {
        return $this->discoverer;
    }

    public function newCreated33(): MultiValidator
    {
        $multiValidator = new MultiValidator('3.3');
        $multiValidator->add(new XmlFollowSchema());
        $this->addDiscovered($multiValidator, __NAMESPACE__ . '\Cfdi33\Standard', __DIR__ . '/Cfdi33/Standard');
        $this->addDiscovered(
            $multiValidator,
            __NAMESPACE__ . '\Cfdi33\RecepcionPagos',
            __DIR__ . '/Cfdi33/RecepcionPagos'
        );
        return $multiValidator;
    }

    public function newReceived33(): MultiValidator
    {
        return $this->newCreated33();
    }

    public function addDiscovered(MultiValidator $multiValidator, string $namespacePrefix, string $directory)
    {
        $multiValidator->addMulti(
            ...$this->discoverer->discoverInFolder($namespacePrefix, $directory)
        );
    }
}
