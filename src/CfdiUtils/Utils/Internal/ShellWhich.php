<?php
namespace CfdiUtils\Utils\Internal;

class ShellWhich
{
    private $executable;

    public function __construct(string $executable = '')
    {
        $this->executable = $executable ?: $this->defaultExecutableByOS();
    }

    public function operatingSystemIsWindows(): bool
    {
        return (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN');
    }

    public function defaultExecutableByOS(): string
    {
        return ($this->operatingSystemIsWindows()) ? 'where' : 'which';
    }

    public function search(string $search): string
    {
        $execution = ShellExec::run(escapeshellarg($this->executable) . ' ' . escapeshellarg($search));
        if (0 !== $execution->exitStatus()) {
            return '';
        }
        return trim($execution->output());
    }
}
