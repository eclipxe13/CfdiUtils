<?php

namespace CfdiUtils\ConsultaCfdiSat;

class StatusResponse
{
    /** @var string */
    private $code;

    /** @var string */
    private $cfdi;

    /** @var string */
    private $cancellable;

    /** @var string */
    private $cancellationStatus;

    public function __construct(
        string $statusCode,
        string $status,
        string $cancellable = '',
        string $cancellationStatus = ''
    ) {
        $this->code = $statusCode;
        $this->cfdi = $status;
        $this->cancellable = $cancellable;
        $this->cancellationStatus = $cancellationStatus;
    }

    /**
     * Status request code, values:
     * - S - Comprobante obtenido satisfactoriamente
     * - N - 601: La expresión impresa proporcionada no es válida
     * - N - 602: Comprobante no encontrado
     *
     * @see responseWasOk()
     *
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * Status about the CFDI, values:
     * - `Vigente`: El comprobante está vigente al momento de la consulta
     * - `Cancelado`: El comprobante está cancelado al momento de la consulta
     * - `No Encontrado`: El comprobante no se encuentra en la base de datos del SAT
     *
     * @see isVigente()
     * @see isNotFound()
     * @see isCancelled()
     *
     * @return string
     */
    public function getCfdi(): string
    {
        return $this->cfdi;
    }

    /**
     * Cancellable status, values:
     * - `No cancelable`: No se puede cancelar, tal vez ya hay documentos relacionados.
     * - `Cancelable sin aceptación`: Se puede cancelar de inmediato.
     * - `Cancelable con aceptación`: Se puede cancelar pero se va a tener que esperar respuesta.
     *
     * @return string
     */
    public function getCancellable(): string
    {
        return $this->cancellable;
    }

    /**
     * Cancellation process status, values:
     *
     * - `(ninguno)`: El estado vacío es que no tiene estado de cancelación, porque no fue solicitada.
     * - `Cancelado sin aceptación`: Se canceló y no fue necesaria la aceptación.
     * - `En proceso`: En espera de que el receptor la autorice.
     * - `Plazo vencido`: Cancelado por vencimiento de plazo en que el receptor podía denegarla.
     * - `Cancelado con aceptación`: Cancelado con el consentimiento del receptor.
     * - `Solicitud rechazada`: No se realizó la cancelación por rechazo.
     *
     * @return string
     */
    public function getCancellationStatus(): string
    {
        return $this->cancellationStatus;
    }

    public function responseWasOk(): bool
    {
        return ('S - ' === substr($this->code, 0, 4));
    }

    public function isVigente(): bool
    {
        return ('Vigente' === $this->cfdi);
    }

    public function isNotFound(): bool
    {
        return ('No Encontrado' === $this->cfdi);
    }

    public function isCancelled(): bool
    {
        return ('Cancelado' === $this->cfdi);
    }
}
