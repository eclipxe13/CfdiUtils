<?php

namespace CfdiUtils\Certificado;

trait CertificadoPropertyTrait
{
    private ?Certificado $certificado = null;

    public function hasCertificado(): bool
    {
        return $this->certificado instanceof Certificado;
    }

    public function getCertificado(): Certificado
    {
        if (! $this->certificado instanceof Certificado) {
            throw new \LogicException('There is no current certificado');
        }
        return $this->certificado;
    }

    public function setCertificado(?Certificado $certificado = null): void
    {
        $this->certificado = $certificado;
    }
}
