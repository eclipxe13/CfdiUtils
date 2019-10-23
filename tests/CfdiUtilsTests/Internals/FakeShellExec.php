<?php

namespace CfdiUtilsTests\Internals;

use CfdiUtils\Internals\ShellExec;
use CfdiUtils\Internals\ShellExecResult;

/**
 * Use this class to emulate a ShellExec with predefined result
 */
class FakeShellExec extends ShellExec
{
    /** @var ShellExecResult|null */
    private $result;

    public function __construct(array $command, array $environment = [], ShellExecResult $result = null)
    {
        parent::__construct($command, $environment);
        $this->result = $result;
    }

    public function getResult(): ShellExecResult
    {
        if (null === $this->result) {
            throw new \LogicException('Predefined result not set on FakeShellExec class');
        }
        return $this->result;
    }

    public function setResult(ShellExecResult $result)
    {
        $this->result = $result;
    }

    public function run(): ShellExecResult
    {
        return $this->getResult();
    }
}
