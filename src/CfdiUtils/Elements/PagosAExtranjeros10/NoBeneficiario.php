<?php

namespace CfdiUtils\Elements\PagosAExtranjeros10;

use CfdiUtils\Elements\Common\AbstractElement;

class NoBeneficiario extends AbstractElement
{
    public function getElementName(): string
    {
        return 'pagosaextranjeros:NoBeneficiario';
    }
}
