<?php

namespace CfdiUtils\Retenciones;

use CfdiUtils\CadenaOrigen\XsltBuilderInterface;
use CfdiUtils\CadenaOrigen\XsltBuilderPropertyInterface;
use CfdiUtils\CadenaOrigen\XsltBuilderPropertyTrait;
use CfdiUtils\Certificado\Certificado;
use CfdiUtils\Certificado\CertificadoPropertyInterface;
use CfdiUtils\Certificado\CertificadoPropertyTrait;
use CfdiUtils\Elements\Retenciones10\Retenciones;
use CfdiUtils\XmlResolver\XmlResolver;
use CfdiUtils\XmlResolver\XmlResolverPropertyInterface;
use CfdiUtils\XmlResolver\XmlResolverPropertyTrait;

class RetencionesCreator10 implements
    CertificadoPropertyInterface,
    XmlResolverPropertyInterface,
    XsltBuilderPropertyInterface
{
    use RetencionesCreatorTrait;
    use CertificadoPropertyTrait;
    use XmlResolverPropertyTrait;
    use XsltBuilderPropertyTrait;

    private Retenciones $retenciones;

    public function __construct(
        array $retencionesAttributes = [],
        ?XmlResolver $xmlResolver = null,
        ?XsltBuilderInterface $xsltBuilder = null,
        ?Certificado $certificado = null,
    ) {
        $this->retenciones = new Retenciones();
        $this->retencionesCreatorConstructor($retencionesAttributes, $certificado, $xmlResolver, $xsltBuilder);
    }

    public function retenciones(): Retenciones
    {
        /** @phpstan-var Retenciones PHPStan 1.10.13 identify retenciones as AbstractElement */
        return $this->retenciones;
    }

    public function putCertificado(Certificado $certificado): void
    {
        $this->setCertificado($certificado);
        $this->retenciones['NumCert'] = $certificado->getSerial();
        $this->retenciones['Cert'] = $certificado->getPemContentsOneLine();
    }

    public function buildCadenaDeOrigen(): string
    {
        return $this->buildCadenaDeOrigenFromXsltLocation(
            'http://www.sat.gob.mx/esquemas/retencionpago/1/retenciones.xslt'
        );
    }

    /** @internal This function is required by RetencionesCreatorTrait::addSello */
    private function getSelloAlgorithm(): int
    {
        return OPENSSL_ALGO_SHA1;
    }
}
