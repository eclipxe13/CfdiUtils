# Certificado

La clase `\CfdiUtils\Certificado\Certificado` obtiene la información de un archivo de tipo certificado.

El archivo puede ser un archivo en formato PEM o en formato CER.
En este último caso es convertido a PEM y luego interpretado.

Una vez cargado el certificado permite obtener los siguientes datos:

- RFC
- Nombre
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


## Relación con `\CfdiUtils\Certificado\NodeCertificado`

La clase `\CfdiUtils\Certificado\Certificado` funciona con un archivo previamente almacenado.
Para extraer un certificado de un CFDI se ofrece la clase `\CfdiUtils\Certificado\NodeCertificado`.


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
