<?php

namespace CfdiUtils\ConsultaCfdiSat;

class StatusResponse
{
    public function __construct(
        private string $code,
        private string $cfdi,
        private string $cancellable,
        private string $cancellationStatus,
        private string $validationEfos,
    ) {
    }

    /**
     * Status request code, values:
     * - S - Comprobante obtenido satisfactoriamente
     * - N - 601: La expresión impresa proporcionada no es válida
     * - N - 602: Comprobante no encontrado
     *
     * @see responseWasOk()
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
     */
    public function getCfdi(): string
    {
        return $this->cfdi;
    }

    /**
     * Cancellable status, values:
     * - `No cancelable`: No se puede cancelar, tal vez ya hay documentos relacionados.
     * - `Cancelable sin aceptación`: Se puede cancelar de inmediato.
     * - `Cancelable con aceptación`: Se puede cancelar, pero se va a tener que esperar respuesta.
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
     */
    public function getCancellationStatus(): string
    {
        return $this->cancellationStatus;
    }

    /**
     * Validation EFOS values:
     *
     * - "100": El emisor se encontró en el listado EFOS
     * - "200": No se encontró en listado EFOS
     */
    public function getValidationEfos(): string
    {
        return $this->validationEfos;
    }

    public function responseWasOk(): bool
    {
        return str_starts_with($this->code, 'S - ');
    }

    public function isVigente(): bool
    {
        return 'Vigente' === $this->cfdi;
    }

    public function isNotFound(): bool
    {
        return 'No Encontrado' === $this->cfdi;
    }

    public function isCancelled(): bool
    {
        return 'Cancelado' === $this->cfdi;
    }

    public function isEfosListed(): bool
    {
        return '100' === $this->validationEfos;
    }
}
