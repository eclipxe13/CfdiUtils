<?php

namespace CfdiUtils\Certificado;

use PhpCfdi\Credentials\Certificate;

interface CertificadoPropertyInterface
{
    public function getCertificado(): Certificate;

    public function setCertificado(Certificate $certificate);
}
