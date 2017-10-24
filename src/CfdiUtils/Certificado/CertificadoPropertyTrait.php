<?php
namespace CfdiUtils\Certificado;

trait CertificadoPropertyTrait
{
    private $certificado;

    public function hasCertificado(): bool
    {
        return (null !== $this->certificado);
    }

    public function getCertificado(): Certificado
    {
        if (null === $this->certificado) {
            throw new \LogicException('There is no current certificado');
        }
        return $this->certificado;
    }

    public function setCertificado(Certificado $certificado = null)
    {
        $this->certificado = $certificado;
    }
}
