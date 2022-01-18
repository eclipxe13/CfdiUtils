<?php

namespace CfdiUtils\Validate\Cfdi40\Xml;

use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\Validate\Asserts;
use CfdiUtils\Validate\Cfdi40\Abstracts\AbstractDiscoverableVersion40;
use CfdiUtils\Validate\Status;

/**
 * XmlDefinition
 *
 * Valida que:
 * - XML01: El XML implementa el namespace %s con el prefijo cfdi
 * - XML02: El nodo principal se llama cfdi:Comprobante
 * - XML03: La versión es 4.0
 */
final class XmlDefinition extends AbstractDiscoverableVersion40
{
    private const CFDI40_NAMESPACE = 'http://www.sat.gob.mx/cfd/4';

    public function validate(NodeInterface $comprobante, Asserts $asserts)
    {
        $asserts->put(
            'XML01',
            sprintf('El XML implementa el namespace %s con el prefijo cfdi', self::CFDI40_NAMESPACE),
            Status::when(self::CFDI40_NAMESPACE === $comprobante['xmlns:cfdi']),
            sprintf('Valor de xmlns:cfdi: %s', $comprobante['xmlns:cfdi'])
        );
        $asserts->put(
            'XML02',
            'El nodo principal se llama cfdi:Comprobante',
            Status::when('cfdi:Comprobante' === $comprobante->name()),
            sprintf('Nombre: %s', $comprobante->name())
        );
        $asserts->put(
            'XML03',
            'La versión es 4.0',
            Status::when('4.0' === $comprobante['Version']),
            sprintf('Versión: %s', $comprobante['Version'])
        );
    }
}
