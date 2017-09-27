<?php
namespace CfdiUtilsTests;

class TestCase extends \PHPUnit\Framework\TestCase
{
    public function utilAsset(string $file)
    {
        return dirname(__DIR__) . '/assets/' . $file;
    }
}
