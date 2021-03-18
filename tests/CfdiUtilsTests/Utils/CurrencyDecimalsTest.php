<?php

namespace CfdiUtilsTests\Utils;

use CfdiUtils\Utils\CurrencyDecimals;
use CfdiUtilsTests\TestCase;

final class CurrencyDecimalsTest extends TestCase
{
    public function testCreateGeneric()
    {
        $curdec = new CurrencyDecimals('FOO', 2);
        $this->assertSame('FOO', $curdec->currency());
        $this->assertSame(2, $curdec->decimals());
    }

    /**
     * @param string $currency
     * @testWith [""]
     *           ["ÑÑÑ"]
     *           ["XXXX"]
     */
    public function testCreateWithEmptyCode(string $currency)
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('currency');
        new CurrencyDecimals($currency, 2);
    }

    public function testCreateWithNegativeDecimals()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('decimals');
        new CurrencyDecimals('FOO', -1);
    }

    public function testDoesNotExceed()
    {
        $foo = new CurrencyDecimals('FOO', 3);
        $this->assertTrue($foo->doesNotExceedDecimals('1'));
        $this->assertTrue($foo->doesNotExceedDecimals('1.000'));
        $this->assertTrue($foo->doesNotExceedDecimals('1.999'));
        $this->assertFalse($foo->doesNotExceedDecimals('1.0001'));
        $this->assertFalse($foo->doesNotExceedDecimals('1.0000'));
    }

    public function testDecimalsCount()
    {
        $this->assertSame(0, CurrencyDecimals::decimalsCount('1'));
        $this->assertSame(0, CurrencyDecimals::decimalsCount('1.'));
        $this->assertSame(1, CurrencyDecimals::decimalsCount('1.0'));
        $this->assertSame(2, CurrencyDecimals::decimalsCount('1.00'));
    }

    public function testKnownCurrencyMexicanPeso()
    {
        $mxn = CurrencyDecimals::newFromKnownCurrencies('MXN');
        $this->assertSame('MXN', $mxn->currency());
        $this->assertSame(2, $mxn->decimals());
    }

    public function testUnknownCurrency()
    {
        $this->expectException(\OutOfBoundsException::class);
        $this->expectExceptionMessage('not known');
        CurrencyDecimals::newFromKnownCurrencies('FOO');
    }

    public function testUnknownCurrencyWithDecimals()
    {
        $foo = CurrencyDecimals::newFromKnownCurrencies('FOO', 6);
        $this->assertSame('FOO', $foo->currency());
        $this->assertSame(6, $foo->decimals());
    }

    public function testKnownDecimalsMexicanPeso()
    {
        $this->assertSame(2, CurrencyDecimals::knownCurrencyDecimals('MXN'));
    }
}
