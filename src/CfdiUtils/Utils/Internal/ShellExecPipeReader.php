<?php
namespace CfdiUtils\Utils\Internal;

/**
 * Internal class used by ShellExec to process a stdout or stderr pipe
 *
 * NOTE: Changes on this file will not be considering a BC since this utility class is for internal usage only
 *
 * @internal
 */
class ShellExecPipeReader
{
    private $pipe;

    private $buffer = '';

    private $continueReading = true;

    public function __construct($pipe)
    {
        if (! is_resource($pipe)) {
            throw new \RuntimeException('Invalid pipe');
        }

        $this->pipe = $pipe;
        stream_set_blocking($pipe, false); // set as non-blocking
    }

    public function continueReading(): bool
    {
        return $this->continueReading;
    }

    public function stopReading()
    {
        $this->continueReading = false;
    }

    public function read(): bool
    {
        if ($this->eof()) {
            $this->stopReading();
            $this->close();
            return false;
        }

        $input = $this->fgets(1024);
        if ('' === $input) {
            return false;
        }

        $this->addToBuffer($input);
        return true;
    }

    public function eof(): bool
    {
        return feof($this->pipe);
    }

    public function close()
    {
        pclose($this->pipe);
    }

    public function fgets(int $length): string
    {
        return fgets($this->pipe, $length) ?: '';
    }

    public function buffer(): string
    {
        return $this->buffer;
    }

    public function addToBuffer(string $input)
    {
        $this->buffer .= $input;
    }
}
