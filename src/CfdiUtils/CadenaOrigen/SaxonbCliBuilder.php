<?php
namespace CfdiUtils\CadenaOrigen;

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

    public function createCommand(string $xmlFile, string $xsltLocation): string
    {
        // if is running on windows then use NUL instead of /dev/null
        $devnull = (0 === stripos(PHP_OS, 'win')) ? 'NUL' : '/dev/null';
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

        $xmlFile = tempnam('', '');
        try {
            file_put_contents($xmlFile, $xmlContent);
            $command = $this->createCommand($xmlFile, $xsltLocation);
            $output = [];
            $return = 0;
            $transform = exec($command, $output, $return);
            // ugly hack for empty xslt
            if ('<?xml version="1.0" encoding="UTF-8"?>' === $transform && 0 === $return && count($output) == 1) {
                $transform = '';
                $return = 2;
            }
            if (0 !== $return) {
                throw new XsltBuildException('Transformation error');
            }
            return $transform;
        } finally {
            unlink($xmlFile);
        }
    }
}
