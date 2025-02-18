<?php

namespace CfdiUtilsTests\Validate\Cfdi33\RecepcionPagos\Helpers;

use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Helpers\FormaPagoCatalog;
use PHPUnit\Framework\TestCase;

final class FormaPagoCatalogTest extends TestCase
{
    public function providerObtain(): array
    {
        return [
            'Efectivo' => ['01'],
            'Cheque nominativo' => ['02'],
            'Transferencia electrónica de fondos' => ['03'],
            'Tarjeta de crédito' => ['04'],
            'Monedero electrónico' => ['05'],
            'Dinero electrónico' => ['06'],
            'Vales de despensa' => ['08'],
            'Dación en pago' => ['12'],
            'Pago por subrogación' => ['13'],
            'Pago por consignación' => ['14'],
            'Condonación' => ['15'],
            'Compensación' => ['17'],
            'Novación' => ['23'],
            'Confusión' => ['24'],
            'Remisión de deuda' => ['25'],
            'Prescripción o caducidad' => ['26'],
            'A satisfacción del acreedor' => ['27'],
            'Tarjeta de débito' => ['28'],
            'Tarjeta de servicios' => ['29'],
            'Por definir' => ['99'],
        ];
    }

    /**
     * @dataProvider providerObtain
     */
    public function testObtain(string $key): void
    {
        $paymentType = (new FormaPagoCatalog())->obtain($key);
        $this->assertSame($key, $paymentType->key());
    }

    public function testObtainWithNonExistentKey(): void
    {
        $this->expectException(\OutOfBoundsException::class);
        $this->expectExceptionMessage('FOO');
        (new FormaPagoCatalog())->obtain('FOO');
    }
}
