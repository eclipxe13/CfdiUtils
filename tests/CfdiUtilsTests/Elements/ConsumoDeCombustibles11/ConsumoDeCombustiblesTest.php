<?php

namespace CfdiUtilsTests\Elements\ConsumoDeCombustibles11;

use CfdiUtils\Elements\ConsumoDeCombustibles11\ConceptoConsumoDeCombustibles;
use CfdiUtils\Elements\ConsumoDeCombustibles11\Conceptos;
use CfdiUtils\Elements\ConsumoDeCombustibles11\ConsumoDeCombustibles;
use CfdiUtils\Elements\ConsumoDeCombustibles11\Determinado;
use CfdiUtils\Elements\ConsumoDeCombustibles11\Determinados;
use CfdiUtilsTests\Elements\ElementTestCase;

class ConsumoDeCombustiblesTest extends ElementTestCase
{
    public function testConsumoDeCombustibles(): void
    {
        $element = new ConsumoDeCombustibles();
        $this->assertElementHasName($element, 'consumodecombustibles11:ConsumoDeCombustibles');
        $this->assertElementHasFixedAttributes($element, [
            'xmlns:consumodecombustibles11' => 'http://www.sat.gob.mx/ConsumoDeCombustibles11',
            'xsi:schemaLocation' => 'http://www.sat.gob.mx/ConsumoDeCombustibles11'
                . ' http://www.sat.gob.mx/sitio_internet/cfd/consumodecombustibles/consumodeCombustibles11.xsd',
            'version' => '1.1',
        ]);
        $this->assertElementHasChildSingle($element, Conceptos::class);
    }

    public function testConceptos(): void
    {
        $element = new Conceptos();
        $this->assertElementHasName($element, 'consumodecombustibles11:Conceptos');
        $this->assertElementHasChildMultiple($element, ConceptoConsumoDeCombustibles::class);
    }

    public function testConceptoConsumoDeCombustibles(): void
    {
        $element = new ConceptoConsumoDeCombustibles();
        $this->assertElementHasName($element, 'consumodecombustibles11:ConceptoConsumoDeCombustibles');
        $this->assertElementHasChildSingle($element, Determinados::class);
    }

    public function testDeterminados(): void
    {
        $element = new Determinados();
        $this->assertElementHasName($element, 'consumodecombustibles11:Determinados');
        $this->assertElementHasChildMultiple($element, Determinado::class);
    }

    public function testDeterminado(): void
    {
        $element = new Determinado();
        $this->assertElementHasName($element, 'consumodecombustibles11:Determinado');
    }
}
