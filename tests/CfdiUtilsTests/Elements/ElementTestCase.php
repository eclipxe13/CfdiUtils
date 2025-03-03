<?php

namespace CfdiUtilsTests\Elements;

use CfdiUtils\Elements\Common\AbstractElement;
use CfdiUtils\Nodes\Node;
use CfdiUtilsTests\TestCase;

abstract class ElementTestCase extends TestCase
{
    public function assertElementHasName(AbstractElement $element, string $name): void
    {
        $this->assertSame(
            $name,
            $element->getElementName(),
            sprintf('The element %s must have the name "%s"', $element::class, $name)
        );
    }

    public function assertElementHasFixedAttributes(AbstractElement $element, array $attributes): void
    {
        $elementClass = $element::class;
        foreach ($attributes as $name => $value) {
            $this->assertSame(
                $element[$name],
                $value,
                sprintf('The element %s must have the attribute "%s" with value "%s"', $elementClass, $name, $value)
            );
        }
    }

    public function assertElementHasOrder(AbstractElement $element, array $order): void
    {
        $this->assertSame(
            $order,
            $element->children()->getOrder(),
            sprintf('The element %s does not have the correct child order definition', $element::class)
        );
    }

    public function assertElementHasChildSingle(
        AbstractElement $element,
        string $childClassName,
        string $getter = '',
        string $adder = '',
    ): void {
        $elementClass = $element::class;
        $childClassBaseName = basename(str_replace('\\', '/', $childClassName));
        $element->children()->removeAll();

        // element should return the same instance
        $getter = $getter ?: 'get' . $childClassBaseName;
        $instance = $element->{$getter}();
        $this->assertInstanceOf(
            $childClassName,
            $instance,
            sprintf('The method %s::%s should return the an instance of %s', $elementClass, $getter, $childClassName)
        );
        $this->assertSame(
            $instance,
            $element->{$getter}(),
            sprintf('The method %s::%s should return always the same instance', $elementClass, $getter)
        );

        // add should work on the same object
        $adder = $adder ?: 'add' . $childClassBaseName;
        $second = $element->{$adder}(['foo' => 'bar']);
        $this->assertInstanceOf(
            $childClassName,
            $second,
            sprintf('The method %s::%s should return the an instance of %s', $elementClass, $adder, $childClassName)
        );
        $this->assertSame(
            'bar',
            $instance['foo'],
            sprintf('The method %s::%s should write the attributes on the same instance', $elementClass, $adder)
        );
    }

    public function assertElementHasChildSingleAddChild(
        AbstractElement $element,
        string $childClassName,
    ): void {
        $elementClass = $element::class;
        $childClassBaseName = basename(str_replace('\\', '/', $childClassName));
        $element->children()->removeAll();

        // element should return the same instance
        $getter = 'get' . $childClassBaseName;
        $instance = $element->{$getter}();
        $this->assertInstanceOf(
            $childClassName,
            $instance,
            sprintf('The method %s::%s should return the an instance of %s', $elementClass, $getter, $childClassName)
        );
        $this->assertSame(
            $instance,
            $element->{$getter}(),
            sprintf('The method %s::%s should return always the same instance', $elementClass, $getter)
        );

        // add should append a child into the existent node
        $adder = 'add' . $childClassBaseName;

        $firstChild = new Node('child1');
        $returnOnAdder = $element->{$adder}($firstChild);
        $this->assertSame(
            $element,
            $returnOnAdder,
            sprintf('The method %s::%s should return always the element instance', $elementClass, $getter)
        );
        $this->assertSame(
            [$firstChild],
            iterator_to_array($instance->children()),
            'The first child should be added to the element\'s children'
        );
        $secondChild = new Node('child2');
        $element->{$adder}($secondChild);
        $this->assertSame(
            [$firstChild, $secondChild],
            iterator_to_array($instance->children()),
            'The second child should be added to the element\'s children'
        );
    }

    public function assertElementHasChildMultiple(
        AbstractElement $element,
        string $childClassName,
        string $elementName = '',
    ): void {
        $elementClass = $element::class;
        $elementName = $elementName ?: basename(str_replace('\\', '/', $childClassName));
        $element->children()->removeAll();

        // first: add should return specific instance and added
        $adder = 'add' . $elementName;
        $first = $element->{$adder}(['id' => 'first']);
        $this->assertInstanceOf(
            $childClassName,
            $first,
            sprintf('The method %s::%s should return the an instance of %s', $elementClass, $adder, $childClassName)
        );
        $this->assertSame(
            'first',
            $first['id'],
            sprintf('The method %s::%s should write the attributes', $elementClass, $adder)
        );
        $this->assertCount(1, $element->children());

        // second: add should return other instance different from first but added
        $second = $element->{$adder}(['id' => 'second']);
        $this->assertNotSame(
            $first,
            $second,
            sprintf('The method %s::%s should return a new instance of %s', $elementClass, $adder, $childClassName)
        );
        $this->assertSame(
            'second',
            $second['id'],
            sprintf('The method %s::%s should write the attributes', $elementClass, $adder)
        );
        $this->assertCount(2, $element->children());

        // multiple: add should return other instance different from first but added
        $multier = 'multi' . $elementName;
        $sameAsElement = $element->{$multier}(['id' => 'third'], ['id' => 'fourth']);
        $this->assertSame(
            $element,
            $sameAsElement,
            sprintf(
                'The method %s::%s should return the same element as the instance contained',
                $elementClass,
                $multier
            )
        );
        $this->assertCount(4, $element->children());
    }
}
