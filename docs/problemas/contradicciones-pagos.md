# Contradicciones de CFDI de Pagos

A raíz de la publicación de la [*Guía de llenado del comprobante al que se le incorpore el complemento para recepción de
pagos*](https://www.sat.gob.mx/cs/Satellite?blobcol=urldata&blobkey=id&blobtable=MungoBlobs&blobwhere=1461173382672&ssbinary=true)
el 2018-08-31 y vigente a partir del 2018-09-01 se introdujeron algunas inconsistencias que chocan con las matrices de errores.


## Inconsistencia de Pago@Monto y suma de DoctoRelacionado@ImpPagado

Hasta antes de la publicación de la guía de llenado en 2018-08-31 no había inconsistencias, la regla en la
matriz de errores del complemento de pagos y la guía de llenado decían:

> Matriz regla 6: Que la suma de los valores registrados en el nodo DoctoRelacionado, atributo ImpPagado,
> sea menor o igual que el valor de este atributo.
>
> Matriz error CRP206: La suma de los valores registrados en el campo ImpPagado de los apartados DoctoRelacionado
> no es menor o igual que el valor del campo Monto.
>
> Guía de llenado: La suma de los valores registrados en el nodo DoctoRelacionado, campo ImpPagado,
> debe ser menor o igual que el valor de este campo.

Pero la nueva guía de llenado agrega:

Guía de llenado: La suma de los valores registrados en el nodo DoctoRelacionado, campo ImpPagado,
debe ser menor o igual que el valor de este campo.

- Se debe considerar la conversión a la moneda del pago registrada en el campo MonedaP
  y el margen de variación por efecto de redondeo de acuerdo a la siguiente formula:
    - Calcular el límite inferior como:
            `(ImportePagado - (10^-NumDecimalesImportePagado/2) / (TipoCambioDR + (10^-NumDecimalesTipoCambioDR)/2-0.0000000001)`
    - Calcular el límite superior como:
            `(ImportePagado + (10^-NumDecimalesImportePagado ) / 2-0.0000000001) / (TipoCambioDR - (10^-NumDecimalesTipoCambioDR /2)`

...por lo visto el SAT no sabe abrir y cerrar paréntesis, ni precedencia de operadores, ni precisiones de números.

### Entendimiento de la fórmula

La forma correcta de entender la fórmula es:

- Límite inferior: `[ImportePagado - (10^-NumDecimalesImportePagado/2)] / [TipoCambioDR + (10^-NumDecimalesTipoCambioDR)/(2 - 0.0000000001)]`
- Límite superior: `[ImportePagado + (10^-NumDecimalesImportePagado/(2 - 0.0000000001))] / [TipoCambioDR - (10^-NumDecimalesTipoCambioDR)/2]`

Entendiendo que `2 - 0.0000000001` es `casi 2`, entonces la fórmula solo pretende variar medio valor significativo al
importe pagado y al tipo de cambio, siendo entonces si el importe pagado es `1234.56` y el tipo de cambio `0.054321`:

- Límite inferior: `[1234.56 - 0.005] / [0.054321 + 0.0000005]`
- Límite superior: `[1234.56 + 0.005] / [0.054321 - 0.0000005]`

Ahora bien, teniendo en cuenta el límite superior y el límite inferior (independientemente de su cálculo),
entonces se entiende que el monto debe estar entre esos límites. Pero eso no es lo que dice la guía, la guía dice:
*La suma de los valores registrados en el nodo DoctoRelacionado, campo ImpPagado, debe ser menor o igual que el valor de este campo*.

Entonces la guía dice dos cosas:

1. Menor que suma: `Suma(DoctoRelacionado@ImpPagado) <= Pago@Monto`
2. Entre intervalos de suma: `Suma(DoctoRelacionado@ImpPagado[LímiteInferior]) <= Pago@Monto <= Suma(DoctoRelacionado@ImpPagado[LímiteSuperior])`

Pero bien sabemos que no se pueden cumplir ambas condiciones en todos los casos.

### Ejemplo de la contradicción

Haz este ejercicio: Te pagan una factura por un monto de 5,137.42 USD,
en tu banco entraron 96,426.29 MXN al TC de operaciones comerciales según el DOF de 18.7694.

El `DoctoRelacionado@TipoCambioDR` debe ser `1/18.7694`, es decir `0.0532782081`.
¿Qué valor se pone, dado que `TipoCambioDR` solo admite 6 decimales? `0.053278` o `0.053279`.

Si se pone `0.053278` en la suma de los importes pagados traducidos a la moneda del pago
se obtienen los valores máximos y mínimos y el monto del pago debe encontrarse entre estos valores:
`Máximo: 96,427.67 = REDONDEAR((5137.42+0.005) / (0.053278-0.0000005), 2)` y
`Mínimo: 96,425.67 = REDONDEAR((5137.42-0.005) / (0.053278+0.0000005), 2)`.

Validación: OK, se cumple que `96,427.67 <= 96426.29 <= 96,425.67`

Sin embargo con el valor `0.053278`, no se cumple que la suma sea menor al monto pagado.
Suma de valores: `96,426.67 = REDONDEAR(5137.42 / 0.053278, 2)`

Validación: ERROR, no se cumple que `96,426.67 <= 96,426.29`

Si se pone `0.053279` en la suma de los importes pagados traducidos a la moneda del pago
se obtienen los valores máximos y mínimos y el monto del pago debe encontrarse entre estos valores:
`Máximo: 96,425.86 = REDONDEAR((5137.42+0.005) / (0.053279-0.0000005), 2)` y
`Mínimo: 96,423.86 = REDONDEAR((5137.42-0.005) / (0.053279+0.0000005), 2)`.

Validación: ERROR, no se cumple que `96,423.86 <= 96426.29 <= 96,425.86`

Sin embargo con el valor `0.053279`, no se cumple que la suma sea menor al monto pagado.
Suma de valores: `96,424.86 = REDONDEAR(5137.42 / 0.053279, 2)`

Validación: OK, se cumple que `96,424.86 <= 96,426.29`

Esto comprueba que no se pueden cumplir ambas condiciones al mismo tiempo.

Lo realmente negativo es que esto no se reduce a la mera contradicción.
Los PAC tienen implementada la regla de `Suma(DoctoRelacionado@ImpPagado) <= Pago@Monto` y no se ha implementado
la de los límites, por lo que no se puede cumplir con las disposiciones más recientes.

Tampoco se le puede reclamar al PAC porque existe un documento (la matriz de errores) que no les dice absolutamente
nada de intervalos y solamente habla de suma menor o igual que monto.

Posibles soluciones:

1. Que el SAT aclare que el monto debe estar entre un intervalo y cancelar/actualizar el error 6 de la matriz de errores del complemento de pagos.
2. Que el SAT aclare y de por buenas cualquiera de ambas reglas.

Mientras tanto, la librería tiene validaciones con respecto a esta cuestión:

- `PAGO09`: En un pago, el monto del pago debe encontrarse entre límites mínimo y máximo de la suma
  de los valores registrados en el importe pagado de los documentos relacionados (Guía llenado).
- `PAGO30`: En un pago, la suma de los valores registrados o predeterminados en el importe pagado
  de los documentos relacionados debe ser menor o igual que el monto del pago (CRP206).

Ambas serán revisadas y en algunos casos alguna fallará.

### Convertir alguna en WARNING si fue encontrada como ERROR

Si deseas hacer caso omiso de `PAGO09` o bien de `PAGO30` te recomiendo que después de hacer las validaciones
cambies el estado de la revisión de error a advertencia, este es un ejemplo:

```php
<?php
/* Ejemplo donde se convierte el estado de los PAGO09 en un WARNING si era un error */
/** @var \CfdiUtils\CfdiCreator33 $creator */
$asserts = $creator->validate();
foreach ($asserts->errors() as $error) {
    $code = $error->getCode();
    if (fnmatch('PAGO09*', $code)) {
        $error->setStatus(\CfdiUtils\Validate\Status::warn());
    }
}
```

Si tu PAC no te deja timbrar por las inconsistencias en las reglas exígele el sustento legal o la aclaración del SAT al respecto.

Al 2018-10-01 [FinkOk](https://www.finkok.com/) marca error de timbrado si no se sigue la regla `Suma(DoctoRelacionado@ImpPagado) <= Pago@Monto`.
Si intentas poner un valor dentro del intervalo que viole la regla anterior manda error.

Al 2018-10-01 [Facturaxion](https://www.facturaxion.com/) marca error de timbrado si no se sigue la regla `Suma(DoctoRelacionado@ImpPagado) <= Pago@Monto`.
Si intentas poner un valor dentro del intervalo que viole la regla anterior manda error.


## Inconsistencia de Receptor@ResidenciaFiscal y Receptor@NumRegIdTrib

*Esta inconsistencia existe al menos desde la publicación de la guía del complemento de pagos de fecha 2017-09-07.*

Según la [Matriz de validaciones para el Comprobante fiscal digital por Internet versión 3.3](https://www.sat.gob.mx/cs/Satellite?blobcol=urldata&blobkey=id&blobtable=MungoBlobs&blobwhere=1461173347927&ssbinary=true):

> 38 - Residencia Fiscal: Si el RFC del receptor es un RFC genérico extranjero y el comprobante incluye el
> complemento de comercio exterior, debe existir este atributo.
>
> CFDI33136 - Para registrar el campo NumRegIdTrib, el CFDI debe contener el complemento de comercio exterior
> y el RFC del receptor debe ser un RFC genérico extranjero.

La primer inconsistencia existe en la propia regla, dado que en el primer parte establece:
`Si A y B entonces debe existir C` y en la segunda parte establece: `Para que exista C entonces A y B`.

Este es un error lógico pues en la `regla` establece que una obligatoriedad a partir de que dos condiciones sean verdaderas.
En cambio, en el `error` establece una obligatoriedad de condiciones si el resultado está presente.

Con la primer parte, `Si A y B entonces debe existir C`, `C` puede o no existir en otros escenarios,
pero si se cumple `A y B` entonces su existencia está obligada.

Con la segunda parte. `Para que exista C entonces A y B`, `C` solamente puede existir en los escenarios donde
se cumpla `A y B` y en ningún otro.

Esto era una insonsistencia, aunque no un problema tangible dado que en ningún lugar se exigía establecer
el atributo de Residencia Fiscal con excepción de la *Guía de llenado del comprobante al que se le incorpore el complemento para comercio exterior*.

Sin embargo, esto cambió en la guía de llenado del complemento de pagos, donde en la página 14 establece:

> Cuando el receptor del comprobante sea un residente en el extranjero, se debe registrar la clave del
> país de residencia para efectos fiscales del receptor del comprobante.
> Este campo es obligatorio cuando se registre una clave en el RFC genérica extranjera.
>
> Se captura el número de registro de identidad fiscal del receptor del comprobante fiscal cuando éste
> sea residente en el extranjero.

La inconsistencia se presenta en que:

- Si se sigue la guía de llenado y se interpreta el error 38 de la matriz, se cae en una contradicción.
- La única forma de evitar la interpretación de error 38 de la matriz es no siguiendo el deber establecido en la guía.

Posibles soluciones:

1. Que el SAT aclare el error 38 de la matriz y establezca que lo que se debe validar es la `Regla` y no el mensaje de error.
2. Que el SAT aclare que tiene más peso la matriz de errores a las guía de llenado, a pesar de sus fechas de publicación.

Mientras tanto, en la librería no existe ninguna validación en su versión original que marque algún error al respecto.

Si tu PAC no te deja timbrar un CFDI con `NumRegIdTrib` exígele el sustento legal o la aclaración del SAT al respecto.

Al 2018-10-01 [FinkOk](https://www.finkok.com/) no marca error de timbrado si se agrega el `NumRegIdTrib`.
Está interpretando la `Reglas de validación` y no el `Error`.

Al 2018-10-01 [Facturaxion](https://www.facturaxion.com/) marca error de timbrado si se agrega el `NumRegIdTrib`.
Está interpretando el `Error` y no la `Regla de validación`.
