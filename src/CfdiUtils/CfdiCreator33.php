<?php
namespace CfdiUtils;

use CfdiUtils\CadenaOrigen\CadenaOrigenBuilder;
use CfdiUtils\Certificado\Certificado;
use CfdiUtils\Certificado\CertificadoPropertyTrait;
use CfdiUtils\Elements\Cfdi33\Comprobante;
use CfdiUtils\Elements\Cfdi33\Helpers\SumasConceptosWriter;
use CfdiUtils\Nodes\XmlNodeUtils;
use CfdiUtils\PemPrivateKey\PemPrivateKey;
use CfdiUtils\SumasConceptos\SumasConceptos;
use CfdiUtils\Validate\Asserts;
use CfdiUtils\Validate\Hydrater;
use CfdiUtils\Validate\MultiValidatorFactory;
use CfdiUtils\XmlResolver\XmlResolver;
use CfdiUtils\XmlResolver\XmlResolverPropertyTrait;

class CfdiCreator33
{
    use CertificadoPropertyTrait;
    use XmlResolverPropertyTrait;

    /** @var Comprobante */
    private $comprobante;

    /**
     * CfdiCreator33 constructor.
     * @param string[] $complementoAttributes
     * @param Certificado|null $certificado
     * @param XmlResolver|null $xmlResolver
     */
    public function __construct(
        array $complementoAttributes = [],
        Certificado $certificado = null,
        XmlResolver $xmlResolver = null
    ) {
        $this->comprobante = new Comprobante($complementoAttributes);
        $this->setXmlResolver($xmlResolver ? : new XmlResolver());
        if (null !== $certificado) {
            $this->putCertificado($certificado);
        }
    }

    public function comprobante(): Comprobante
    {
        return $this->comprobante;
    }

    public function putCertificado(Certificado $certificado, bool $putEmisorRfcNombre = true)
    {
        $this->setCertificado($certificado);
        $cerfile = $certificado->getFilename();
        $this->comprobante['NoCertificado'] = $certificado->getSerial();
        if (file_exists($cerfile)) {
            $this->comprobante['Certificado'] = base64_encode(file_get_contents($cerfile));
        }
        if ($putEmisorRfcNombre) {
            $this->comprobante->addEmisor([
                'Nombre' => $certificado->getName(),
                'Rfc' => $certificado->getRfc(),
            ]);
        }
    }

    public function asXml(): string
    {
        return XmlNodeUtils::nodeToXmlString($this->comprobante);
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
        $builder = new CadenaOrigenBuilder();
        return $builder->build($this->asXml(), $xsltLocation);
    }

    public function buildSumasConceptos(): SumasConceptos
    {
        return new SumasConceptos($this->comprobante);
    }

    public function addSumasConceptos(SumasConceptos $sumasConceptos = null, int $decimals = 2)
    {
        $sumasConceptos = $sumasConceptos ? : $this->buildSumasConceptos();
        $writer = new SumasConceptosWriter($this->comprobante, $sumasConceptos, $decimals);
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

    public function validate(): Asserts
    {
        $asserts = new Asserts();
        $hydrator = $this->newHydrater();

        // validate against discovered
        $factory = new MultiValidatorFactory();
        $validator = $factory->newCreated33();
        $validator->hydrate($hydrator);
        $validator->validate($this->comprobante(), $asserts);
        return $asserts;
    }

    public function newHydrater(): Hydrater
    {
        $hydrator = new Hydrater();
        $hydrator->setXmlString($this->asXml());
        $hydrator->setXmlResolver(($this->hasXmlResolver()) ? $this->getXmlResolver() : null);
        return $hydrator;
    }
}
