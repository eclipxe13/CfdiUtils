<?php

namespace CfdiUtils;

use CfdiUtils\CadenaOrigen\DOMBuilder;
use CfdiUtils\CadenaOrigen\XsltBuilderInterface;
use CfdiUtils\CadenaOrigen\XsltBuilderPropertyTrait;
use CfdiUtils\Certificado\Certificado;
use CfdiUtils\Certificado\CertificadoPropertyTrait;
use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\Nodes\NodeNsDefinitionsMover;
use CfdiUtils\Nodes\XmlNodeUtils;
use CfdiUtils\PemPrivateKey\PemPrivateKey;
use CfdiUtils\SumasConceptos\SumasConceptos;
use CfdiUtils\SumasConceptos\SumasConceptosWriter;
use CfdiUtils\Validate\Asserts;
use CfdiUtils\Validate\Hydrater;
use CfdiUtils\Validate\MultiValidator;
use CfdiUtils\XmlResolver\XmlResolver;
use CfdiUtils\XmlResolver\XmlResolverPropertyTrait;

trait CfdiCreatorTrait
{
    use CertificadoPropertyTrait;
    use XmlResolverPropertyTrait;
    use XsltBuilderPropertyTrait;

    private function cfdiCreatorConstructor(
        array $comprobanteAttributes = [],
        Certificado $certificado = null,
        XmlResolver $xmlResolver = null,
        XsltBuilderInterface $xsltBuilder = null
    ): void {
        $this->comprobante->addAttributes($comprobanteAttributes);
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
        $comprobante = $new->comprobante;
        $comprobante->addAttributes($node->attributes()->exportArray());
        foreach ($node as $child) {
            $comprobante->addChild($child);
        }
        return $new;
    }

    public function putCertificado(Certificado $certificado, bool $putEmisorRfcNombre = true)
    {
        $this->setCertificado($certificado);
        $this->comprobante['NoCertificado'] = $certificado->getSerial();
        $this->comprobante['Certificado'] = $certificado->getPemContentsOneLine();
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

    private function buildCadenaDeOrigenUsingXsltLocation(string $xsltLocation): string
    {
        if (! $this->hasXmlResolver()) {
            throw new \LogicException(
                'Cannot build the cadena de origen since there is no xml resolver'
            );
        }
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

        // check private key belongs to certificado
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

    private function validateUsingValidator(MultiValidator $validator): Asserts
    {
        $hydrater = new Hydrater();
        $hydrater->setXmlString($this->asXml());
        $hydrater->setXmlResolver(($this->hasXmlResolver()) ? $this->getXmlResolver() : null);
        $hydrater->setXsltBuilder($this->getXsltBuilder());
        $validator->hydrate($hydrater);

        $asserts = new Asserts();
        $validator->validate($this->comprobante, $asserts);

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
