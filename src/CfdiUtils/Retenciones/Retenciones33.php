<?php
namespace CfdiUtils\Retenciones;

use CfdiUtils\CadenaOrigen\DOMBuilder;
use CfdiUtils\CadenaOrigen\XsltBuilderInterface;
use CfdiUtils\CadenaOrigen\XsltBuilderPropertyInterface;
use CfdiUtils\CadenaOrigen\XsltBuilderPropertyTrait;
use CfdiUtils\Certificado\Certificado;
use CfdiUtils\Certificado\CertificadoPropertyInterface;
use CfdiUtils\Certificado\CertificadoPropertyTrait;
use CfdiUtils\Nodes\Node;
use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\Nodes\XmlNodeUtils;
use CfdiUtils\PemPrivateKey\PemPrivateKey;
use CfdiUtils\Validate\Asserts;
use CfdiUtils\Validate\Cfdi33\Xml\XmlFollowSchema;
use CfdiUtils\XmlResolver\XmlResolver;
use CfdiUtils\XmlResolver\XmlResolverPropertyInterface;
use CfdiUtils\XmlResolver\XmlResolverPropertyTrait;

class Retenciones33 implements
    CertificadoPropertyInterface,
    XmlResolverPropertyInterface,
    XsltBuilderPropertyInterface
{
    use CertificadoPropertyTrait;
    use XmlResolverPropertyTrait;
    use XsltBuilderPropertyTrait;
    const XSLT_1_0 = 'http://www.sat.gob.mx/esquemas/retencionpago/1/retenciones.xslt';
    const XSD_1_0 = 'http://www.sat.gob.mx/esquemas/retencionpago/1/retencionpagov1.xsd';
    const XMLNS_1_0 = 'http://www.sat.gob.mx/esquemas/retencionpago/1';

    /** @var NodeInterface */
    private $root;

    /**
     * Retenciones33 constructor.
     * @param array $comprobanteAttributes
     * @param NodeInterface[] $children
     * @param XmlResolver|null $xmlResolver
     * @param XsltBuilderInterface|null $xsltBuilder
     */
    public function __construct(
        array $comprobanteAttributes = [],
        array $children = [],
        XmlResolver $xmlResolver = null,
        XsltBuilderInterface $xsltBuilder = null
    ) {
        $this->root = new Node('retenciones:Retenciones', array_merge($comprobanteAttributes, [
            'xmlns:xsi' => 'http://www.w3.org/2001/XMLSchema-instance',
            'xmlns:retenciones' => self::XMLNS_1_0,
            'xsi:schemaLocation' => self::XMLNS_1_0 . ' ' . self::XSD_1_0,
            'Version' => '1.0',
        ]));
        $this->root->children()->add(...$children);
        $this->setXmlResolver($xmlResolver ? : new XmlResolver());
        $this->setXsltBuilder($xsltBuilder ? : new DOMBuilder());
    }

    public function rootNode(): NodeInterface
    {
        return $this->root;
    }

    public function putCertificado(Certificado $certificado)
    {
        $this->setCertificado($certificado);
        $cerfile = $certificado->getFilename();
        $this->root['NumCert'] = $certificado->getSerial();
        if (file_exists($cerfile)) {
            $this->root['Cert'] = base64_encode((string) file_get_contents($cerfile));
        }
    }

    public function buildCadenaDeOrigen(): string
    {
        if (! $this->hasXmlResolver()) {
            throw new \LogicException('Cannot build the cadena de origen since there is no xml resolver');
        }
        $xmlResolver = $this->getXmlResolver();
        $xsltLocation = $xmlResolver->resolve(self::XSLT_1_0, $xmlResolver::TYPE_XSLT);
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
        $this->root['Sello'] = base64_encode(
            $privateKey->sign($this->buildCadenaDeOrigen(), OPENSSL_ALGO_SHA1)
        );
    }

    public function validate(): Asserts
    {
        $validator = new XmlFollowSchema();
        $validator->setXmlResolver($this->getXmlResolver());
        $asserts = new Asserts();
        $validator->validate($this->root, $asserts);
        return $asserts;
    }

    public function asXml(): string
    {
        return XmlNodeUtils::nodeToXmlString($this->root, true);
    }
}
