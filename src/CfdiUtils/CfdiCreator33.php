<?php

namespace CfdiUtils;

use CfdiUtils\CadenaOrigen\XsltBuilderInterface;
use CfdiUtils\CadenaOrigen\XsltBuilderPropertyInterface;
use CfdiUtils\Certificado\Certificado;
use CfdiUtils\Certificado\CertificadoPropertyInterface;
use CfdiUtils\Elements\Cfdi33\Comprobante;
use CfdiUtils\Validate\Asserts;
use CfdiUtils\Validate\MultiValidatorFactory;
use CfdiUtils\XmlResolver\XmlResolver;
use CfdiUtils\XmlResolver\XmlResolverPropertyInterface;

class CfdiCreator33 implements
    CertificadoPropertyInterface,
    XmlResolverPropertyInterface,
    XsltBuilderPropertyInterface
{
    use CfdiCreatorTrait;

    /** @var Comprobante */
    private $comprobante;

    /**
     * CfdiCreator33 constructor.
     *
     * @param array $comprobanteAttributes
     * @param Certificado|null $certificado
     * @param XmlResolver|null $xmlResolver
     * @param XsltBuilderInterface|null $xsltBuilder
     */
    public function __construct(
        array $comprobanteAttributes = [],
        Certificado $certificado = null,
        XmlResolver $xmlResolver = null,
        XsltBuilderInterface $xsltBuilder = null
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
        $xsltLocation = $this->getXmlResolver()->resolveCadenaOrigenLocation('3.3');
        return $this->buildCadenaDeOrigenUsingXsltLocation($xsltLocation);
    }

    public function validate(): Asserts
    {
        $validator = (new MultiValidatorFactory())->newCreated33();
        return $this->validateUsingValidator($validator);
    }
}
