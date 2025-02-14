<?php

namespace CfdiUtilsTests\Validate;

use CfdiUtils\Validate\Contracts\ValidatorInterface;
use CfdiUtils\Validate\Discoverer;
use PHPUnit\Framework\TestCase;

final class DiscovererTest extends TestCase
{
    public function testDiscoverInFolder()
    {
        $discoverer = new Discoverer();
        $namespace = __NAMESPACE__ . '\FakeObjects';
        $folder = __DIR__ . '/FakeObjects';
        $discoverInFolder = $discoverer->discoverInFolder($namespace, $folder);

        $this->assertGreaterThanOrEqual(1, $discoverInFolder);
        foreach ($discoverInFolder as $discovered) {
            $this->assertInstanceOf(ValidatorInterface::class, $discovered);
        }
    }

    public function testDiscoverInFile()
    {
        $discoverer = new Discoverer();
        $namespace = __NAMESPACE__ . '\FakeObjects';
        $file = __DIR__ . '/FakeObjects/ImplementationDiscoverableCreateInterface.php';
        $discovered = $discoverer->discoverInFile($namespace, $file);
        $this->assertNotNull($discovered);
        $this->assertInstanceOf(ValidatorInterface::class, $discovered);
    }
}
