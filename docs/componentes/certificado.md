# Certificado

La clase `\CfdiUtils\Certificado\Certificado` obtiene la información de un archivo de tipo certificado.

El archivo puede ser un archivo en formato PEM o en formato CER.
En este último caso es convertido internamente a formato PEM y luego interpretado.

Una vez cargado el certificado permite obtener los siguientes datos utilizando *getters* (como `getRfc()`):

- RFC
- Nombre amigable
- Nombre del certificado
- Número de serie
- Válido desde y hasta
- Llave pública
- Nombre del archivo cargado

Adicionalmente cuenta con los métodos:

- Permite verificar si una llave privada corresponde a este certificado:

    `belongsTo(string $pemKeyFile, string $passPhrase = ''): bool`

- Permite verificar si una firma dada corresponde a los datos, como por ejemplo,
  si el sello corresponde con  la cadena de origen.

    `verify(string $data, string $signature, int $algorithm = OPENSSL_ALGO_SHA256): bool`


## Leer un archivo de certificado

Para leer un archivo de certificado se debe crear el objeto `Certificado` pasando el nombre del archivo.

```php
<?php
$cerFile = '/certificates/00001000000406258094.cer';
$certificate = new \CfdiUtils\Certificado\Certificado($cerFile);
var_dump($certificate->getRfc()); // algo como COSC8001137NA
```


## Relación con `\CfdiUtils\Certificado\NodeCertificado`

La clase `\CfdiUtils\Certificado\Certificado` funciona con un archivo previamente almacenado.
Para extraer un certificado de un CFDI se ofrece la clase `\CfdiUtils\Certificado\NodeCertificado`.

Esta clase puede trabajar con CFDI versión 3.2 y 3.3, toma la información de `cfdi:Comprobante@Certificado`
utilizando `CfdiUtils\Nodes\NodeInterface` y provee tres métodos para trabajar con el certificado:

- `extract(): string`: obtiene el contenido del certificado acorde a la versión y decodifica desde base 64.
- `save(string $filename): void`: guarda el contenido extraído a una ruta.
- `obtain(): Certificado`: obtiene el objeto certificado del archivo extraído.

```php
<?php
$certificate = (new \CfdiUtils\Certificado\NodeCertificado(
    \CfdiUtils\Nodes\XmlNodeUtils::nodeFromXmlString(
        file_get_contents('/cfdis/FE-00012847.xml')
    )
))->obtain();
var_dump($certificate->getRfc()); // algo como COSC8001137NA
```


## Números de serie del certificado

En el número de serie requerido en los CFDI se utiliza una representación ASCII y no hexadecimal, sin embargo
en algunas ocasiones se podría necesitar el número en formato hexadecimal de dos dígitos o la representación decimal.

El objeto `Certificado` contiene internamente un objeto de tipo `SerialNumber` que del que se puede obtener **una copia**
por el método `getSerialObject(): SerialNumber` y dicho objeto puede devolver el número de serie en tres diferentes formatos:

- `SerialNumber::asAscii()`: `30001000000300023708` el mismo que se devuelve en `Certificado::getSerial()`
- `SerialNumber::getHexadecimal()`: `3330303031303030303030333030303233373038`
- `SerialNumber::getDecimal()`: `292233162870206001759766198425879490508935868472`

!!! note ""
    Se obtiene una copia del objeto y no la misma instancia porque el `SerialNumber` es mutable, a partir de la
    versión 3 el objeto será inmutable y se podrá obtener el objeto de la propia instancia.


## Acerca de los formatos de archivo

El certificado (el archivo extensión CER) puede ser leído directamente.

La llave privada (el archivo extensión KEY) debe ser convertido a tipo PEM
para ser correctamente interpretado por esta clase (en realidad por PHP).


## Comandos útiles de openssl

- Obtener información del certificado:

```shell
openssl x509 -nameopt utf8,sep_multiline,lname -inform DER -noout -dates -serial \
  -subject -fingerprint -pubkey -in CSD01_AAA010101AAA.cer
```

- Convertir la llave privada a un archivo PEM sin contraseña:

```shell
openssl pkcs8 -inform DER -in CSD01_AAA010101AAA.key -out CSD01_AAA010101AAA.key.pem
```

- Establecer la contraseña a un archivo PEM:

```shell
openssl rsa -in CSD01_AAA010101AAA.key.pem -des3 -out CSD01_AAA010101AAA_password.key.pem
```

- Convertir el certificado a formato PEM:

```shell
openssl x509 -inform DER -outform PEM -in CSD01_AAA010101AAA.cer -pubkey -out CSD01_AAA010101AAA.cer.pem
```
