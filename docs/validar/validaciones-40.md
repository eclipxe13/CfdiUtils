# Validaciones para CFDI 4.0

Las validaciones se deben realizar tanto para CFDI creados como para CFDI recibidos.


## XmlDefinition

Valida que el archivo XML tiene las especificaciones necesarias para 4.0.

- XML01: El XML implementa el namespace %s con el prefijo cfdi.
- XML02: El nodo principal se llama cfdi:Comprobante.
- XML03: La versión es 4.0


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
