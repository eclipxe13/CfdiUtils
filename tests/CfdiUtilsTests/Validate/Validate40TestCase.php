<?php

namespace CfdiUtilsTests\Validate;

use CfdiUtils\Nodes\Node;

abstract class Validate40TestCase extends ValidateBaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->comprobante = new Node('cfdi:Comprobante', [
            'xmlns:cfdi' => 'http://www.sat.gob.mx/cfd/4',
            'Version' => '4.0',
        ]);
    }

}
