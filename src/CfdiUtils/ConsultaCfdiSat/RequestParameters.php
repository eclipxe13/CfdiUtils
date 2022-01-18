<?php

namespace CfdiUtils\ConsultaCfdiSat;

use CfdiUtils\Cfdi;
use UnexpectedValueException;

class RequestParameters
{
    /** @var string */
    private $version;

    /** @var string */
    private $rfcEmisor;

    /** @var string */
    private $rfcReceptor;

    /** @var string */
    private $total;

    /** @var float */
    private $totalFloat;

    /** @var string */
    private $uuid;

    /** @var string */
    private $sello;

    public function __construct(
        string $version,
        string $rfcEmisor,
        string $rfcReceptor,
        string $total,
        string $uuid,
        string $sello = ''
    ) {
        $this->setVersion($version);
        $this->rfcEmisor = $rfcEmisor;
        $this->rfcReceptor = $rfcReceptor;
        $this->total = $total;
        $this->totalFloat = (float) trim(str_replace(',', '', $this->total));
        $this->uuid = $uuid;
        $this->sello = $sello;
    }

    public static function createFromCfdi(Cfdi $cfdi): self
    {
        $qr = $cfdi->getQuickReader();
        return new self(
            $qr['version'],
            $qr->{'emisor'}['rfc'],
            $qr->{'receptor'}['rfc'],
            $qr['total'],
            $qr->{'complemento'}->{'timbrefiscaldigital'}['uuid'],
            $qr['sello']
        );
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function setVersion(string $version)
    {
        if (! in_array($version, ['3.2', '3.3', '4.0'], true)) {
            throw new UnexpectedValueException('The version is not allowed');
        }
        $this->version = $version;
    }

    public function getRfcEmisor(): string
    {
        return $this->rfcEmisor;
    }

    public function getRfcReceptor(): string
    {
        return $this->rfcReceptor;
    }

    public function getTotal(): string
    {
        return $this->total;
    }

    public function getTotalFloat(): float
    {
        return $this->totalFloat;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getSello(): string
    {
        return $this->sello;
    }

    public function expression(): string
    {
        if ('4.0' === $this->version) {
            return $this->expressionVersion40();
        }
        if ('3.3' === $this->version) {
            return $this->expressionVersion33();
        }
        if ('3.2' === $this->version) {
            return $this->expressionVersion32();
        }
        return '';
    }

    public function expressionVersion32(): string
    {
        return '?' . implode('&', [
            're=' . htmlentities($this->rfcEmisor, ENT_XML1),
            'rr=' . htmlentities($this->rfcReceptor, ENT_XML1),
            'tt=' . str_pad(number_format($this->totalFloat, 6, '.', ''), 17, '0', STR_PAD_LEFT),
            'id=' . $this->uuid,
        ]);
    }

    public function expressionVersion33(): string
    {
        $total = rtrim(number_format($this->totalFloat, 6, '.', ''), '0');
        if ('.' === substr($total, -1, 1)) {
            $total = $total . '0'; // add trailing zero
        }
        return 'https://verificacfdi.facturaelectronica.sat.gob.mx/default.aspx?' . implode('&', [
            'id=' . $this->uuid,
            're=' . htmlentities($this->rfcEmisor, ENT_XML1),
            'rr=' . htmlentities($this->rfcReceptor, ENT_XML1),
            'tt=' . $total,
            'fe=' . substr($this->sello, -8),
        ]);
    }

    public function expressionVersion40(): string
    {
        return $this->expressionVersion33();
    }
}
