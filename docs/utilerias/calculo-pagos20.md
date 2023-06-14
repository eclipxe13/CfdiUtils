# Cálculo de CFDI con complemento de pagos 2.0

Es frecuente tener conflictos para hacer los cálculos de los atributos que van en el
*Complemento para recepción de Pagos versión 2.0*, para ello se ha creado la utilería
`CfdiUtils\SumasPagos20\Calculator`.

En resumen, los datos calculados son: `Totales`, `Pago\Impuestos` y `Pago@MontoMínimo`.

También se provee de un escritor de los datos de los cálculos `CfdiUtils\SumasPagos20\PagosWriter`
para escribir en el nodo del complemento de pagos.

Importante: Para poder utilizar esta herramienta, es importante tener instalada la extensión
[`BCMath`](https://www.php.net/manual/en/book.bc.php).

## Ejemplo de uso

```php
<?php
use \CfdiUtils\SumasPagos20\Calculator;
use \CfdiUtils\SumasPagos20\Currencies;
use \CfdiUtils\SumasPagos20\PagosWriter;

/**
 * La variable $pagos tiene el pago con los datos requeridos
 * @var \CfdiUtils\Elements\Pagos20\Pagos $pagos 
 */
 
// Se puede usar el método estático
PagosWriter::calculateAndPut($pagos);

// Se puede calcular y mandar a escribir
$pagosCalculator = new Calculator(
    2, // Decimales a usar en los impuestos de los pagos
    new Currencies(['MXN' => 2, 'USD' => '2', 'EUR' => 2]) // Monedas con decimales
);
$result = $pagosCalculator->calculate($pagos);
$pagosWriter = new PagosWriter($pagos);
$pagosWriter->writePago($result);
```

## Origen de los datos

Para hacer los cálculos, se require de forma general un *Pre-CFDI* con algunos datos armados:

- `Pagos`: Nodo del complemento
    - `Pago`: Nodo de un pago
        - `@Monto`: Atributo que establece el monto del pago (opcional para el cálculo).
        - `@MonedaP`: Moneda del pago (para saber los dígitos soportados).
        - `@TipoCambioP`: Factor de conversión de la moneda del pago a MXN.
        - `DocumentoRelacionado`: Nodo del documento relacionado.
            - `@ImpPagado`: Monto pagado.
            - `@EquivalenciaDR`: Factor de equivalencia de la moneda del documento a la moneda del pago.
            - `ImpuestosDR`: Nodo de impuestos del documento relacionado, **con todos sus hijos y atributos**.

## Cálculo y resultado

La herramienta de cálculo obtiene los valores del *Pre-CFDI* y procesa cada elemento `Pago`,
cada elemento hijo `DocumentoRelacionado` y cada elemento hijo `ImpuestosDR\RetencionesDR\RetencionDR`
e `ImpuestosDR\TrasladosDR\TrasladoDR` para calcular *Totales* e *Información de pagos*.

Los objetos devueltos son inmutables, en futuras versiones será reforzada esta característica.
Adicionalmente, los objetos son exportables a formato JSON para proveer una mejor experiencia de desarrollo.

El resultado es un objeto `CfdiUtils\SumasPagos20\Pagos` que contiene:

- `Pagos::getTotales(): Totales`: Información de totales.
- `Pagos::getPagos(): Pago[]`: Arreglo de pagos.
- `Pagos::getPago(int $index): Pago`: Obtiene un pago.

El objeto `CfdiUtils\SumasPagos20\Totales` contiene:

- `Totales::getRetencionIva(): Decimal|null`.
- `Totales::getRetencionIsr(): Decimal|null`.
- `Totales::getRetencionIeps(): Decimal|null`.
- `Totales::getTrasladoIva16Base(): Decimal|null`.
- `Totales::getTrasladoIva16Importe(): Decimal|null`.
- `Totales::getTrasladoIva08Base(): Decimal|null`.
- `Totales::getTrasladoIva08Importe(): Decimal|null`.
- `Totales::getTrasladoIva00Base(): Decimal|null`.
- `Totales::getTrasladoIva00Importe(): Decimal|null`.
- `Totales::getTrasladoIvaExento(): Decimal|null`.
- `Totales::getTotal(): Decimal`.

En caso de devolver `null` significa que no existe información para establecer en el total.

El objeto `Pago` contiene:

- `Pago::getMonto(): Decimal`: Monto obtenido del nodo, o bien, el monto mínimo.
- `Pago::getMontoMinimo(): Decimal`: Monto mínimo que debe existir en el atributo `Pago@Monto`.
- `Pago::getTipoCambioP(): Decimal`: Tipo de cambio para MXN obtenido del atributo `Pago@Monto`.
- `Pago::getImpuestos(): Impuestos`: Conjunto de impuestos que deben existir en el nodo `Pago\ImpuestosP`.

El objeto `Impuestos` contiene:

- `Impuestos::getRetencion(string $impuesto): Impuesto`: Método para obtener una retención según su clave de impuesto.
- `Impuestos::getTraslado(string $impuesto, string $tipoFactor, string $tasaCuota): Impuesto`:
  Método para obtener un traslado según su clave de impuesto, clave de tipo de factor y valor de tasa o cuota.

El objeto `Impuesto` contiene:

- `Impuesto::getBase(): Decimal`: Valor del monto base.
- `Impuesto::getImporte(): Decimal`: Valor del importe.

El objeto `Decimal`:

Este es un objeto especial para realizar operaciones matemáticas con precisión.
Requiere de la librería `BCMath`.

- `strval(Decimal)`: `Decimal` es un `Stringable`, por lo que puede ser convertido a cadena de caracteres.
- `Decimal::round(int $decimals): Decimal`: obtiene el valor redondeado a un número de decimales.

## Nota de `BCMath`

Trabajar con números de punto flotante no es sencillo.
Para el 99% de los casos con simplemente tener cuidado al leer, escribir, comparar, y redondear es suficiente.
Sin embargo, este proceso resulta complicado para la función de truncado.

Por ello, es mejor usar la estrategia de trabajar con los números como cadenas de caracteres y delegar las operaciones
matemáticas a la extensión `BCMath` *Arbitrary Precision Mathematics*.

Existen otras extensiones y otras formas de hacer esta tarea, sin embargo, esta estrategia es de las más aceptadas
entre los desarrollos de PHP.
