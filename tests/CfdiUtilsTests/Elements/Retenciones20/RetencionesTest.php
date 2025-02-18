<?php

namespace CfdiUtilsTests\Elements\Retenciones20;

use CfdiUtils\Elements\Retenciones20\Addenda;
use CfdiUtils\Elements\Retenciones20\CfdiRetenRelacionados;
use CfdiUtils\Elements\Retenciones20\Complemento;
use CfdiUtils\Elements\Retenciones20\Emisor;
use CfdiUtils\Elements\Retenciones20\Extranjero;
use CfdiUtils\Elements\Retenciones20\ImpRetenidos;
use CfdiUtils\Elements\Retenciones20\Nacional;
use CfdiUtils\Elements\Retenciones20\Periodo;
use CfdiUtils\Elements\Retenciones20\Receptor;
use CfdiUtils\Elements\Retenciones20\Retenciones;
use CfdiUtils\Elements\Retenciones20\Totales;
use CfdiUtils\Nodes\NodeInterface;
use CfdiUtilsTests\Elements\ElementTestCase;

final class RetencionesTest extends ElementTestCase
{
    public function testRetenciones(): void
    {
        $element = new Retenciones();
        $this->assertElementHasName($element, 'retenciones:Retenciones');
        $this->assertElementHasFixedAttributes($element, [
            'xmlns:retenciones' => 'http://www.sat.gob.mx/esquemas/retencionpago/2',
            'xmlns:xsi' => 'http://www.w3.org/2001/XMLSchema-instance',
            'xsi:schemaLocation' => vsprintf('%s %s', [
                'http://www.sat.gob.mx/esquemas/retencionpago/2',
                'http://www.sat.gob.mx/esquemas/retencionpago/2/retencionpagov2.xsd',
            ]),
            'Version' => '2.0',
        ]);
        $this->assertElementHasChildSingle($element, CfdiRetenRelacionados::class);
        $this->assertElementHasChildSingle($element, Emisor::class);
        $this->assertElementHasChildSingle($element, Receptor::class);
        $this->assertElementHasChildSingle($element, Periodo::class);
        $this->assertElementHasChildSingle($element, Totales::class);
        $this->assertElementHasChildSingleAddChild($element, Complemento::class);
        $this->assertElementHasChildSingleAddChild($element, Addenda::class);
        $this->assertElementHasOrder($element, [
            'retenciones:CfdiRetenRelacionados',
            'retenciones:Emisor',
            'retenciones:Receptor',
            'retenciones:Periodo',
            'retenciones:Totales',
            'retenciones:Complemento',
            'retenciones:Addenda',
        ]);
    }

    public function testCfdiRetenRelacionados(): void
    {
        $element = new CfdiRetenRelacionados();
        $this->assertElementHasName($element, 'retenciones:CfdiRetenRelacionados');
    }

    public function testEmisor(): void
    {
        $element = new Emisor();
        $this->assertElementHasName($element, 'retenciones:Emisor');
    }

    public function testReceptor(): void
    {
        $element = new Receptor();
        $this->assertElementHasName($element, 'retenciones:Receptor');
        $this->assertElementHasChildSingle($element, Nacional::class);
        $this->assertElementHasChildSingle($element, Extranjero::class);
    }

    public function testNacional(): void
    {
        $element = new Nacional();
        $this->assertElementHasName($element, 'retenciones:Nacional');
    }

    public function testExtranjero(): void
    {
        $element = new Extranjero();
        $this->assertElementHasName($element, 'retenciones:Extranjero');
    }

    public function testPeriodo(): void
    {
        $element = new Periodo();
        $this->assertElementHasName($element, 'retenciones:Periodo');
    }

    public function testTotales(): void
    {
        $element = new Totales();
        $this->assertElementHasName($element, 'retenciones:Totales');
        $this->assertElementHasChildMultiple($element, ImpRetenidos::class);
    }

    public function testImpRetenidos(): void
    {
        $element = new ImpRetenidos();
        $this->assertElementHasName($element, 'retenciones:ImpRetenidos');
    }

    public function testComplemento(): void
    {
        $element = new Complemento();
        $this->assertElementHasName($element, 'retenciones:Complemento');
    }

    public function testAddenda(): void
    {
        $element = new Addenda();
        $this->assertElementHasName($element, 'retenciones:Addenda');
    }

    public function testShortcutRetencionImpRetenidos(): void
    {
        $element = new Retenciones();

        $first = $element->addImpRetenidos(['id' => '1']);
        $this->assertCount(1, $element->getTotales()->children());
        $this->assertTrue($element->getTotales()->children()->exists($first));

        $second = $element->addImpRetenidos(['id' => '2']);
        $this->assertCount(2, $element->getTotales()->children());
        $this->assertTrue($element->getTotales()->children()->exists($second));

        $this->assertSame(
            $element,
            $element->multiImpRetenidos(['id' => '3'], ['id' => '4']),
            'Method Retenciones::multiImpRetenidos should return retenciones self instance'
        );
        $this->assertCount(4, $element->getTotales()->children());

        $this->assertSame(
            ['1', '2', '3', '4'],
            array_map(
                fn (NodeInterface $element): string => $element['id'],
                iterator_to_array($element->getTotales()->children())
            ),
            'All elements added should exists with expected values'
        );
    }
}
