<?php

namespace CfdiUtilsTests\Validate;

use CfdiUtils\Validate\Status;
use PHPUnit\Framework\TestCase;

final class StatusTest extends TestCase
{
    public function testConstructWithInvalidCode()
    {
        $this->expectException(\UnexpectedValueException::class);
        new Status('foo');
    }

    public function testOk()
    {
        $statusOne = new Status(Status::STATUS_OK);
        $statusTwo = Status::ok();
        $this->assertEquals($statusOne, $statusTwo);
        $this->assertTrue($statusOne->isOk());
        $this->assertTrue($statusOne->equalsTo($statusTwo));
        $this->assertFalse($statusOne->equalsTo(Status::none()));
    }

    public function testError()
    {
        $statusOne = new Status(Status::STATUS_ERROR);
        $statusTwo = Status::error();
        $this->assertEquals($statusOne, $statusTwo);
        $this->assertTrue($statusOne->isError());
        $this->assertTrue($statusOne->equalsTo($statusTwo));
        $this->assertFalse($statusOne->equalsTo(Status::none()));
    }

    public function testWarning()
    {
        $statusOne = new Status(Status::STATUS_WARNING);
        $statusTwo = Status::warn();
        $this->assertEquals($statusOne, $statusTwo);
        $this->assertTrue($statusOne->isWarning());
        $this->assertTrue($statusOne->equalsTo($statusTwo));
        $this->assertFalse($statusOne->equalsTo(Status::none()));
    }

    public function testNone()
    {
        $statusOne = new Status(Status::STATUS_NONE);
        $statusTwo = Status::none();
        $this->assertEquals($statusOne, $statusTwo);
        $this->assertTrue($statusOne->isNone());
        $this->assertTrue($statusOne->equalsTo($statusTwo));
        $this->assertFalse($statusOne->equalsTo(Status::ok()));
    }

    public function testToString()
    {
        $status = Status::none();
        $this->assertSame(Status::STATUS_NONE, (string) $status);
    }

    public function testConditionalCreation()
    {
        $this->assertEquals(Status::ok(), Status::when(true));
        $this->assertNotEquals(Status::ok(), Status::when(false));
        $this->assertEquals(Status::error(), Status::when(false));
        $this->assertEquals(Status::warn(), Status::when(false, Status::warn()));
    }

    public function testComparableValue()
    {
        $this->assertGreaterThan(0, Status::ok()->compareTo(Status::none()));
        $this->assertGreaterThan(0, Status::none()->compareTo(Status::warn()));
        $this->assertGreaterThan(0, Status::warn()->compareTo(Status::error()));
    }
}
