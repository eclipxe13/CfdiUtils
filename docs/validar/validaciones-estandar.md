# Validaciones estándar para CFDI 3.3

Las validaciones estándar se deben realizar tanto para CFDI creados como para CFDI recibidos.


## XmlFollowSchema

Valida que el archivo XML sigue con los esquemas que tiene declarados contra los
archivos XSD que tenga declarados en los campos `xsi:schemaLocation`.
Este es uno de los validadores más útiles porque revisa la estructura contra el SAT, incluyendo los catálogos.
Cuando este validador falla regresa un estado `mustStop` que previene la ejecución de futuros
validadores dentro de un objeto `MultiValidator`.

- XSD01: El contenido XML sigue los esquemas XSD


## ComprobanteDecimalesMoneda

Valida que los atributos no tengan más del máximo número de decimales que permite la moneda,
esto incluye los ceros a la izquierda. Si la moneda no es USD, EUR, MXN o XXX entonces todos los estados son NONE.

- `MONDEC01`: El subtotal del comprobante no contiene más de los decimales de la moneda (CFDI33106)
- `MONDEC02`: El descuento del comprobante no contiene más de los decimales de la moneda (CFDI33111)
- `MONDEC03`: El total del comprobante no contiene más de los decimales de la moneda
- `MONDEC04`: El total de impuestos trasladados no contiene más de los decimales de la moneda (CFDI33182)
- `MONDEC05`: El total de impuestos retenidos no contiene más de los decimales de la moneda (CFDI33180)


## ComprobanteFormaPago

Valida que si el complemento de pagos existe entonces no debe existir el atributo forma de pago.
En caso de que el complemento de pagos no exista esta validación no tiene ningún efecto.

- `FORMAPAGO01`: El campo forma de pago no debe existir cuando existe el complemento para recepción de pagos (CFDI33103)


## ComprobanteImpuestos

Valida el nodo de impuestos del comprobante

- `COMPIMPUESTOSC01`: Si existe el nodo de impuestos entonces debe incluir el total de traslados y/o el total de retenciones
- `COMPIMPUESTOSC02`: Si existe al menos un traslado entonces debe existir el total de traslados
- `COMPIMPUESTOSC03`: Si existe al menos una retención entonces debe existir el total de retenciones


## ComprobanteTipoCambio

- `TIPOCAMBIO01`: La moneda exista y no tenga un valor vacío
- `TIPOCAMBIO02`: Si la moneda es "MXN", entonces el tipo de cambio debe tener el valor "1" o no debe existir (CFDI33113)
- `TIPOCAMBIO03`: Si la moneda es "XXX", entonces el tipo de cambio no debe existir (CFDI33115)
- `TIPOCAMBIO04`: Si la moneda no es "MXN" ni "XXX", entonces el tipo de cambio entonces
  el tipo de cambio debe seguir el patrón [0-9]{1,18}(.[0-9]{1,6})? (CFDI33114, CFDI33117)


## ComprobanteTipoDeComprobante

Realiza diferentes validaciones relacionadas con el tipo de comprobante:

- `TIPOCOMP01`: Si el tipo de comprobante es T, P o N, entonces no debe existir las condiciones de pago
- `TIPOCOMP02`: Si el tipo de comprobante es T, P o N, entonces no debe existir la definición de impuestos (CFDI33179)
- `TIPOCOMP03`: Si el tipo de comprobante es T o P, entonces no debe existir la forma de pago
- `TIPOCOMP04`: Si el tipo de comprobante es T o P, entonces no debe existir el método de pago (CFDI33123)
- `TIPOCOMP05`: Si el tipo de comprobante es T o P, entonces no debe existir el descuento del comprobante (CFDI33110)
- `TIPOCOMP06`: Si el tipo de comprobante es T o P, entonces no debe existir el descuento de los conceptos (CFDI33179)
- `TIPOCOMP07`: Si el tipo de comprobante es T o P, entonces el subtotal debe ser cero (CFDI33108)
- `TIPOCOMP08`: Si el tipo de comprobante es T o P, entonces el total debe ser cero
- `TIPOCOMP09`: Si el tipo de comprobante es I, E o N, entonces el valor unitario de todos los conceptos debe ser mayor que cero
- `TIPOCOMP10`: Si el tipo de comprobante es N, entonces la moneda debe ser MXN


## ComprobanteDescuento

- DESCUENTO01: Si existe el atributo descuento, entonces debe ser menor o igual que el subtotal
               y mayor o igual que cero (CFDI33109)


## ComprobanteTotal

- TOTAL01: El atributo Total existe, no está vacío y cumple con el patrón [0-9]+(.[0-9]+)?


## ConceptoDescuento

Estas validaciones son exclusivas del atributo descuento del concepto:

- `CONCEPDESC01`: Si existe el atributo descuento en el concepto,
  entonces debe ser menor o igual que el importe y mayor o igual que cero (CFDI33151)


## ConceptoImpuestos

Estas validaciones son exclusivas del nodo de impuestos del concepto:

- `CONCEPIMPC01`: El nodo de impuestos de un concepto debe incluir traslados y/o retenciones (CFDI33152)
- `CONCEPIMPC02`: Los traslados de los impuestos de un concepto deben tener una base y ser mayor a cero (CFDI33154)
- `CONCEPIMPC03`: No se debe registrar la tasa o cuota ni el importe cuando el tipo de factor de traslado es exento (CFDI33157)
- `CONCEPIMPC04`: Se debe registrar la tasa o cuota y el importe cuando el tipo de factor de traslado es tasa o cuota (CFDI33158)
- `CONCEPIMPC05`: Las retenciones de los impuestos de un concepto deben tener una base y ser mayor a cero (CFDI33154)
- `CONCEPIMPC06`: Las retenciones de los impuestos de un concepto deben tener un tipo de factor diferente de exento (CFDI33166)


## EmisorRegimenFiscal

- `REGFIS01`: El régimen fiscal contenga un valor apropiado según el tipo de RFC emisor (CFDI33130 y CFDI33131)


## FechaComprobante

Valida que la fecha del comprobante:

- FECHA01: La fecha del comprobante cumple con el formato
- FECHA02: La fecha existe en el comprobante y es mayor que 2017-07-01 y menor que el futuro
    - La fecha en el futuro se puede configurar a un valor determinado
    - La fecha en el futuro es por defecto el momento de validación más una tolerancia
    - La tolerancia puede ser configurada y es por defecto 300 segundos


## ReceptorResidenciaFiscal

- `RESFISC01`: Si el RFC no es `XEXX010101000`, entonces la residencia fiscal no debe existir (CFDI33134)
- `RESFISC02`: Si el RFC sí es `XEXX010101000` y existe el complemento de comercio exterior, entonces la residencia fiscal debe establecerse y no puede ser "MEX" (CFDI33135 y CFDI33136)
- `RESFISC03`: Si el RFC sí es `XEXX010101000` y se registró el número de registro de identificación fiscal, entonces la residencia fiscal debe establecerse y no puede ser "MEX" (CFDI33135 y CFDI33136)


## SelloDigitalCertificado

Valida el Sello del comprobante y el Certificado

- SELLO01: Se puede obtener el certificado del comprobante
- SELLO02: El número de certificado del comprobante igual al encontrado en el certificado
- SELLO03: El RFC del comprobante igual al encontrado en el certificado
- SELLO04: El nombre del emisor del comprobante igual al encontrado en el certificado
- SELLO05: La fecha del documento es mayor o igual a la fecha de inicio de vigencia del certificado
- SELLO06: La fecha del documento menor o igual a la fecha de fin de vigencia del certificado
- SELLO07: El sello del comprobante está en base 64
- SELLO08: El sello del comprobante coincide con el certificado y la cadena de origen generada


## SumasConceptosComprobanteImpuestos

Obtiene las sumas de los importes de los conceptos y las sumas agrupadas de los impuestos y las valida contra la información del comprobante y el nodo principal de impuestos.

- SUMAS01: La suma de los importes de conceptos es igual al subtotal del comprobante
- SUMAS02: La suma de los descuentos es igual al descuento del comprobante
- SUMAS03: El cálculo del total es igual al total del comprobante
- SUMAS04: El cálculo de impuestos trasladados es igual al total de impuestos trasladados
- SUMAS05: Todos los impuestos trasladados existen en el comprobante
- SUMAS06: Todos los valores de los impuestos trasladados coinciden con el comprobante
- SUMAS07: No existen más nodos de impuestos trasladados en el comprobante de los que se han calculado
- SUMAS08: El cálculo de impuestos retenidos es igual al total de impuestos retenidos
- SUMAS09: Todos los impuestos retenidos existen en el comprobante
- SUMAS10: Todos los valores de los impuestos retenidos coinciden con el comprobante
- SUMAS11: No existen más nodos de impuestos trasladados en el comprobante de los que se han calculado
- SUMAS12: El cálculo del descuento debe ser menor o igual al cálculo del subtotal


## TimbreFiscalDigitalSello

Posiblemente este es el **validador más importante** porque se encarga de comprobar que
el CFDI no fue modificado después de haber sido sellado.

- `TFDSELLO01`: El Sello SAT del Timbre Fiscal Digital corresponde al certificado SAT

Esto lo hace de la siguiente forma:

1. Obtiene el TimbreFiscalDigital, si no existe entonces no hay qué validar.
1. Corrobora que sea versión 1.1, si no lo es entonces no hay qué validar
1. Se asegura que cuente con SelloCFD y que coincida con el Sello del comprobante.
1. Se asegura que NoCertificadoSAT contenga un número válido.
1. Obtiene el certificado con el que fue sellado desde el sitio del SAT `https://rdc.sat.gob.mx/`.  
   Si no se pudo obtener entonces el resultado será de error.
1. Fabrica la cadena de origen del TimbreFiscalDigital.
1. Verifica que el sello corresponde con la cadena de origen usando el certificado.

Es posible que un emisor intente modificar el comprobante, simplemente debe alterar el contenido
sin modificar el TimbreFiscalDigital ni el atributo Sello del comprobante.
En ese caso este validador no marcará error, pero sí lo hará el validador `SelloDigitalCertificado`
al encontrar que el Sello del comprobante no coincide con la cadena de origen.


## TimbreFiscalDigitalVersion

- `TFDVERSION01`: Si existe el complemento timbre fiscal digital, entonces su versión debe ser 1.1
