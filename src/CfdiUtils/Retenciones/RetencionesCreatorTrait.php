<?php

namespace CfdiUtils\Retenciones;

use CfdiUtils\CadenaOrigen\DOMBuilder;
use CfdiUtils\CadenaOrigen\XsltBuilderInterface;
use CfdiUtils\CadenaOrigen\XsltBuilderPropertyTrait;
use CfdiUtils\Certificado\Certificado;
use CfdiUtils\Certificado\CertificadoPropertyTrait;
use CfdiUtils\Elements\Common\AbstractElement;
use CfdiUtils\Nodes\XmlNodeUtils;
use CfdiUtils\PemPrivateKey\PemPrivateKey;
use CfdiUtils\Utils\SatNsDefinitionsMover;
use CfdiUtils\Validate\Asserts;
use CfdiUtils\Validate\Xml\XmlFollowSchema;
use CfdiUtils\XmlResolver\XmlResolver;
use CfdiUtils\XmlResolver\XmlResolverPropertyTrait;

/**
 * @method void putCertificado(Certificado $certificado)
 * @method string buildCadenaDeOrigen()
 * @property AbstractElement $retenciones
 */
trait RetencionesCreatorTrait
{
    use CertificadoPropertyTrait;
    use XmlResolverPropertyTrait;
    use XsltBuilderPropertyTrait;

    private function retencionesCreatorConstructor(
        array $retencionesAttributes = [],
        ?Certificado $certificado = null,
        ?XmlResolver $xmlResolver = null,
        ?XsltBuilderInterface $xsltBuilder = null,
    ): void {
        $this->retenciones->addAttributes($retencionesAttributes);
        $this->setXmlResolver($xmlResolver ?: new XmlResolver());
        if (null !== $certificado) {
            $this->putCertificado($certificado);
        }
        $this->setXsltBuilder($xsltBuilder ?: new DOMBuilder());
    }

    public function addSello(string $key, string $passPhrase = ''): void
    {
        // create private key
        $privateKey = new PemPrivateKey($key);
        if (! $privateKey->open($passPhrase)) {
            throw new \RuntimeException('Cannot open the private key');
        }

        // check privatekey belongs to certificado
        if (
            $this->hasCertificado()
            && ! $privateKey->belongsTo($this->getCertificado()->getPemContents())
        ) {
            throw new \RuntimeException('The private key does not belong to the current certificate');
        }

        // create sign and set into Sello attribute
        $this->retenciones['Sello'] = base64_encode(
            $privateKey->sign($this->buildCadenaDeOrigen(), $this->getSelloAlgorithm())
        );
    }

    public function validate(): Asserts
    {
        $validator = new XmlFollowSchema();
        $validator->setXmlResolver($this->getXmlResolver());
        $asserts = new Asserts();
        $validator->validate($this->retenciones, $asserts);
        return $asserts;
    }

    public function asXml(): string
    {
        return XmlNodeUtils::nodeToXmlString($this->retenciones, true);
    }

    public function moveSatDefinitionsToRetenciones(): void
    {
        $mover = new SatNsDefinitionsMover();
        $mover->move($this->retenciones);
    }

    private function buildCadenaDeOrigenFromXsltLocation(string $xsltLocation): string
    {
        if (! $this->hasXmlResolver()) {
            throw new \LogicException('Cannot build the cadena de origen since there is no xml resolver');
        }
        if (! $this->hasXsltBuilder()) {
            throw new \LogicException('Cannot build the cadena de origen since there is no xslt builder');
        }

        $xmlResolver = $this->getXmlResolver();
        $xsltLocation = $xmlResolver->resolve($xsltLocation, $xmlResolver::TYPE_XSLT);

        return $this->getXsltBuilder()->build($this->asXml(), $xsltLocation);
    }
}
