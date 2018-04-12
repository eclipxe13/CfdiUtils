<?php
namespace CfdiUtilsTests\Certificado;

use CfdiUtils\Certificado\SerialNumber;
use PHPUnit\Framework\TestCase;

class SerialNumberTest extends TestCase
{
    /**
     * This test extends the base class to expose the visibility of serialHexToAscii method.
     *
     * @param string $input
     * @param string $expected
     * @testWith ["3330303031303030303030333030303233373038", "30001000000300023708"]
     */
    public function testLoadHexadecimal(string $input, string $expected)
    {
        $serial = new SerialNumber('');
        $serial->loadHexadecimal($input);
        $this->assertSame($input, $serial->getHexadecimal());
        $this->assertSame($expected, $serial->asAscii());
    }

    /**
     * This test extends the base class to expose the visibility of serialHexToAscii method.
     *
     * @param string $input
     * @param string $expected
     * @testWith ["0x3330303031303030303030333030303233373038", "30001000000300023708"]
     *           ["292233162870206001759766198425879490508935868472", "30001000000300023708"]
     */
    public function testSerialHexToAsciiDecimal(string $input, string $expected)
    {
        $serial = new SerialNumber('');
        $serial->loadDecimal($input);
        $this->assertSame($expected, $serial->asAscii());
    }
}
