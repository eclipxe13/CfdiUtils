<?php

namespace CfdiUtils\CadenaOrigen;

use CfdiUtils\Internals\TemporaryFile;
use Symfony\Component\Process\Process;

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

    /**
     * SECURITY: This method does not work as expected on non POSIX system (as MS Windows)
     * It was never intented to be public. It is not used by this class and will be removed on 3.0.0
     *
     * @param string $xmlFile
     * @param string $xsltLocation
     * @return string
     * @deprecated 2.9.0 Will be removed with no replacement, never intended to be public
     * @codeCoverageIgnore
     */
    public function createCommand(string $xmlFile, string $xsltLocation): string
    {
        // if is running on windows then use NUL instead of /dev/null
        $devnull = ('\\' === DIRECTORY_SEPARATOR) ? 'NUL' : '/dev/null';
        return implode(' ', [
            escapeshellarg($this->getExecutablePath()),
            escapeshellarg('-s:' . $xmlFile),
            escapeshellarg('-xsl:' . $xsltLocation),
            escapeshellarg('-warnings:silent'), // default recover
            "2>$devnull",
        ]);
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
        return $temporaryFile->runAndRemove(
            function () use ($temporaryFile, $xmlContent, $xsltLocation) {
                $temporaryFile->storeContents($xmlContent);

                $command = [
                    $this->getExecutablePath(),
                    '-s:' . $temporaryFile->getPath(),
                    '-xsl:' . $xsltLocation,
                    '-warnings:silent', // default recover
                ];

                $process = new Process($command);

                $exitCode = $process->run();
                if (0 !== $exitCode) {
                    throw new XsltBuildException(
                        sprintf('Transformation error: %s', $process->getErrorOutput() ?: '(no output error)')
                    );
                }

                $output = trim($process->getOutput());
                if ('<?xml version="1.0" encoding="UTF-8"?>' === $output) {
                    throw new XsltBuildException('Transformation error: XML without root element');
                }

                return $output;
            }
        );
    }
}
