<?php

namespace CfdiUtilsTests\Elements\Cfdi33\Traits;

use CfdiUtils\Elements\Cfdi33\InformacionAduanera;
use PHPUnit\Framework\TestCase;

final class InformacionAduaneraTraitTest extends TestCase
{
    public function testAddInformacionAduanera()
    {
        // no childs
        $node = new UseInformacionAduanera('X');

        $this->assertCount(0, $node);

        // add first child
        $first = $node->addInformacionAduanera(['name' => 'first']);
        $this->assertInstanceOf(InformacionAduanera::class, $first);
        $this->assertSame('first', $first['name']);
        $this->assertCount(1, $node);

        // add second child
        $node->addInformacionAduanera();
        $this->assertCount(2, $node);
    }

    public function testMultiInformacionAduanera()
    {
        $node = new UseInformacionAduanera('X');
        $this->assertCount(0, $node);
        $multiReturn = $node->multiInformacionAduanera(
            ['id' => 'first'],
            ['id' => 'second']
        );
        $this->assertSame($multiReturn, $node);
        $this->assertCount(2, $node);
        $this->assertSame('first', $node->searchAttribute('cfdi:InformacionAduanera', 'id'));
    }
}
