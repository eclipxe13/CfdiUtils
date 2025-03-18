<?php

namespace CfdiUtilsTests\Nodes;

use CfdiUtils\Nodes\NodeNsDefinitionsMover;
use CfdiUtils\Nodes\XmlNodeUtils;
use CfdiUtilsTests\TestCase;

final class NodeNsDefinitionsMoverTest extends TestCase
{
    public function testMoveDefinitionsWithFilter(): void
    {
        $inputFile = $this->utilAsset('xml-with-namespace-definitions-at-child-level.xml');
        $input = XmlNodeUtils::nodeFromXmlString(file_get_contents($inputFile) ?: '');

        $processor = new NodeNsDefinitionsMover();
        // only process tempuri namespaces
        $processor->setNamespaceFilter(
            fn (string $namespace): bool => 'http://www.tempuri.org/' === strval(substr($namespace, 0, 23))
        );
        $processor->process($input);

        $expectedFile = $this->utilAsset('xml-with-namespace-definitions-at-root-level-filtered.xml');
        $this->assertXmlStringEqualsXmlFile($expectedFile, XmlNodeUtils::nodeToXmlString($input));
    }

    public function testMoveDefinitionsWithoutFilter(): void
    {
        $inputFile = $this->utilAsset('xml-with-namespace-definitions-at-child-level.xml');
        $input = XmlNodeUtils::nodeFromXmlString(file_get_contents($inputFile) ?: '');

        $processor = new NodeNsDefinitionsMover();
        $processor->process($input);

        $expectedFile = $this->utilAsset('xml-with-namespace-definitions-at-root-level-all.xml');
        $this->assertXmlStringEqualsXmlFile($expectedFile, XmlNodeUtils::nodeToXmlString($input));
    }
}
