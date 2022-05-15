<?php

namespace CfdiUtils\Retenciones;

use CfdiUtils\CadenaOrigen\XsltBuilderInterface;
use CfdiUtils\CadenaOrigen\XsltBuilderPropertyInterface;
use CfdiUtils\CadenaOrigen\XsltBuilderPropertyTrait;
use CfdiUtils\Certificado\Certificado;
use CfdiUtils\Certificado\CertificadoPropertyInterface;
use CfdiUtils\Certificado\CertificadoPropertyTrait;
use CfdiUtils\Elements\Retenciones20\Retenciones;
use CfdiUtils\XmlResolver\XmlResolver;
use CfdiUtils\XmlResolver\XmlResolverPropertyInterface;
use CfdiUtils\XmlResolver\XmlResolverPropertyTrait;

class RetencionesCreator20 implements
    CertificadoPropertyInterface,
    XmlResolverPropertyInterface,
    XsltBuilderPropertyInterface
{
    use RetencionesCreatorTrait;
    use CertificadoPropertyTrait;
    use XmlResolverPropertyTrait;
    use XsltBuilderPropertyTrait;

    /** @var Retenciones */
    private $retenciones;

    public function __construct(
        array $retencionesAttributes = [],
        XmlResolver $xmlResolver = null,
        XsltBuilderInterface $xsltBuilder = null,
        Certificado $certificado = null
    ) {
        $this->retenciones = new Retenciones();
        $this->retencionesCreatorConstructor($retencionesAttributes, $certificado, $xmlResolver, $xsltBuilder);
    }

    public function retenciones(): Retenciones
    {
        return $this->retenciones;
    }

    public function putCertificado(Certificado $certificado)
    {
        $this->setCertificado($certificado);
        $this->retenciones['NoCertificado'] = $certificado->getSerial();
        $this->retenciones['Certificado'] = $certificado->getPemContentsOneLine();
        // maybe put Emisor values from Certificate, as in CfdiCreatorTrait
    }

    public function buildCadenaDeOrigen(): string
    {
        if (! $this->hasXmlResolver()) {
            throw new \LogicException('Cannot build the cadena de origen since there is no xml resolver');
        }
        $xmlResolver = $this->getXmlResolver();
        $xsltLocation = $xmlResolver->resolve(
            'http://www.sat.gob.mx/esquemas/retencionpago/2/retenciones.xslt',
            $xmlResolver::TYPE_XSLT
        );
        return $this->getXsltBuilder()->build($this->asXml(), $xsltLocation);
    }

    /** @internal This function is required by RetencionesCreatorTrait::addSello */
    private function getSelloAlgorithm(): int
    {
        return OPENSSL_ALGO_SHA256;
    }
}
