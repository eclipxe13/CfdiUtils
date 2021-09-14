<?php

namespace CfdiUtils;

use CfdiUtils\CadenaOrigen\CfdiDefaultLocations;
use CfdiUtils\CadenaOrigen\DOMBuilder;
use CfdiUtils\CadenaOrigen\XsltBuilderInterface;
use CfdiUtils\CadenaOrigen\XsltBuilderPropertyInterface;
use CfdiUtils\CadenaOrigen\XsltBuilderPropertyTrait;
use CfdiUtils\Certificado\CertificadoPropertyInterface;
use CfdiUtils\Certificado\CertificadoPropertyTrait;
use CfdiUtils\Elements\Cfdi33\Comprobante;
use CfdiUtils\Elements\Cfdi33\Helpers\SumasConceptosWriter;
use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\Nodes\NodeNsDefinitionsMover;
use CfdiUtils\Nodes\XmlNodeUtils;
use CfdiUtils\SumasConceptos\SumasConceptos;
use CfdiUtils\Validate\Asserts;
use CfdiUtils\Validate\Hydrater;
use CfdiUtils\Validate\MultiValidatorFactory;
use CfdiUtils\XmlResolver\XmlResolver;
use CfdiUtils\XmlResolver\XmlResolverPropertyInterface;
use CfdiUtils\XmlResolver\XmlResolverPropertyTrait;
use PhpCfdi\Credentials\Certificate;
use PhpCfdi\Credentials\PrivateKey;

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
     *
     * @param array $comprobanteAttributes
     * @param XmlResolver|null $xmlResolver
     * @param XsltBuilderInterface|null $xsltBuilder
     */
    public function __construct(
        array $comprobanteAttributes = [],
        XmlResolver $xmlResolver = null,
        XsltBuilderInterface $xsltBuilder = null
    ) {
        $this->comprobante = new Comprobante($comprobanteAttributes);
        $this->setXmlResolver($xmlResolver ?? new XmlResolver());
        $this->setXsltBuilder($xsltBuilder ?? new DOMBuilder());
    }

    public static function newUsingNode(
        NodeInterface $node,
        XmlResolver $xmlResolver = null,
        XsltBuilderInterface $xsltBuilder = null
    ): self {
        $new = new self([], $xmlResolver, $xsltBuilder);
        $comprobante = $new->comprobante();
        $comprobante->addAttributes($node->attributes()->exportArray());
        foreach ($node as $child) {
            $comprobante->addChild($child);
        }
        $certificateContents = (new Certificado\NodeCertificado($comprobante))->extract();
        if ('' !== $certificateContents) {
            $new->putCertificado(new Certificate($certificateContents), false);
        }
        return $new;
    }

    public function comprobante(): Comprobante
    {
        return $this->comprobante;
    }

    public function putCertificado(Certificate $certificado, bool $putEmisorRfcNombre = true): void
    {
        $this->setCertificado($certificado);
        $this->comprobante['NoCertificado'] = $certificado->serialNumber()->bytes();
        $pemContents = implode('', preg_grep('/^((?!-).)*$/', explode(PHP_EOL, $certificado->pemAsOneLine())));
        $this->comprobante['Certificado'] = $pemContents;
        if ($putEmisorRfcNombre) {
            $emisor = $this->comprobante->searchNode('cfdi:Emisor');
            if (null === $emisor) {
                $emisor = $this->comprobante->getEmisor();
            }
            $emisor->addAttributes([
                'Nombre' => $certificado->legalName(),
                'Rfc' => $certificado->rfc(),
            ]);
        }
    }

    public function asXml(): string
    {
        return XmlNodeUtils::nodeToXmlString($this->comprobante, true);
    }

    public function moveSatDefinitionsToComprobante(): void
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
        $xmlResolver = $this->getXmlResolver();
        $xsltLocation = $xmlResolver->resolve(CfdiDefaultLocations::location('3.3'), $xmlResolver::TYPE_XSLT);
        return $this->getXsltBuilder()->build($this->asXml(), $xsltLocation);
    }

    public function buildSumasConceptos(int $precision = 2): SumasConceptos
    {
        return new SumasConceptos($this->comprobante, $precision);
    }

    public function addSumasConceptos(SumasConceptos $sumasConceptos = null, int $precision = 2): void
    {
        if (null === $sumasConceptos) {
            $sumasConceptos = $this->buildSumasConceptos($precision);
        }
        $writer = new SumasConceptosWriter($this->comprobante, $sumasConceptos, $precision);
        $writer->put();
    }

    public function addSello(PrivateKey $privateKey): void
    {
        if (! $privateKey->belongsTo($this->getCertificado())) {
            throw new \RuntimeException('The private key does not belong to the current certificate');
        }

        $sourceString = $this->buildCadenaDeOrigen();

        $this->comprobante['Sello'] = base64_encode($privateKey->sign($sourceString, OPENSSL_ALGO_SHA256));
    }

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

    public function __toString(): string
    {
        try {
            return $this->asXml();
        } catch (\Throwable $ex) {
            return '';
        }
    }
}
