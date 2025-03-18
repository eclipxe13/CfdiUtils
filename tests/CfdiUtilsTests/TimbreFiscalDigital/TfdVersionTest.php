<?php

namespace CfdiUtilsTests\TimbreFiscalDigital;

use CfdiUtils\Nodes\Node;
use CfdiUtils\Nodes\XmlNodeUtils;
use CfdiUtils\TimbreFiscalDigital\TfdVersion;
use CfdiUtilsTests\TestCase;

final class TfdVersionTest extends TestCase
{
    public function providerTfdVersion(): array
    {
        return [
            '1.1' => ['1.1', 'Version', '1.1'],
            '1.0' => ['1.0', 'version', '1.0'],
            '1.1 bad case' => ['', 'version', '1.1'],
            '1.0 bad case' => ['', 'Version', '1.0'],
            '1.1 non set' => ['', 'Version', null],
            '1.0 non set' => ['', 'version', null],
            '1.1 empty' => ['', 'Version', ''],
            '1.0 empty' => ['', 'version', ''],
            '1.1 wrong number' => ['', 'Version', '2.1'],
            '1.0 wrong number' => ['', 'version', '2.0'],
        ];
    }

    /**
     * @dataProvider providerTfdVersion
     */
    public function testTfdVersion(string $expected, string $attribute, ?string $value): void
    {
        $node = new Node('tfd', [$attribute => $value]);
        $tfdVersion = new TfdVersion();
        $this->assertSame($expected, $tfdVersion->getFromNode($node));
        $this->assertSame($expected, $tfdVersion->getFromXmlString(XmlNodeUtils::nodeToXmlString($node)));
    }
}
