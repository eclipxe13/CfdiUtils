<?php

namespace CfdiUtils\Certificado;

use PhpCfdi\Credentials\Certificate;

trait CertificadoPropertyTrait
{
    /** @var Certificate */
    private $certificado;

    public function getCertificado(): Certificate
    {
        return $this->certificado;
    }

    public function setCertificado(Certificate $certificado)
    {
        $this->certificado = $certificado;
    }
}
