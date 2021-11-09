<?php

namespace CfdiUtilsTests\Elements\CartaPorte10;

use CfdiUtils\Elements\CartaPorte10\Remolque;
use CfdiUtils\Elements\CartaPorte10\Remolques;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CfdiUtils\Elements\CartaPorte10\Remolques
 */
final class RemolquesTest extends TestCase
{
    /** @var Remolques */
    public $element;

    protected function setUp(): void
    {
        parent::setUp();
        $this->element = new Remolques();
    }

    public function testConstructedObject()
    {
        $this->assertSame('cartaporte:Remolques', $this->element->getElementName());
    }

    public function testAddRemolque()
    {
        // insert first element
        $first = $this->element->addRemolque(['id' => 'first']);
        $this->assertInstanceOf(Remolque::class, $first);
        $this->assertSame('first', $first['id']);
        $this->assertCount(1, $this->element);

        // insert second element data should return a different element
        $second = $this->element->addRemolque(['id' => 'second']);
        $this->assertNotEquals($first, $second);
        $this->assertCount(2, $this->element);
    }

    public function testMultiRemolque()
    {
        // insert first element
        $remolque = $this->element->multiRemolque(
            ['id' => 'first'],
            ['id' => 'second']
        );
        $this->assertCount(2, $remolque);
        $this->assertSame($this->element, $remolque);
    }
}
