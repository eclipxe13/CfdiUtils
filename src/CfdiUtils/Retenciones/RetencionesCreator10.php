<?php

namespace CfdiUtils\Retenciones;

use CfdiUtils\CadenaOrigen\DOMBuilder;
use CfdiUtils\CadenaOrigen\XsltBuilderInterface;
use CfdiUtils\CadenaOrigen\XsltBuilderPropertyInterface;
use CfdiUtils\CadenaOrigen\XsltBuilderPropertyTrait;
use CfdiUtils\Certificado\CertificadoPropertyInterface;
use CfdiUtils\Certificado\CertificadoPropertyTrait;
use CfdiUtils\Elements\Retenciones10\Retenciones;
use CfdiUtils\Nodes\XmlNodeUtils;
use CfdiUtils\Validate\Asserts;
use CfdiUtils\Validate\Cfdi33\Xml\XmlFollowSchema;
use CfdiUtils\XmlResolver\XmlResolver;
use CfdiUtils\XmlResolver\XmlResolverPropertyInterface;
use CfdiUtils\XmlResolver\XmlResolverPropertyTrait;
use PhpCfdi\Credentials\Certificate;
use PhpCfdi\Credentials\PrivateKey;

class RetencionesCreator10 implements
    CertificadoPropertyInterface,
    XmlResolverPropertyInterface,
    XsltBuilderPropertyInterface
{
    use CertificadoPropertyTrait;
    use XmlResolverPropertyTrait;
    use XsltBuilderPropertyTrait;

    /** @var Retenciones */
    private $retenciones;

    public function __construct(
        array $comprobanteAttributes = [],
        XmlResolver $xmlResolver = null,
        XsltBuilderInterface $xsltBuilder = null
    ) {
        $this->retenciones = new Retenciones($comprobanteAttributes);
        $this->setXmlResolver($xmlResolver ? : new XmlResolver());
        $this->setXsltBuilder($xsltBuilder ? : new DOMBuilder());
    }

    public function retenciones(): Retenciones
    {
        return $this->retenciones;
    }

    public function putCertificado(Certificate $certificado): void
    {
        $this->setCertificado($certificado);
        $this->retenciones['NumCert'] = $certificado->serialNumber()->bytes();
        $pemContents = implode('', preg_grep('/^((?!-).)*$/', explode(PHP_EOL, $certificado->pemAsOneLine())));
        $this->retenciones['Cert'] = $pemContents;
    }

    public function buildCadenaDeOrigen(): string
    {
        if (! $this->hasXmlResolver()) {
            throw new \LogicException('Cannot build the cadena de origen since there is no xml resolver');
        }
        $xmlResolver = $this->getXmlResolver();
        $xsltLocation = $xmlResolver->resolve(
            'http://www.sat.gob.mx/esquemas/retencionpago/1/retenciones.xslt',
            $xmlResolver::TYPE_XSLT
        );
        return $this->getXsltBuilder()->build($this->asXml(), $xsltLocation);
    }

    public function addSello(PrivateKey $privateKey): void
    {
        if (! $privateKey->belongsTo($this->getCertificado())) {
            throw new \RuntimeException('The private key does not belong to the current certificate');
        }

        $sourceString = $this->buildCadenaDeOrigen();

        $this->retenciones['Sello'] = base64_encode($privateKey->sign($sourceString, OPENSSL_ALGO_SHA1));
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
}
