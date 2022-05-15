<?php

namespace CfdiUtils\Retenciones;

use CfdiUtils\VersionDiscovery\StaticMethodsCompatTrait;
use CfdiUtils\VersionDiscovery\VersionDiscoverer;

/**
 * This class provides static methods to retrieve the version attribute from a
 * Comprobante Fiscal Digital por Internet que ampara retenciones e información de Pagos
 *
 * It will not check anything but the value of the correct attribute
 * It will not care if the cfdi is following a schema or element's name
 *
 * Possible values are always 1.0, 2.0 or empty string
 */
class RetencionVersion extends VersionDiscoverer
{
    use StaticMethodsCompatTrait;

    protected static function createDiscoverer(): VersionDiscoverer
    {
        return new self();
    }

    public function rules(): array
    {
        return [
            '2.0' => 'Version',
            '1.0' => 'Version',
        ];
    }
}
