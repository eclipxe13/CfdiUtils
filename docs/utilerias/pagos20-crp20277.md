# Regla CRP20277 del complemento de pagos 2.0

El SAT creó la regla `CRP20277` en los Documentos técnicos del complemento de recepción de pagos 2.0, revisión B,
vigente a partir del 15 de enero de 2024, en la Matriz de errores. Donde dice:

- Validación:
  *Cuando existan operaciones con más de un Documento relacionado en donde al menos uno de ellos contenga
  la misma moneda que la del Pago, para la fórmula en el cálculo del margen de variación se deben
  considerar 10 decimales en la EquivalenciaDR cuando el valor sea 1.*
- Código de error:
  *El campo EquivalenciaDR debe contener el valor "1.0000000000".*

Esta regla cambia lo especificado en la regla `CRP20238`. Que establece que si el atributo `Pago@MonedaP` es igual
a `DoctoRelacionado@MonedaDR` entonces el valor de `DoctoRelacionado@EquivalenciaDR` debe ser `"1"`.

La regla `CRP20277` establece entonces que el valor debe ser `"1.0000000000"`, siempre que se cumplan las siguientes condiciones para el pago:

- *con más de un documento relacionado*:
  Es decir, no aplica para cuando hay solo 1 `DoctoRelacionado`, debe haber al menos 2.
- *al menos uno de ellos contenga la misma moneda que la del Pago*:
  Es decir, aplica con que exista 1 `DoctoRelacionado` donde el valor de `Pago@MonedaP` es el mismo que `DoctoRelacionado@MonedaDR`.
- *se deben considerar 10 decimales en la EquivalenciaDR cuando el valor sea 1*:
  Es decir, `DoctoRelacionado@EquivalenciaDR` debe ser `"1"` (y va a cambiar a `"1.0000000000"`).

Y si las tres condiciones se cumplen:

- (a) *se deben considerar 10 decimales*, (b) *el campo EquivalenciaDR debe contener el valor "1.0000000000"*:
  Es decir, el valor de `DoctoRelacionado@EquivalenciaDR` cambia de `"1"` a `"1.0000000000"`.

## Utilería `Crp20277Fixer`

Para facilitar la aplicación de la regla `CRP20277` y modificar `@EquivalenciaDR`, se ha creado
la clase `Crp20277Fixer`, que hace las revisiones necesarias para cuando es necesario cambiar
el valor de `DoctoRelacionado@EquivalenciaDR` de `"1"` a `"1.0000000000"`.

Recomendación de uso:

- Fabrica el *Pre-CFDI* considerando el valor `1` cuando `Pago@MonedaP` es igual a `DoctoRelacionado@MonedaDR`,
  tal como do dice la regla `CRP20238`.
- Después de llenar el complemento y antes de firmar el *Pre-CFDI*, llama al método `Crp20277Fixer::staticFix()`.

Para el siguiente ejemplo se omite la lógica de cómo crear o llenar un CFDI 4.0 con Complemento de pagos 2.0,
la parte importante es ilustrar el llamado a `Crp20277Fixer::staticFix()`.

```php
<?php

use CfdiUtils\CfdiCreator40;
use CfdiUtils\Elements\Pagos20\Pagos;
use CfdiUtils\Utils\Crp20277Fixer;

// se fabrica el creador de CFDI 4.0
$creator = new CfdiCreator40(/* atributos del comprobante */);

// se crea el complemento de pagos
$complementoPagos = new Pagos();
// se agrega un pago
$pago = $complementoPagos->addPago([/* atributos del pago */]);
// se agrega uno o más documentos
$pago->addDoctoRelacionado([/* atributos del documento relacionado */]);


// se llama a la utilería para cambiar los valores DoctoRelacionado@MonedaDR de 1 a 1.0000000000 cuando sea necesario
Crp20277Fixer::staticFix($complementoPagos);


// se sigue con la lógica de firmado del Pre-CFDI, timbrado con el PAC, etc. 
$creator->addSello($key, $password);
```

## Reflexión de la regla

Creo que el SAT ha cometido un error grave en esta regla, dado que la necesidad de 10 decimales
solamente aplica para el cálculo del margen de variación, dentro de las validaciones de un PAC.

Se trata de un valor que se puede *interpretar* de esta forma (con diez decimales), a pesar de que esté escrito sin decimales.

Sería suficiente con poner una nota en la validación dentro del estándar del complemento, y una nota en la guía de llenado.

Para la matriz de errores, se puede especificar (como en el caso de CFDI) que es una regla que solo aplica para los PAC.
Y donde dice *El campo EquivalenciaDR debe contener el valor "1.0000000000".*,
debería decir *El campo EquivalenciaDR se debe interpretar con el valor "1.0000000000" para el cálculo del margen de variación.*

Pero, al no hacerlo, se tiene que implementar una doble lógica, la primera es para seguir la especificación de `CRP20238`
y la segunda para cumplir con el requisito de la regla `CRP20277`.
