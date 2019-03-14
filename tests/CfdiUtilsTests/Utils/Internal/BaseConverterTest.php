<?php
namespace CfdiUtilsTests\Utils\Internal;

use CfdiUtils\Utils\Internal\BaseConverter;
use CfdiUtils\Utils\Internal\BaseConverterSequence;
use PHPUnit\Framework\TestCase;

class BaseConverterTest extends TestCase
{
    public function testBasicFunctionality()
    {
        $hexSequence = new BaseConverterSequence('0123456789ABCDEF');
        $converter = new BaseConverter($hexSequence);
        $this->assertSame($hexSequence, $converter->sequence());
        $this->assertSame(16, $converter->maximumBase());
        $input = 'FFFF';
        $expected = base_convert($input, 16, 2);
        $this->assertSame($expected, $converter->convert($input, 16, 2));
    }

    public function testConvertEmptyString()
    {
        $converter = BaseConverter::createBase36();
        $this->assertSame('0', $converter->convert('', 10, 2));
    }

    /**
     * @param int $base
     * @testWith [-1]
     *           [0]
     *           [1]
     *           [37]
     */
    public function testInvalidFromBase(int $base)
    {
        $converter = BaseConverter::createBase36();

        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('Invalid from base');
        $converter->convert('', $base, 16);
    }

    /**
     * @param int $base
     * @testWith [-1]
     *           [0]
     *           [1]
     *           [37]
     */
    public function testInvalidToBase(int $base)
    {
        $converter = BaseConverter::createBase36();

        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('Invalid to base');
        $converter->convert('', 16, $base);
    }

    public function testConvertWithInputNotIsSequence()
    {
        $converter = BaseConverter::createBase36();
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('The number to convert contains invalid characters');
        $converter->convert('@', 16, 10);
    }

    public function testConvertUsingLongInput()
    {
        // this is the main reason to exists of BaseConverter class
        // since base_convert cannot handle large inputs
        $input = '3330303031303030303030333030303233373038';
        $expected = '292233162870206001759766198425879490508935868472';
        $converter = BaseConverter::createBase36();
        $this->assertSame($expected, $converter->convert($input, 16, 10));
    }

    public function testConvertZeroUsingSameBase()
    {
        $input = '0000000';
        $expected = '0';

        $converter = BaseConverter::createBase36();
        $this->assertSame($expected, $converter->convert($input, 2, 2));
    }

    public function testConvertZeroUsingDifferentBase()
    {
        $input = '0000000';
        $expected = '0';

        $converter = BaseConverter::createBase36();
        $this->assertSame($expected, $converter->convert($input, 2, 4));
    }

    public function testConvertZeroUsingLettersSequence()
    {
        // base_convert(501020304050607, 8, 16) => 141083105187
        //        501020304050607
        $input = 'FABACADAEAFAGAH';
        //           141083105187
        $expected = 'BEBAIDBAFBIH';

        $converter = new BaseConverter(new BaseConverterSequence('ABCDEFGHIJKLMNOPQRSTUVWXYZ'));
        $this->assertSame($expected, $converter->convert($input, 8, 16));
    }
}
