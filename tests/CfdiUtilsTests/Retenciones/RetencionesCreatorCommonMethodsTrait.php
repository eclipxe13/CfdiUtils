<?php

namespace CfdiUtilsTests\Retenciones;

use CfdiUtils\Retenciones\RetencionesCreator10;
use CfdiUtils\Retenciones\RetencionesCreator20;
use LogicException;

trait RetencionesCreatorCommonMethodsTrait
{
    abstract public function createMinimalCreator(): RetencionesCreator10|RetencionesCreator20;

    public function testBuildCadenaDeOrigenWithoutXmlResolver(): void
    {
        $specimen = $this->createMinimalCreator();
        $specimen->setXmlResolver(null);

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Cannot build the cadena de origen since there is no xml resolver');

        $specimen->buildCadenaDeOrigen();
    }

    public function testBuildCadenaDeOrigenWithoutXsltBuilder(): void
    {
        $specimen = $this->createMinimalCreator();
        $specimen->setXsltBuilder(null);

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Cannot build the cadena de origen since there is no xslt builder');

        $specimen->buildCadenaDeOrigen();
    }
}
