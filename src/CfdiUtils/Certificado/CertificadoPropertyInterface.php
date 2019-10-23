<?php

namespace CfdiUtils\Certificado;

interface CertificadoPropertyInterface
{
    public function getCertificado(): Certificado;

    public function setCertificado(Certificado $Certificado);
}
