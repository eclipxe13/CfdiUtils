<?php

namespace CfdiUtilsTests;

use CfdiUtils\CfdiVersion;
use CfdiUtils\Nodes\Node;
use CfdiUtils\Nodes\XmlNodeUtils;
use CfdiUtils\VersionDiscovery\VersionDiscoverer;

final class CfdiVersionTest extends TestCase
{
    public function testCreateDiscoverer(): void
    {
        $extended = new class() extends CfdiVersion {
            public static function exposeCreateDiscoverer(): VersionDiscoverer
            {
                return static::createDiscoverer();
            }
        };

        $this->assertInstanceOf(CfdiVersion::class, $extended::exposeCreateDiscoverer());
    }

    public function providerCfdiVersion(): array
    {
        return [
            '4.0' => ['4.0', 'Version', '4.0'],
            '3.3' => ['3.3', 'Version', '3.3'],
            '3.2' => ['3.2', 'version', '3.2'],
            '4.0 bad case' => ['', 'version', '4.0'],
            '3.3 bad case' => ['', 'version', '3.3'],
            '3.2 bad case' => ['', 'Version', '3.2'],
            '4.0 non set' => ['', 'Version', null],
            '3.3 non set' => ['', 'Version', null],
            '3.2 non set' => ['', 'version', null],
            '4.0 empty' => ['', 'Version', ''],
            '3.3 empty' => ['', 'Version', ''],
            '3.2 empty' => ['', 'version', ''],
            '4.0 wrong number' => ['', 'Version', '2.1'],
            '3.3 wrong number' => ['', 'Version', '2.1'],
            '3.2 wrong number' => ['', 'version', '2.0'],
        ];
    }

    /**
     * @param string $expected
     * @param string $attribute
     * @param string|null $value
     * @dataProvider providerCfdiVersion
     */
    public function testCfdiVersion(string $expected, string $attribute, ?string $value): void
    {
        $node = new Node('cfdi', [$attribute => $value]);
        $cfdiVersion = new CfdiVersion();
        $this->assertSame($expected, $cfdiVersion->getFromNode($node));
        $this->assertSame($expected, $cfdiVersion->getFromXmlString(XmlNodeUtils::nodeToXmlString($node)));
    }
}
