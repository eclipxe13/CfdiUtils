<?php

namespace CfdiUtils;

use CfdiUtils\VersionDiscovery\StaticMethodsCompatTrait;
use CfdiUtils\VersionDiscovery\VersionDiscoverer;

/**
 * This class provides static methods to retrieve the version attribute from a
 * Comprobante Fiscal Digital por Internet (CFDI)
 *
 * It will not check anything but the value of the correct attribute
 * It will not care if the cfdi is following a schema or element's name
 *
 * Possible values are always 3.2, 3.3, 4.0 or empty string
 */
class CfdiVersion extends VersionDiscoverer
{
    use StaticMethodsCompatTrait;

    protected static function createDiscoverer(): VersionDiscoverer
    {
        return new self();
    }

    public function rules(): array
    {
        return [
            '4.0' => 'Version',
            '3.3' => 'Version',
            '3.2' => 'version',
        ];
    }
}
