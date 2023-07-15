<?php

namespace CfdiUtilsTests\Retenciones;

use CfdiUtils\Nodes\Node;
use CfdiUtils\Nodes\XmlNodeUtils;
use CfdiUtils\Retenciones\RetencionVersion;
use CfdiUtils\VersionDiscovery\VersionDiscoverer;
use CfdiUtilsTests\TestCase;

final class RetencionVersionTest extends TestCase
{
    public function testCreateDiscoverer(): void
    {
        $extended = new class () extends RetencionVersion {
            public static function exposeCreateDiscoverer(): VersionDiscoverer
            {
                return static::createDiscoverer();
            }
        };

        $this->assertInstanceOf(RetencionVersion::class, $extended::exposeCreateDiscoverer());
    }

    public function providerRetencionVersion(): array
    {
        return [
            '2.0' => ['2.0', 'Version', '2.0'],
            '1.0' => ['1.0', 'Version', '1.0'],
            '2.0 bad case' => ['', 'version', '2.0'],
            '1.0 bad case' => ['', 'version', '1.0'],
            '2.0 non set' => ['', 'Version', null],
            '1.0 non set' => ['', 'Version', null],
            '2.0 empty' => ['', 'Version', ''],
            '1.0 empty' => ['', 'Version', ''],
            '2.0 wrong number' => ['', 'Version', '2.1'],
            '1.0 wrong number' => ['', 'Version', '2.1'],
        ];
    }

    /**
     * @param string $expected
     * @param string $attribute
     * @param string|null $value
     * @dataProvider providerRetencionVersion
     */
    public function testRetencionVersion(string $expected, string $attribute, ?string $value): void
    {
        $node = new Node('retenciones', [$attribute => $value]);
        $cfdiVersion = new RetencionVersion();
        $this->assertSame($expected, $cfdiVersion->getFromNode($node));
        $this->assertSame($expected, $cfdiVersion->getFromXmlString(XmlNodeUtils::nodeToXmlString($node)));
    }
}
