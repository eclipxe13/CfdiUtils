<?php
namespace CfdiUtils\CadenaOrigen;

use CfdiUtils\Utils\Internal\ShellExec;
use CfdiUtils\Utils\Internal\TemporaryFile;

class SaxonbCliBuilder extends AbstractXsltBuilder
{
    /** @var string */
    private $executablePath;

    public function __construct(string $executablePath)
    {
        $this->setExecutablePath($executablePath);
    }

    public function getExecutablePath(): string
    {
        return $this->executablePath;
    }

    public function setExecutablePath(string $executablePath)
    {
        if ('' === $executablePath) {
            throw new \UnexpectedValueException('The executable path for SabonB cannot be empty');
        }
        $this->executablePath = $executablePath;
    }

    public function createCommand(string $xmlFile, string $xsltLocation): array
    {
        // if is running on windows then use NUL instead of /dev/null
        return [
            $this->getExecutablePath(),
            '-s:' . $xmlFile,
            '-xsl:' . $xsltLocation,
            '-warnings:silent', // default recover
        ];
    }

    public function build(string $xmlContent, string $xsltLocation): string
    {
        $this->assertBuildArguments($xmlContent, $xsltLocation);

        $executable = $this->getExecutablePath();
        if (! file_exists($executable)) {
            throw new XsltBuildException('The executable path for SabonB does not exists');
        }
        if (is_dir($executable)) {
            throw new XsltBuildException('The executable path for SabonB is a directory');
        }
        if (! is_executable($executable)) {
            throw new XsltBuildException('The executable path for SabonB is not executable');
        }

        $temporaryFile = TemporaryFile::create();
        try {
            file_put_contents($temporaryFile->getPath(), $xmlContent);
            $command = $this->createCommand($temporaryFile->getPath(), $xsltLocation);
            $execution = ShellExec::run($command);

            if (0 !== $execution->exitStatus()) {
                throw new XsltBuildException('Transformation error');
            }
            if ('<?xml version="1.0" encoding="UTF-8"?>' === trim($execution->output())) {
                throw new XsltBuildException('Transformation error');
            }
            return trim($execution->output());
        } finally {
            $temporaryFile->remove();
        }
    }
}
