# Regla CRP20277 del complemento de pagos 2.0

!!! note ""
Esta regla fue modificada el 2024-01-16 y no es necesario cambiar el valor de `EquivalenciaDR`.

El SAT creó la regla `CRP20277` en los Documentos técnicos del complemento de recepción de pagos 2.0, revisión B,
vigente a partir del 15 de enero de 2024, en la Matriz de errores. Donde dice:

- Validación:
  *Cuando existan operaciones con más de un Documento relacionado en donde al menos uno de ellos contenga
  la misma moneda que la del Pago, para la fórmula en el cálculo del margen de variación se deben
  considerar 10 decimales en la EquivalenciaDR cuando el valor sea 1.*
- Código de error:
  ~~*El campo EquivalenciaDR debe contener el valor "1.0000000000".*~~
  *El valor de EquivalenciaDR  para la fórmula del cálculo del margen de variación debe ser “1.0000000000”.*

Esta regla **no cambia** lo especificado en la regla `CRP20238`. Que establece que si el atributo `Pago@MonedaP` es igual
a `DoctoRelacionado@MonedaDR` entonces el valor de `DoctoRelacionado@EquivalenciaDR` debe ser `"1"`.

La regla `CRP20277` establece entonces que el valor debe **considerarse** como `"1.0000000000"`,
siempre que se cumplan las siguientes condiciones para el pago:

- *con más de un documento relacionado*:
  Es decir, no aplica para cuando hay solo 1 `DoctoRelacionado`, debe haber al menos 2.
- *al menos uno de ellos contenga la misma moneda que la del Pago*:
  Es decir, aplica con que exista 1 `DoctoRelacionado` donde el valor de `Pago@MonedaP` es el mismo que `DoctoRelacionado@MonedaDR`.
- *se deben considerar 10 decimales en la EquivalenciaDR cuando el valor sea 1*:
  Es decir, `DoctoRelacionado@EquivalenciaDR` debe ser `"1"` (y se considerará para la validación como `"1.0000000000"`).

Y si las tres condiciones se cumplen:

- (a) *se deben considerar 10 decimales*, ~~(b) *el campo EquivalenciaDR debe contener el valor "1.0000000000"*~~:
  Es decir, el valor de `DoctoRelacionado@EquivalenciaDR` se considera como `"1.0000000000"` y no como `"1"`.

## Utilería `Crp20277Fixer`

Para facilitar la aplicación de la regla `CRP20277` y modificar `@EquivalenciaDR` cuando era necesario,
se creó la clase `Crp20277Fixer`, que hace las revisiones necesarias para cuando era necesario cambiar
el valor de `DoctoRelacionado@EquivalenciaDR` de `"1"` a `"1.0000000000"`.

A partir de 2024-01-16, no es necesario hacer el cambio, dado que el valor `1` se debe mantener en el CFDI,
y solamente para hacer las validaciones, se debe considerar como `"1.0000000000"`. Luego entonces,
esta utilería ha sido deprecada desde la versión `2.28.0` y no se recomienda su uso.

## Reflexión de la regla

### En su implementación original

Creo que el SAT ha cometido un error grave en esta regla, dado que la necesidad de 10 decimales
solamente aplica para el cálculo del margen de variación, dentro de las validaciones de un PAC.

Se trata de un valor que se puede *interpretar* de esta forma (con diez decimales), a pesar de que esté escrito sin decimales.

Sería suficiente con poner una nota en la validación dentro del estándar del complemento, y una nota en la guía de llenado.

Para la matriz de errores, se puede especificar (como en el caso de CFDI) que es una regla que solo aplica para los PAC.
Y donde dice *El campo EquivalenciaDR debe contener el valor "1.0000000000".*,
debería decir *El campo EquivalenciaDR se debe interpretar con el valor "1.0000000000" para el cálculo del margen de variación.*

Pero, al no hacerlo, se tiene que implementar una doble lógica, la primera es para seguir la especificación de `CRP20238`
y la segunda para cumplir con el requisito de la regla `CRP20277`.

### En su implementación a partir de 2024-01-16

Afortunadamente, el SAT ha recapacitado y ha cambiado el mensaje de error, con este cambio, se elimina la doble lógica
y la contradicción entre `CRP20238` y `CRP20277` mencionada anteriormente.

Esta modificación del valor aplica únicamente para los PAC o aquellos que calculen el margen de variación por su propia cuenta.
