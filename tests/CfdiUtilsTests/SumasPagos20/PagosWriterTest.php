<?php

declare(strict_types=1);

namespace CfdiUtilsTests\SumasPagos20;

use CfdiUtils\Elements\Pagos20\Pagos as ElementPagos20;
use CfdiUtils\SumasPagos20\Calculator;
use CfdiUtils\SumasPagos20\PagosWriter;
use CfdiUtilsTests\TestCase;

final class PagosWriterTest extends TestCase
{
    public function testWritePagoWithOnlyOneExento(): void
    {
        $pagos = new ElementPagos20();

        $pago = $pagos->addPago([
            'FechaPago' => '2025-06-24',
            'FormaDePagoP' => '03',
            'MonedaP' => 'MXN',
            'TipoCambioP' => '1',
            'Monto' => '10.00',
        ]);

        $pago->addDoctoRelacionado([
            'IdDocumento' => '00000000-1111-2222-3333-00000000000A',
            'MonedaDR' => 'MXN',
            'EquivalenciaDR' => '1',
            'NumParcialidad' => '1',
            'ImpSaldoAnt' => '4500.00',
            'ImpPagado' => '10.00',
            'ImpSaldoInsoluto' => '4490.00',
            'ObjetoImpDR' => '02',
        ])->getImpuestosDR()->getTrasladosDR()->addTrasladoDR([
            'BaseDR' => '10.00',
            'ImpuestoDR' => '002',
            'TipoFactorDR' => 'Exento',
        ]);

        $calculator = new Calculator(2);
        $result = $calculator->calculate($pagos);

        $writer = new PagosWriter($pagos);
        $writer->writePago($pago, $result->getPago(0));

        $traslado = $pagos->searchNode('pago20:Pago', 'pago20:ImpuestosP', 'pago20:TrasladosP', 'pago20:TrasladoP');
        $this->assertFalse(isset($traslado['TasaOCuotaP']));
        $this->assertFalse(isset($traslado['ImporteP']));
        $this->assertSame('002', $traslado['ImpuestoP']);
        $this->assertSame('10.00', $traslado['BaseP']);
    }
}
