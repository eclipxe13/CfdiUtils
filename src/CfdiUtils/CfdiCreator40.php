<?php

namespace CfdiUtils;

use CfdiUtils\CadenaOrigen\XsltBuilderInterface;
use CfdiUtils\CadenaOrigen\XsltBuilderPropertyInterface;
use CfdiUtils\Certificado\Certificado;
use CfdiUtils\Certificado\CertificadoPropertyInterface;
use CfdiUtils\Elements\Cfdi40\Comprobante;
use CfdiUtils\Validate\Asserts;
use CfdiUtils\Validate\MultiValidatorFactory;
use CfdiUtils\XmlResolver\XmlResolver;
use CfdiUtils\XmlResolver\XmlResolverPropertyInterface;

class CfdiCreator40 implements
    CertificadoPropertyInterface,
    XmlResolverPropertyInterface,
    XsltBuilderPropertyInterface
{
    use CfdiCreatorTrait;

    private Comprobante $comprobante;

    /**
     * CfdiCreator40 constructor.
     */
    public function __construct(
        array $comprobanteAttributes = [],
        ?Certificado $certificado = null,
        ?XmlResolver $xmlResolver = null,
        ?XsltBuilderInterface $xsltBuilder = null,
    ) {
        $this->comprobante = new Comprobante();
        $this->cfdiCreatorConstructor($comprobanteAttributes, $certificado, $xmlResolver, $xsltBuilder);
    }

    public function comprobante(): Comprobante
    {
        return $this->comprobante;
    }

    public function buildCadenaDeOrigen(): string
    {
        $xsltLocation = $this->getXmlResolver()->resolveCadenaOrigenLocation('4.0');
        return $this->buildCadenaDeOrigenUsingXsltLocation($xsltLocation);
    }

    public function validate(): Asserts
    {
        $validator = (new MultiValidatorFactory())->newCreated40();
        return $this->validateUsingValidator($validator);
    }
}
