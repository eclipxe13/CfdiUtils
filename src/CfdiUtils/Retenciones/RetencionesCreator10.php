<?php

namespace CfdiUtils\Retenciones;

use CfdiUtils\CadenaOrigen\DOMBuilder;
use CfdiUtils\CadenaOrigen\XsltBuilderInterface;
use CfdiUtils\CadenaOrigen\XsltBuilderPropertyInterface;
use CfdiUtils\CadenaOrigen\XsltBuilderPropertyTrait;
use CfdiUtils\Certificado\Certificado;
use CfdiUtils\Certificado\CertificadoPropertyInterface;
use CfdiUtils\Certificado\CertificadoPropertyTrait;
use CfdiUtils\Elements\Retenciones10\Retenciones;
use CfdiUtils\Nodes\XmlNodeUtils;
use CfdiUtils\PemPrivateKey\PemPrivateKey;
use CfdiUtils\Validate\Asserts;
use CfdiUtils\Validate\Cfdi33\Xml\XmlFollowSchema;
use CfdiUtils\XmlResolver\XmlResolver;
use CfdiUtils\XmlResolver\XmlResolverPropertyInterface;
use CfdiUtils\XmlResolver\XmlResolverPropertyTrait;

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

    public function putCertificado(Certificado $certificado)
    {
        $this->setCertificado($certificado);
        $this->retenciones['NumCert'] = $certificado->getSerial();
        $pemContents = implode('', preg_grep('/^((?!-).)*$/', explode(PHP_EOL, $certificado->getPemContents())));
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

    public function addSello(string $key, string $passPhrase = '')
    {
        // create private key
        $privateKey = new PemPrivateKey($key);
        if (! $privateKey->open($passPhrase)) {
            throw new \RuntimeException('Cannot open the private key');
        }

        // check privatekey belongs to certificado
        if ($this->hasCertificado()) {
            if (! $privateKey->belongsTo($this->getCertificado()->getPemContents())) {
                throw new \RuntimeException('The private key does not belong to the current certificate');
            }
        }

        // create sign and set into Sello attribute
        $this->retenciones['Sello'] = base64_encode(
            $privateKey->sign($this->buildCadenaDeOrigen(), OPENSSL_ALGO_SHA1)
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
}
