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

    public function providerFullJoin(array $first, array ...$next): array
    {
        if (! count($next)) {
            return $first;
        }
        $combine = [];
        $second = array_shift($next);
        foreach ($first as $a) {
            foreach ($second as $b) {
                $combine[] = array_merge($a, $b);
            }
        }
        if (count($next)) {
            return $this->providerFullJoin($combine, ...$next);
        }
        return $combine;
    }
}
