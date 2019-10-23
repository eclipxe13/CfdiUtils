<?php

namespace CfdiUtils\Certificado;

use CfdiUtils\Nodes\NodeInterface;

class NodeCertificado
{
    /** @var NodeInterface */
    private $comprobante;

    public function __construct(NodeInterface $comprobante)
    {
        $this->comprobante = $comprobante;
    }

    /**
     * Extract the certificate from Comprobante->certificado
     * If the node does not exists return an empty string
     * The returned string is no longer base64 encoded
     *
     * @return string
     *
     * @throws \RuntimeException if the certificado attribute is not a valid base64 encoded string
     */
    public function extract(): string
    {
        $version = $this->getVersion();
        if ('3.2' === $version) {
            $attr = 'certificado';
        } elseif ('3.3' === $version) {
            $attr = 'Certificado';
        } else {
            throw new \RuntimeException('Unsupported or unknown version');
        }
        $certificateBase64 = $this->comprobante->searchAttribute($attr);
        if ('' === $certificateBase64) {
            return '';
        }

        $certificateBin = (string) base64_decode($certificateBase64, true);
        if ('' === $certificateBin) {
            throw new \RuntimeException('The certificado attribute is not a valid base64 encoded string');
        }

        return $certificateBin;
    }

    private function getVersion(): string
    {
        if ('3.2' === $this->comprobante->searchAttribute('version')) {
            return '3.2';
        }
        if ('3.3' === $this->comprobante->searchAttribute('Version')) {
            return '3.3';
        }
        return '';
    }

    /**
     * Extract and save the certificate into an specified location
     *
     * @see extract
     *
     * @param string $filename
     * @return void
     *
     * @throws \UnexpectedValueException if the filename to store the certificate is empty
     * @throws \RuntimeException if the certificado attribute is empty
     * @throws \RuntimeException if cannot write the contents of the certificate
     */
    public function save(string $filename)
    {
        if ('' === $filename) {
            throw new \UnexpectedValueException('The filename to store the certificate is empty');
        }
        $certificado = $this->extract();
        if ('' === $certificado) {
            throw new \RuntimeException('The certificado attribute is empty');
        }
        try {
            if (false === file_put_contents($filename, $certificado)) {
                throw new \RuntimeException('file_put_contents returns FALSE');
            }
        } catch (\Throwable $error) {
            throw new \RuntimeException("Unable to write the certificate contents into $filename", 0, $error);
        }
    }

    /**
     * Return a Certificado object from the Comprobante->Certificado attribute
     * The temporary certificate is stored into a temporary folder and removed
     * after the certificado is loaded. If you need to persist the certificate
     * use the saveCertificado method instead
     *
     * @see save
     *
     * @return Certificado
     */
    public function obtain(): Certificado
    {
        $certificado = $this->extract();
        if ('' === $certificado) {
            throw new \RuntimeException('The certificado attribute is empty');
        }
        return new Certificado(base64_encode($certificado));
    }
}
