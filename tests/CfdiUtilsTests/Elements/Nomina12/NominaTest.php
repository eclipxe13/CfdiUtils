<?php

namespace CfdiUtilsTests\Elements\Nomina12;

use CfdiUtils\Elements\Nomina12\Deducciones;
use CfdiUtils\Elements\Nomina12\Emisor;
use CfdiUtils\Elements\Nomina12\Incapacidades;
use CfdiUtils\Elements\Nomina12\Nomina;
use CfdiUtils\Elements\Nomina12\OtrosPagos;
use CfdiUtils\Elements\Nomina12\Percepciones;
use CfdiUtils\Elements\Nomina12\Receptor;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CfdiUtils\Elements\Nomina12\Nomina
 */
final class NominaTest extends TestCase
{
    public Nomina $element;

    protected function setUp(): void
    {
        parent::setUp();
        $this->element = new Nomina();
    }

    public function testConstructedObject(): void
    {
        $this->assertSame('nomina12:Nomina', $this->element->getElementName());
    }

    public function testChildrenOrder(): void
    {
        $expected = [
            'nomina12:Emisor',
            'nomina12:Receptor',
            'nomina12:Percepciones',
            'nomina12:Deducciones',
            'nomina12:OtrosPagos',
            'nomina12:Incapacidades',
        ];
        $this->assertSame($expected, $this->element->getChildrenOrder());
    }

    public function testFixedVersion(): void
    {
        $this->assertSame('1.2', $this->element['Version']);
    }

    public function testFixedNamespaceDefinition(): void
    {
        $namespace = 'http://www.sat.gob.mx/nomina12';
        $this->assertSame($namespace, $this->element['xmlns:nomina12']);
        $xsdLocation = 'http://www.sat.gob.mx/sitio_internet/cfd/nomina/nomina12.xsd';
        $this->assertSame($namespace . ' ' . $xsdLocation, $this->element['xsi:schemaLocation']);
    }

    public function testGetEmisor(): void
    {
        $this->assertCount(0, $this->element->searchNodes('nomina12:Emisor'));

        $first = $this->element->getEmisor();
        $this->assertCount(1, $this->element->searchNodes('nomina12:Emisor'));

        $second = $this->element->getEmisor();
        $this->assertCount(1, $this->element->searchNodes('nomina12:Emisor'));

        $this->assertSame($first, $second);
    }

    public function testAddEmisor(): void
    {
        // insert first element
        $first = $this->element->addEmisor(['id' => 'first']);
        $this->assertInstanceOf(Emisor::class, $first);
        $this->assertSame('first', $first['id']);
        $this->assertCount(1, $this->element);

        // insert second element data should return the same element
        $second = $this->element->addEmisor(['id' => 'second']);
        $this->assertSame($first, $second);
        $this->assertSame('second', $first['id']);
    }

    public function testGetReceptor(): void
    {
        $this->assertCount(0, $this->element->searchNodes('nomina12:Receptor'));

        $first = $this->element->getReceptor();
        $this->assertCount(1, $this->element->searchNodes('nomina12:Receptor'));

        $second = $this->element->getReceptor();
        $this->assertCount(1, $this->element->searchNodes('nomina12:Receptor'));

        $this->assertSame($first, $second);
    }

    public function testAddReceptor(): void
    {
        // insert first element
        $first = $this->element->addReceptor(['id' => 'first']);
        $this->assertInstanceOf(Receptor::class, $first);
        $this->assertSame('first', $first['id']);
        $this->assertCount(1, $this->element);

        // insert second element data should return the same element
        $second = $this->element->addReceptor(['id' => 'second']);
        $this->assertSame($first, $second);
        $this->assertSame('second', $first['id']);
    }

    public function testGetPercepciones(): void
    {
        $this->assertCount(0, $this->element->searchNodes('nomina12:Percepciones'));

        $first = $this->element->getPercepciones();
        $this->assertCount(1, $this->element->searchNodes('nomina12:Percepciones'));

        $second = $this->element->getPercepciones();
        $this->assertCount(1, $this->element->searchNodes('nomina12:Percepciones'));

        $this->assertSame($first, $second);
    }

    public function testAddPercepciones(): void
    {
        // insert first element
        $first = $this->element->addPercepciones(['id' => 'first']);
        $this->assertInstanceOf(Percepciones::class, $first);
        $this->assertSame('first', $first['id']);
        $this->assertCount(1, $this->element);

        // insert second element data should return the same element
        $second = $this->element->addPercepciones(['id' => 'second']);
        $this->assertSame($first, $second);
        $this->assertSame('second', $first['id']);
    }

    public function testGetDeducciones(): void
    {
        $this->assertCount(0, $this->element->searchNodes('nomina12:Deducciones'));

        $first = $this->element->getDeducciones();
        $this->assertCount(1, $this->element->searchNodes('nomina12:Deducciones'));

        $second = $this->element->getDeducciones();
        $this->assertCount(1, $this->element->searchNodes('nomina12:Deducciones'));

        $this->assertSame($first, $second);
    }

    public function testAddDeducciones(): void
    {
        // insert first element
        $first = $this->element->addDeducciones(['id' => 'first']);
        $this->assertInstanceOf(Deducciones::class, $first);
        $this->assertSame('first', $first['id']);
        $this->assertCount(1, $this->element);

        // insert second element data should return the same element
        $second = $this->element->addDeducciones(['id' => 'second']);
        $this->assertSame($first, $second);
        $this->assertSame('second', $first['id']);
    }

    public function testGetOtrosPagos(): void
    {
        $this->assertCount(0, $this->element->searchNodes('nomina12:OtrosPagos'));

        $first = $this->element->getOtrosPagos();
        $this->assertCount(1, $this->element->searchNodes('nomina12:OtrosPagos'));

        $second = $this->element->getOtrosPagos();
        $this->assertCount(1, $this->element->searchNodes('nomina12:OtrosPagos'));

        $this->assertSame($first, $second);
    }

    public function testAddOtrosPagos(): void
    {
        // insert first element
        $first = $this->element->addOtrosPagos(['id' => 'first']);
        $this->assertInstanceOf(OtrosPagos::class, $first);
        $this->assertSame('first', $first['id']);
        $this->assertCount(1, $this->element);

        // insert second element data should return the same element
        $second = $this->element->addOtrosPagos(['id' => 'second']);
        $this->assertSame($first, $second);
        $this->assertSame('second', $first['id']);
    }

    public function testGetIncapacidades(): void
    {
        $this->assertCount(0, $this->element->searchNodes('nomina12:Incapacidades'));

        $first = $this->element->getIncapacidades();
        $this->assertCount(1, $this->element->searchNodes('nomina12:Incapacidades'));

        $second = $this->element->getIncapacidades();
        $this->assertCount(1, $this->element->searchNodes('nomina12:Incapacidades'));

        $this->assertSame($first, $second);
    }

    public function testAddIncapacidades(): void
    {
        // insert first element
        $first = $this->element->addIncapacidades(['id' => 'first']);
        $this->assertInstanceOf(Incapacidades::class, $first);
        $this->assertSame('first', $first['id']);
        $this->assertCount(1, $this->element);

        // insert second element data should return the same element
        $second = $this->element->addIncapacidades(['id' => 'second']);
        $this->assertSame($first, $second);
        $this->assertSame('second', $first['id']);
    }
}
