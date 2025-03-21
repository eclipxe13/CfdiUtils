<?php

namespace CfdiUtils\TimbreFiscalDigital;

use CfdiUtils\VersionDiscovery\VersionDiscoverer;

/**
 * This class provides static methods to retrieve the version attribute from a
 * Timbre Fiscal Digital (TFD)
 *
 * It will not check anything but the value of the correct attribute
 * It will not care if the cfdi is following a schema or element's name
 *
 * Possible values are always 1.0, 1.1 or empty string
 */
class TfdVersion extends VersionDiscoverer
{
    public function rules(): array
    {
        return [
            '1.1' => 'Version',
            '1.0' => 'version',
        ];
    }
}
