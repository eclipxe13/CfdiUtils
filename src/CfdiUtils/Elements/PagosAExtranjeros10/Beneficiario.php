<?php

namespace CfdiUtils\Elements\PagosAExtranjeros10;

use CfdiUtils\Elements\Common\AbstractElement;

class Beneficiario extends AbstractElement
{
    public function getElementName(): string
    {
        return 'pagosaextranjeros:Beneficiario';
    }
}
