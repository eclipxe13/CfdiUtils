<?php

namespace CfdiUtils\OpenSSL;

use Throwable;

class OpenSSLCallerException extends OpenSSLException
{
    public function __construct(
        private CallResponse $execResult,
        string $message = 'OpenSSL execution error',
        int $code = 0,
        ?Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getCallResponse(): CallResponse
    {
        return $this->execResult;
    }
}
