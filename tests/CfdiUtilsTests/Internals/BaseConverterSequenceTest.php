<?php

namespace CfdiUtilsTests\Internals;

use CfdiUtils\Internals\BaseConverterSequence;
use PHPUnit\Framework\TestCase;

final class BaseConverterSequenceTest extends TestCase
{
    public function testValidSequence(): void
    {
        $source = 'ABCD';
        $sequence = new BaseConverterSequence($source);
        $this->assertSame($source, $sequence->value());
        $this->assertSame(4, $sequence->length());
        $this->assertSame($source, strval($sequence));
    }

    public function testInvalidSequenceWithEmptyString(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('Sequence does not contains enough elements');
        new BaseConverterSequence('');
    }

    public function testInvalidSequenceWithOneChar(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('Sequence does not contains enough elements');
        new BaseConverterSequence('X');
    }

    public function testInvalidSequenceWithMultibyte(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('multibyte');
        new BaseConverterSequence('Ñ');
    }

    public function testInvalidSequenceWithRepeatedChars(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('The sequence has not unique values');
        new BaseConverterSequence('ABCBA');
    }

    public function testInvalidSequenceWithRepeatedCharsDifferentCase(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('The sequence has not unique values');
        new BaseConverterSequence('ABCDabcd');
    }

    public function testIsValidMethod(): void
    {
        $this->assertTrue(BaseConverterSequence::isValid('abc'));
        $this->assertFalse(BaseConverterSequence::isValid('abcb'));
        $this->assertFalse(BaseConverterSequence::isValid(''));
        $this->assertFalse(BaseConverterSequence::isValid('0'));
    }
}
