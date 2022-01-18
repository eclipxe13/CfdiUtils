<?php

namespace CfdiUtilsTests;

use CfdiUtils\CfdiCreateObjectException;
use LogicException;

final class CfdiCreateObjectExceptionTest extends TestCase
{
    public function testWithVersionExceptions(): void
    {
        $exceptions = [
            '2.0' => $ex20 = new \UnexpectedValueException('Version 2.0 exception'),
            '1.5' => $ex15 = new \UnexpectedValueException('Version 1.5 exception'),
        ];
        $exception = CfdiCreateObjectException::withVersionExceptions($exceptions);

        $this->assertSame('Unable to read DOMDocument as CFDI', $exception->getMessage());
        $this->assertSame(['2.0', '1.5'], $exception->getVersions());
        $this->assertSame($ex20, $exception->getExceptionByVersion('2.0'));
        $this->assertSame($ex15, $exception->getExceptionByVersion('1.5'));
        $this->assertSame($exceptions, $exception->getExceptions());
    }

    public function testWithVersionNotFound(): void
    {
        $exception = CfdiCreateObjectException::withVersionExceptions([]);
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Version 4.0 does not have any exception');
        $exception->getExceptionByVersion('4.0');
    }
}
