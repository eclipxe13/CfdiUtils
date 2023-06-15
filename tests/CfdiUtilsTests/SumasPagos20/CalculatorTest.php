<?php

namespace CfdiUtilsTests\SumasPagos20;

use CfdiUtils\Nodes\XmlNodeUtils;
use CfdiUtils\SumasPagos20\Calculator;
use CfdiUtilsTests\TestCase;

final class CalculatorTest extends TestCase
{
    public function testCalculateMinimal(): void
    {
        $xml = <<< XML
            <pago20:Pagos>
                <pago20:Pago MonedaP="MXN" TipoCambioP="1">
                    <pago20:DoctoRelacionado MonedaDR="MXN" EquivalenciaDR="1" ImpPagado="0.14">
                        <pago20:ImpuestosDR>
                            <pago20:TrasladosDR>
                                <pago20:TrasladoDR ImpuestoDR="002" TipoFactorDR="Tasa" TasaOCuotaDR="0.160000"
                                                   BaseDR="0.123456789" ImporteDR="0.01975308624"/>
                            </pago20:TrasladosDR>
                        </pago20:ImpuestosDR>
                    </pago20:DoctoRelacionado>
                </pago20:Pago>
            </pago20:Pagos>
            XML;
        $pagos = XmlNodeUtils::nodeFromXmlString($xml);

        $calculator = new Calculator();
        $result = $calculator->calculate($pagos);

        $this->assertSame('0.14', (string) $result->getTotales()->getTotal());
        $this->assertSame('0.12', (string) $result->getTotales()->getTrasladoIva16Base());
        $this->assertSame('0.02', (string) $result->getTotales()->getTrasladoIva16Importe());

        $impuesto = $result->getPago(0)->getImpuestos()->getTraslado('002', 'Tasa', '0.160000');
        $this->assertSame('0.123456', (string) $impuesto->getBase());
        $this->assertSame('0.019753', (string) $impuesto->getImporte());
    }

    public function testCalculateTwoDocuments(): void
    {
        $xml = <<< XML
            <pago20:Pagos Version="2.0">
                <pago20:Pago MonedaP="MXN" TipoCambioP="1">
                    <pago20:DoctoRelacionado ImpPagado="0.14" MonedaDR="MXN" EquivalenciaDR="1">
                        <pago20:ImpuestosDR>
                            <pago20:TrasladosDR>
                                <pago20:TrasladoDR ImpuestoDR="002" TipoFactorDR="Tasa" TasaOCuotaDR="0.160000"
                                                   BaseDR="0.123456789" ImporteDR="0.01975308624"/>
                            </pago20:TrasladosDR>
                        </pago20:ImpuestosDR>
                    </pago20:DoctoRelacionado>
                    <pago20:DoctoRelacionado ImpPagado="1.43" MonedaDR="MXN" EquivalenciaDR="1">
                        <pago20:ImpuestosDR>
                            <pago20:TrasladosDR>
                                <pago20:TrasladoDR ImpuestoDR="002" TipoFactorDR="Tasa" TasaOCuotaDR="0.160000"
                                                   BaseDR="1.23456789" ImporteDR="0.1975308624"/>
                            </pago20:TrasladosDR>
                        </pago20:ImpuestosDR>
                    </pago20:DoctoRelacionado>
                </pago20:Pago>
            </pago20:Pagos>
            XML;
        $pagos = XmlNodeUtils::nodeFromXmlString($xml);

        $calculator = new Calculator();
        $result = $calculator->calculate($pagos);

        $this->assertSame('1.57', (string) $result->getTotales()->getTotal());
        $this->assertSame('1.36', (string) $result->getTotales()->getTrasladoIva16Base());
        $this->assertSame('0.22', (string) $result->getTotales()->getTrasladoIva16Importe());

        $impuesto = $result->getPago(0)->getImpuestos()->getTraslado('002', 'Tasa', '0.160000');
        $this->assertSame('1.358024', (string) $impuesto->getBase());
        $this->assertSame('0.217283', (string) $impuesto->getImporte());
    }

    /**
     * In the following case, also Pago::monto is greater than Pago::montoMinimo
     */
    public function testCalculatePaymentUsdDoctosMxnAndUsd(): void
    {
        $xml = <<< XML
            <pago20:Pagos Version="2.0">
                <pago20:Pago MonedaP="USD" Monto="5.00" TipoCambioP="17.8945">
                    <pago20:DoctoRelacionado ImpPagado="0.14" MonedaDR="MXN" EquivalenciaDR="17.8945">
                        <pago20:ImpuestosDR>
                            <pago20:TrasladosDR>
                                <pago20:TrasladoDR ImpuestoDR="002" TipoFactorDR="Tasa" TasaOCuotaDR="0.160000"
                                                   BaseDR="0.123456789" ImporteDR="0.01975308624"/>
                                <pago20:TrasladoDR ImpuestoDR="002" TipoFactorDR="Tasa" TasaOCuotaDR="0.000000"
                                                   BaseDR="0.123456789" ImporteDR="0"/>
                            </pago20:TrasladosDR>
                        </pago20:ImpuestosDR>
                    </pago20:DoctoRelacionado>
                    <pago20:DoctoRelacionado ImpPagado="1.43" MonedaDR="MXN" EquivalenciaDR="17.8945">
                        <pago20:ImpuestosDR>
                            <pago20:TrasladosDR>
                                <pago20:TrasladoDR ImpuestoDR="002" TipoFactorDR="Tasa" TasaOCuotaDR="0.160000"
                                                   BaseDR="1.23456789" ImporteDR="0.1975308624"/>
                            </pago20:TrasladosDR>
                        </pago20:ImpuestosDR>
                    </pago20:DoctoRelacionado>
                    <pago20:DoctoRelacionado ImpPagado="4.01" MonedaDR="USD" EquivalenciaDR="1">
                        <pago20:ImpuestosDR>
                            <pago20:TrasladosDR>
                                <pago20:TrasladoDR ImpuestoDR="002" TipoFactorDR="Tasa" TasaOCuotaDR="0.160000"
                                                   BaseDR="3.456789" ImporteDR="0.55308624"/>
                            </pago20:TrasladosDR>
                        </pago20:ImpuestosDR>
                    </pago20:DoctoRelacionado>
                </pago20:Pago>
            </pago20:Pagos>
            XML;
        $pagos = XmlNodeUtils::nodeFromXmlString($xml);

        $calculator = new Calculator();
        $result = $calculator->calculate($pagos);

        $expectedJson = <<< JSON
            {
                "totales": {
                    "trasladoIva16Base": "63.22",
                    "trasladoIva16Importe": "10.11",
                    "trasladoIva00Base": "0.12",
                    "trasladoIva00Importe": "0.00",
                    "total": "89.47"
                },
                "pagos": [
                    {
                        "monto": "5.00",
                        "montoMinimo": "4.09",
                        "tipoCambioP": "17.8945",
                        "impuestos": {
                            "T:Traslado|I:002|F:Tasa|C:0.160000": {
                                "tipo": "Traslado",
                                "impuesto": "002",
                                "tipoFactor": "Tasa",
                                "tasaCuota": "0.160000",
                                "base": "3.532679",
                                "importe": "0.565228"
                            },
                            "T:Traslado|I:002|F:Tasa|C:0.000000": {
                                "tipo": "Traslado",
                                "impuesto": "002",
                                "tipoFactor": "Tasa",
                                "tasaCuota": "0.000000",
                                "base": "0.006899",
                                "importe": "0.000000"
                            }
                        }
                    }
                ]
            }
            JSON;
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($result));
    }
}
