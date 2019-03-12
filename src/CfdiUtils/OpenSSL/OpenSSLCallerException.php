<?php
namespace CfdiUtils\OpenSSL;

use CfdiUtils\Utils\Internal\ShellExecResult;
use Throwable;

class OpenSSLCallerException extends OpenSSLException
{
    /** @var ShellExecResult */
    private $execResult;

    public function __construct(
        ShellExecResult $execResult,
        string $message = 'OpenSSL execution error',
        int $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->execResult = $execResult;
    }

    public function getExecResult(): ShellExecResult
    {
        return $this->execResult;
    }
}
