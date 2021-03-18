<?php

namespace CfdiUtilsTests\Validate;

use CfdiUtils\Validate\Assert;
use CfdiUtils\Validate\Status;
use PHPUnit\Framework\TestCase;

final class AssertTest extends TestCase
{
    public function testConstructor()
    {
        $assert = new Assert('X');
        $this->assertSame('X', $assert->getCode());
        $this->assertSame('', $assert->getTitle());
        $this->assertEquals(Status::none(), $assert->getStatus());
        $this->assertSame('', $assert->getExplanation());
    }

    public function testConstructorWithValues()
    {
        $assert = new Assert('CODE', 'Title', Status::ok(), 'Explanation');
        $this->assertSame('CODE', $assert->getCode());
        $this->assertSame('Title', $assert->getTitle());
        $this->assertEquals(Status::ok(), $assert->getStatus());
        $this->assertSame('Explanation', $assert->getExplanation());
    }

    public function testConstructWithEmptyStatusThrowException()
    {
        $this->expectException(\UnexpectedValueException::class);
        new Assert('');
    }

    public function testSetTitle()
    {
        $assert = new Assert('X');
        $assert->setTitle('Title');
        $this->assertSame('Title', $assert->getTitle());
    }

    public function testSetStatusWithoutExplanation()
    {
        $assert = new Assert('X');
        $assert->setExplanation('Explanation');
        $expectedStatus = Status::ok();

        $assert->setStatus(Status::ok());
        $this->assertEquals($expectedStatus, $assert->getStatus());
        $this->assertSame('Explanation', $assert->getExplanation());
    }

    public function testSetStatusWithExplanation()
    {
        $assert = new Assert('X');
        $assert->setExplanation('Explanation');
        $expectedStatus = Status::ok();

        $assert->setStatus(Status::ok(), 'Changed explanation');
        $this->assertEquals($expectedStatus, $assert->getStatus());
        $this->assertSame('Changed explanation', $assert->getExplanation());
    }

    public function testSetExplanation()
    {
        $assert = new Assert('X');
        $assert->setTitle('Explanation');
        $this->assertSame('Explanation', $assert->getTitle());
    }

    public function testToString()
    {
        $assert = new Assert('CODE', 'Title', Status::ok());
        $value = (string) $assert;
        $this->assertStringContainsString('CODE', $value);
        $this->assertStringContainsString('Title', $value);
        $this->assertStringContainsString(Status::STATUS_OK, $value);
    }
}
