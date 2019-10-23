<?php

namespace CfdiUtils;

use CfdiUtils\CadenaOrigen\DOMBuilder;
use CfdiUtils\CadenaOrigen\XsltBuilderInterface;
use CfdiUtils\CadenaOrigen\XsltBuilderPropertyInterface;
use CfdiUtils\CadenaOrigen\XsltBuilderPropertyTrait;
use CfdiUtils\Certificado\Certificado;
use CfdiUtils\Certificado\CertificadoPropertyInterface;
use CfdiUtils\Certificado\CertificadoPropertyTrait;
use CfdiUtils\Elements\Cfdi33\Comprobante;
use CfdiUtils\Elements\Cfdi33\Helpers\SumasConceptosWriter;
use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\Nodes\NodeNsDefinitionsMover;
use CfdiUtils\Nodes\XmlNodeUtils;
use CfdiUtils\PemPrivateKey\PemPrivateKey;
use CfdiUtils\SumasConceptos\SumasConceptos;
use CfdiUtils\Validate\Asserts;
use CfdiUtils\Validate\Hydrater;
use CfdiUtils\Validate\MultiValidatorFactory;
use CfdiUtils\XmlResolver\XmlResolver;
use CfdiUtils\XmlResolver\XmlResolverPropertyInterface;
use CfdiUtils\XmlResolver\XmlResolverPropertyTrait;

class CfdiCreator33 implements
    CertificadoPropertyInterface,
    XmlResolverPropertyInterface,
    XsltBuilderPropertyInterface
{
    use CertificadoPropertyTrait;
    use XmlResolverPropertyTrait;
    use XsltBuilderPropertyTrait;

    /** @var Comprobante */
    private $comprobante;

    /**
     * CfdiCreator33 constructor.
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
        $this->comprobante = new Comprobante($comprobanteAttributes);
        $this->setXmlResolver($xmlResolver ? : new XmlResolver());
        if (null !== $certificado) {
            $this->putCertificado($certificado);
        }
        $this->setXsltBuilder($xsltBuilder ? : new DOMBuilder());
    }

    public static function newUsingNode(
        NodeInterface $node,
        Certificado $certificado = null,
        XmlResolver $xmlResolver = null
    ): self {
        $new = new self([], $certificado, $xmlResolver);
        $comprobante = $new->comprobante();
        $comprobante->addAttributes($node->attributes()->exportArray());
        foreach ($node as $child) {
            $comprobante->addChild($child);
        }
        return $new;
    }

    public function comprobante(): Comprobante
    {
        return $this->comprobante;
    }

    public function putCertificado(Certificado $certificado, bool $putEmisorRfcNombre = true)
    {
        $this->setCertificado($certificado);
        $this->comprobante['NoCertificado'] = $certificado->getSerial();
        $pemContents = implode('', preg_grep('/^((?!-).)*$/', explode(PHP_EOL, $certificado->getPemContents())));
        $this->comprobante['Certificado'] = $pemContents;
        if ($putEmisorRfcNombre) {
            $emisor = $this->comprobante->searchNode('cfdi:Emisor');
            if (null === $emisor) {
                $emisor = $this->comprobante->getEmisor();
            }
            $emisor->addAttributes([
                'Nombre' => $certificado->getName(),
                'Rfc' => $certificado->getRfc(),
            ]);
        }
    }

    public function asXml(): string
    {
        return XmlNodeUtils::nodeToXmlString($this->comprobante, true);
    }

    public function moveSatDefinitionsToComprobante()
    {
        $nodeNsDefinitionsMover = new NodeNsDefinitionsMover();
        $nodeNsDefinitionsMover->setNamespaceFilter(
            function (string $namespaceUri): bool {
                return ('http://www.sat.gob.mx/' === (substr($namespaceUri, 0, 22) ?: ''));
            }
        );
        $nodeNsDefinitionsMover->process($this->comprobante);
    }

    public function saveXml(string $filename): bool
    {
        return (false !== file_put_contents($filename, $this->asXml()));
    }

    public function buildCadenaDeOrigen(): string
    {
        if (! $this->hasXmlResolver()) {
            throw new \LogicException(
                'Cannot build the cadena de origen since there is no xml resolver'
            );
        }
        $xsltLocation = $this->getXmlResolver()->resolveCadenaOrigenLocation('3.3');
        return $this->getXsltBuilder()->build($this->asXml(), $xsltLocation);
    }

    public function buildSumasConceptos(int $precision = 2): SumasConceptos
    {
        return new SumasConceptos($this->comprobante, $precision);
    }

    public function addSumasConceptos(SumasConceptos $sumasConceptos = null, int $precision = 2)
    {
        if (null === $sumasConceptos) {
            $sumasConceptos = $this->buildSumasConceptos($precision);
        }
        $writer = new SumasConceptosWriter($this->comprobante, $sumasConceptos, $precision);
        $writer->put();
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
        $this->comprobante['Sello'] = base64_encode(
            $privateKey->sign($this->buildCadenaDeOrigen(), OPENSSL_ALGO_SHA256)
        );
    }

    /**
     * @return Asserts|\CfdiUtils\Validate\Assert[]
     */
    public function validate(): Asserts
    {
        $factory = new MultiValidatorFactory();
        $validator = $factory->newCreated33();

        $hydrater = new Hydrater();
        $hydrater->setXmlString($this->asXml());
        $hydrater->setXmlResolver(($this->hasXmlResolver()) ? $this->getXmlResolver() : null);
        $hydrater->setXsltBuilder($this->getXsltBuilder());
        $validator->hydrate($hydrater);

        $asserts = new Asserts();
        $validator->validate($this->comprobante(), $asserts);

        return $asserts;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        try {
            return $this->asXml();
        } catch (\Throwable $ex) {
            return '';
        }
    }
}
