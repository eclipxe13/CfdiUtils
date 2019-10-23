<?php

namespace CfdiUtils\CadenaOrigen;

class CfdiDefaultLocations
{
    const XSLT_32 = 'http://www.sat.gob.mx/sitio_internet/cfd/3/cadenaoriginal_3_2/cadenaoriginal_3_2.xslt';

    const XSLT_33 = 'http://www.sat.gob.mx/sitio_internet/cfd/3/cadenaoriginal_3_3/cadenaoriginal_3_3.xslt';

    public static function location(string $version): string
    {
        if ('3.3' === $version) {
            return static::XSLT_33;
        }
        if ('3.2' === $version) {
            return static::XSLT_32;
        }
        throw new \UnexpectedValueException("Cannot get the default xslt location for version '$version'");
    }
}
