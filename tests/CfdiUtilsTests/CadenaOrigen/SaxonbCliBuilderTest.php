<?php

namespace CfdiUtilsTests\CadenaOrigen;

use CfdiUtils\CadenaOrigen\SaxonbCliBuilder;
use CfdiUtils\CadenaOrigen\XsltBuilderInterface;
use CfdiUtils\CadenaOrigen\XsltBuildException;

final class SaxonbCliBuilderTest extends GenericBuilderTestCase
{
    protected function createBuilder(): XsltBuilderInterface
    {
        $executable = (string) getenv('saxonb-path');
        if ('' === $executable) {
            $executable = '/usr/bin/saxonb-xslt';
        }
        if (! is_executable($executable)) {
            $this->markTestSkipped("Cannot test since saxonb ($executable) is missing");
        }
        return new SaxonbCliBuilder($executable);
    }

    public function testConstructorWithEmptyExecutable()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('empty');
        new SaxonbCliBuilder('');
    }

    public function testWithNonExistentExecutable()
    {
        $builder = new SaxonbCliBuilder('/foo/bar');
        $this->expectException(XsltBuildException::class);
        $this->expectExceptionMessage('does not exists');

        $builder->build('x', 'y');
    }

    public function testWithDirectory()
    {
        $builder = new SaxonbCliBuilder(__DIR__);
        $this->expectException(XsltBuildException::class);
        $this->expectExceptionMessage('directory');

        $builder->build('x', 'y');
    }

    public function testWithFile()
    {
        $builder = new SaxonbCliBuilder(__FILE__);
        $this->expectException(XsltBuildException::class);
        // this file could have executable permissions (because users!)... then change message
        if (is_executable(__FILE__)) {
            $this->expectExceptionMessage('Transformation error');
        } else {
            $this->expectExceptionMessage('executable');
        }
        $builder->build('x', 'y');
    }
}
