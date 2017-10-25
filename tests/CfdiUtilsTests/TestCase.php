<?php
namespace CfdiUtilsTests;

use CfdiUtils\XmlResolver\XmlResolver;

class TestCase extends \PHPUnit\Framework\TestCase
{
    public static function utilAsset(string $file)
    {
        return dirname(__DIR__) . '/assets/' . $file;
    }

    protected function downloadResourceIfNotExists(string $remote): string
    {
        $xmlResolver = new XmlResolver();
        return $xmlResolver->resolve($remote);
    }
}
