<?php

namespace CfdiUtils\OpenSSL;

use Throwable;

class OpenSSLCallerException extends OpenSSLException
{
    /** @var CallResponse */
    private $execResult;

    public function __construct(
        CallResponse $execResult,
        string $message = 'OpenSSL execution error',
        int $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->execResult = $execResult;
    }

    public function getCallResponse(): CallResponse
    {
        return $this->execResult;
    }
}
