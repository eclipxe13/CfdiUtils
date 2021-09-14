<?php

namespace CfdiUtils\Retenciones;

use CfdiUtils\VersionDiscovery\VersionDiscoverer;

/**
 * This class provides the methods to retrieve the version attribute from a
 * CFDI de Retenciones e información de pagos (Retenciones)
 *
 * It will not check anything but the value of the correct attribute
 * It will not care if the element is following a schema or element's name
 *
 * Possible values are always 1.0 or empty string
 */
final class RetencionesVersion extends VersionDiscoverer
{
    public function rules(): array
    {
        return [
            '1.0' => 'Version',
        ];
    }
}
