<?php
namespace CfdiUtilsTests\Internals;

use CfdiUtils\Internals\BaseConverterSequence;
use PHPUnit\Framework\TestCase;

class BaseConverterSequenceTest extends TestCase
{
    public function testValidSequence()
    {
        $source = 'ABCD';
        $sequence = new BaseConverterSequence($source);
        $this->assertSame($source, $sequence->value());
        $this->assertSame(4, $sequence->length());
        $this->assertSame($source, strval($sequence));
    }

    public function testInvalidSequenceWithEmptyString()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('Sequence does not contains enough elements');
        new BaseConverterSequence('');
    }

    public function testInvalidSequenceWithOneChar()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('Sequence does not contains enough elements');
        new BaseConverterSequence('X');
    }

    public function testInvalidSequenceWithMultibyte()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('multibyte');
        new BaseConverterSequence('Ñ');
    }

    public function testInvalidSequenceWithRepeatedChars()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('The sequence has not unique values');
        new BaseConverterSequence('ABCBA');
    }

    public function testInvalidSequenceWithRepeatedCharsDifferentCase()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('The sequence has not unique values');
        new BaseConverterSequence('ABCDabcd');
    }

    public function testIsValidMethod()
    {
        $this->assertTrue(BaseConverterSequence::isValid('abc'));
        $this->assertFalse(BaseConverterSequence::isValid('abcb'));
        $this->assertFalse(BaseConverterSequence::isValid(''));
        $this->assertFalse(BaseConverterSequence::isValid('0'));
    }
}
