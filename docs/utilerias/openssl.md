# Utilería OpenSSL

Esta utilería está hecha para ayudarte en las tareas comunes relacionadas con el trabajo de
certificados y llaves privadas.

No es una implementación de [`OpenSSL`](https://www.openssl.org/) ni sustituye sus funciones.
Para la mayoría de comandos ejecuta externamente openssl para poder funcionar.


## Archivos CER, KEY y PEM

### Archivos de certificado CER

Los archivos de certificado provistos por el SAT se encuentran en formato X509 DER.
PHP no puede trabajar con estos archivos en la forma original pues requiere el formato PEM.

Cambiar a formato PEM solo requiere codificar en base 64 y cierto formato.
Por lo tanto, se puede hacer utilizando solamente PHP o bien utilizando `openssl`.
Esta utilería le entrega ambas formas de hacerlo.

!!! note
    No incluya el certificado en formato PEM en su CFDI porque contienen finales de línea.
    Siempre hágalo con el formato X509 DER y siga las instrucciones del Anexo 20.

### Archivos de llave privada KEY

Los archivos de llave privada provistos por el SAT se encuentran en formato PKCS#8 DER.
PHP no puede trabajar con estos archivos en la forma original pues requiere el formato PEM.

Convertir archivos PKCS#8 DER a PEM no es una actividad que pueda hacer PHP.
Por esta razón estamos obligados a utilizar el comando externo `openssl`.

La llave privada en formato PEM ya es algo con lo que PHP sí puede trabajar.

### Archivos contenedores PEM

PEM es un formato de contenidos seccionados y es utilizado para múltiples propósitos.

Este es un ejemplo válido del contenido en formato PEM.

```text
-----BEGIN MY INFORMATION-----
UXXDqSBjdXJpb3NvIHNvcyB2b3MhCg==
-----END MY INFORMATION-----
```

Leer [RFC 1421 - Privacy Enhancement for Internet Electronic Mail](https://tools.ietf.org/html/rfc1421)
y [Textual Encodings of PKIX, PKCS, and CMS Structures](https://tools.ietf.org/html/rfc7468#section-2) para más información.

Para el caso de certificados, llaves públicas y llaves privadas:

- El contenido de una sección debería ser un texto en [base 64](https://en.wikipedia.org/wiki/Base64#Base64_table).

- También puede incluir `LF` o `CRLF` como finales de línea de el contenido a 64 caracteres.

- Para `RSA PRIVATE KEY` también incluye texto que **no es base 64**:

```text
Proc-Type: 4,ENCRYPTED
DEK-Info: DES-EDE3-CBC,0EF5A5CAA276419B

WqPzvGCc ...
```

- Un archivo o contenido PEM puede incluir (entre otras cosas):
    - el certificado, en la sección `CERTIFICATE`,
    - la llave publica, en la sección `PUBLIC KEY`,
    - la llave privada, en la sección `PRIVATE KEY`, `RSA PRIVATE KEY` o `ENCRYPTED PRIVATE KEY`.

Aunque su uso más frecuente es que un archivo PEM contenga solamente un contenido y no múltiples,
no se debe considerar como un hecho. Por ello la librería ofrece métodos de extracción de los ratos relevantes.

#### Finales de línea en archivos PEM

En sistemas POSIX el final de línea es `LF` `\n`, en MS Windows el final de línea es `CRLF` `\r\n`.

El comando `openssl` regresa finales de línea acorde al sistema operativo en el que se ejecute.
En herramienta los finales de línea son leídos y convertidos definidos por la constante de PHP `PHP_EOL`.

#### Comparaciones de contenido PEM

Nunca hay que comparar la lectura directa de un archivo contra una sección PEM extraída.
Puede tener finales de línea diferentes u otros contenidos adicionales.

Para evitar este problema se pueden usar los métodos `readPemFile` y `readPemContents`
que devuelven un objeto `PemContainer` con finales de línea normalizados y sin final de línea al final del contenido.

## Generalidades

De forma general, tenga en cuenta estas consideraciones:

- Cuando se trabaja con un archivo de entrada se valida que exista y que su tamaño sea mayor a cero.
- Cuando se trabaja con un archivo de salida se valida que no exista pero que sí exista su directorio.
  En caso de que exista su tamaño debe ser cero.
- Ninguna ejecución con el comando `openssl` puede contener caracteres de control excepto `CR` y `LF`.
- Las contraseñas pasadas al comando `openssl` se pasan por el entorno y no por la línea de comandos.
  Aunque no garantiza su privacidad es el mejor método de ocultamiento del que se dispone.

Existen algunos métodos que tienen salida directa en el retorno, en realidad,
estos archivos trabajan con archivos temporales que son eliminados (aun en una excepción)
y hacen la llamada a la función original. Los puede reconocer porque terminan en `Out` o `InOut`.

Cuando es `Out` se crea un archivo temporal donde se almacenará la respuesta y se manda llamar el método base.
El contenido del archivo temporal es el retorno del método. El archivo temporal es eliminado.

Cuando es `InOut` se crea un archivo temporal donde se almacena el contenido enviado y se manda llamar el método base.
Después se manda llamar al método `Out` y se retorna su respuesta. El archivo temporal es eliminado.

- `derCerConvert`: `derCerConvertOut` y `derCerConvertInOut`.
- `derKeyConvert`: `derKeyConvertOut`.
- `derKeyProtect`: `derKeyProtectOut`.
- `pemKeyProtect`: `pemKeyProtectOut` y `pemKeyProtectInOut`.
- `pemKeyUnprotect`: `pemKeyUnprotectOut` y `pemKeyUnprotectInOut`.

Nota: Mientras que en sistemas POSIX (Linux y Mac) se podría usar tuberías de entrada y salida para mandar llamar
a `openssl`, en sistemas Windows esto no funciona como debería.
Además de que `openssl` en versiones anteriores no contaba con este soporte.


## Métodos de archivos PEM

```php
<?php

$openssl = new \CfdiUtils\OpenSSL\OpenSSL();

// Leer un certificado desde un archivo con finales de línea convertidas a PHP_EOL
$cerFile = 'AAAA010101AAA.cer.pem';
$certificate = $openssl->readPemFile($cerFile)->certificate();

// Leer una llave privada desde una cadena de caracteres con finales de línea convertidas a PHP_EOL
$keyFile = 'AAAA010101AAA.key.pem';
$keyContents = file_get_contents($keyFile);
$privateKey = $openssl->readPemContents($keyContents)->privateKey();

// Leer un archivo PEM y averiguar sus contenidos:
$pemFile = 'AAAA010101AAA.pem';
$pem = $openssl->readPemFile($pemFile);

// usando hasCertificate
if ($pem->hasCertificate()) {
    echo 'CERTIFICATE:', PHP_EOL, $pem->certificate(), PHP_EOL;
}

// obteniendo directamente, si no hay entonces devuelve una cadena de caracteres vacía
echo 'PUBLIC KEY:', PHP_EOL, $pem->publicKey(), PHP_EOL;

```

### `readPemFile(string $pemFile): PemContainer`

Obtiene un objeto `PemContainer` a partir de los contenidos de un archivo.

### `readPemContents(string $contents): PemContainer`

Obtiene un objeto `PemContainer` a partir del parámetro `$contents`.

## `PemContainer` y `PemExtractor`

Los métodos `readPemFile` y `readPemContents` internamente utilizan un objeto `PemExtractor`
para generar el objeto `PemContainer`

`PemExtractor` tratará de reconocer si se cumple con el contenido de la sección considerando
los caracteres de Base 64 `/[A-Za-z0-9+\/=]/` con los finales de línea `/\r?\n/`
e información específica de `RSA PRIVATE KEY`.

El extractor **no verifica** si el contenido en Base 64 está correctamente codificado.

Por ejemplo, el siguiente contenido generará la extración de `"FOO-BAR"` como el contenido de `certificate()`,
una cadena vacía para `publicKey()` por tener caracteres indebidos como espacios en blanco
y una cadena vacía para `privateKey()` porque no se encuentra.

```text
Contenidos antes de primer sección son descartados
-----BEGIN CERTIFICATE-----
FOO-BAR
-----END CERTIFICATE-----
-----BEGIN PUBLIC KEY-----
Esta sección contiene una "o" acentuada y comillas dobles por lo que simplemente es descartada  
-----END PUBLIC KEY-----
Contenidos entre secciones son descartados
-----BEGIN SOMETHING-----
SOMETHING no es requerido por lo que es descartado
-----END SOMETHING-----
Contenidos después de última sección son descartados
```

`PemContainer` se compone de los métodos:

- Para certificados: `hasCertificate(): bool` y `certificate(): string`.
- Para llave pública: `hasPublicKey(): bool` y `publicKey(): string`.
- Para llave private: `hasPrivateKey(): bool` y `privateKey(): string`.

`PemContainer` no determina si los contenidos son correctos o si corresponden entre sí.
Lo único que hace es almacenar la información encontrada en un solo objeto.

## Métodos de certificados

```php
<?php
$cerFile = 'AAAA010101AAA.cer';
$cerContents = file_get_contents('AAAA010101AAA.cer');
$openssl = new \CfdiUtils\OpenSSL\OpenSSL();

// guardar el certificado en PEM a partir del archivo DER usando openssl
$openssl->derCerConvert($cerFile, __DIR__ . '/certificate.pem');

// obtener el certificado en PEM a partir del archivo DER usando openssl
$pemCertificate = $openssl->derCerConvertOut($cerFile);

// obtener el certificado en PEM a partir de sus contenidos usando PHP
$pemCertificate = $openssl->derCerConvertPhp($cerContents);

// obtener el certificado en PEM a partir de sus contenidos usando openssl
$pemCertificate = $openssl->derCerConvertInOut($cerContents);
```

### `derCerConvertPhp(string $derContent): string`

Convierte de X509 DER a PEM.

Se espera que `$derContent` se encuentre en formato DER.

Este método no llama a `openssl`.

### `derCerConvert(string $derInFile, string $pemOutFile)`

Convierte de X509 DER a PEM.

Se espera que `$derInFile` sea la ruta a un archivo.

Este método llama a `openssl`.

## Métodos de llaves privadas

```php
<?php
$cerFile = 'AAAA010101AAA.cer.pem';
$keyDerFile = 'AAAA010101AAA.key';
$keyPemFile = $keyDerFile . '.pem';
$keyPemFileUnprotected = $keyDerFile . '.unprotected.pem';
$keyDerPass = '12345678a';
$keyPemPass = 'This is my not so strong password';

$openssl = new \CfdiUtils\OpenSSL\OpenSSL();

// convertir la llave original DER a formato PEM sin contraseña, guardar en $keyPemFileUnprotected
$openssl->derKeyConvert($keyDerFile, $keyDerPass, $keyPemFileUnprotected);

// poner contraseña a $keyPemFileUnprotected, guardar en $keyPemFile
$openssl->pemKeyProtect($keyPemFileUnprotected, '', $keyPemFile, $keyPemPass);

// convertir la llave original DER a formato PEM con nueva contraseña, guardar en $keyPemFile
// lo mismo que los dos pasos anteriores pero en una llamada
$openssl->derKeyProtect($keyDerFile, $keyDerPass, $keyPemFile, $keyPemPass);

// supongamos que requerimos un certificado PEM con contraseña "abc/123-xyz"
// y tenemos la llave en $keyPemFile con la contraseña $keyPemPass
// por ejemplo, para finkok
$keyForFinkOk = $openssl->pemKeyProtectOut($keyPemFile, $keyPemPass, 'abc/123-xyz');
```

### `derKeyConvert(string $derInFile, string $inPassPhrase, string $pemOutFile)`

Convierte de PKCS#8 DER a PEM **sin contraseña**.

Se espera que `$derInFile` y `$pemOutFile` sean rutas a un archivo.

Este método llama a `openssl`.

### `derKeyProtect(string $derInFile, string $inPassPhrase, string $pemOutFile, string $outPassPhrase)`

Convierte de PKCS#8 DER a PEM **con contraseña**.

Se espera que `$derInFile` y `$pemOutFile` sean rutas a un archivo.

Este método en realidad manda llamar a `derKeyConvert` y `pemKeyProtect` (en caso de ser necesario).

### `pemKeyProtect(string $pemInFile, string $inPassPhrase, string $pemOutFile, string $outPassPhrase)`

Establece una nueva contraseña a un archivo PEM.

Se espera que `$pemInFile` y `$pemOutFile` sean rutas a un archivo.

Este método cede el control a `pemKeyUnprotect` si la contraseña para el archivo de salida es una cadena de caracteres vacía.

Este método llama a `openssl` si la contraseña para el archivo de salida no es una cadena de caracteres vacía.

Esta función no es determinista. El resultado devuelto por `openssl` será siempre diferente aun cuando provenga de la misma llave y se establezca la misma contraseña.

### `pemKeyUnprotect(string $pemInFile, string $inPassPhrase, string $pemOutFile)`

Elimina una nueva contraseña a un archivo PEM.

Se espera que `$pemInFile` y `$pemOutFile` sean rutas a un archivo.

Este método cede el control a `pemKeyUnprotect` si la contraseña para el archivo de salida es una cadena de caracteres vacía.

Este método llama a `openssl` si la contraseña para el archivo de salida no es una cadena de caracteres vacía.

## Ejecución del comando `openssl`

Cuando se construye el objeto `OpenSSL` se puede pasar la ubicación del archivo `openssl`.

Si lo hace este es el parámetro que se utilizará para armar el comando a ejecutar.

Si este parámetro está vació (o se omitió) entonces se usará simplemente `openssl`.

## Excepciones

### `OpenSSLException`

Es la excepción genérica que se origina en alguno de los métodos de OpenSSL.

### `OpenSSLCallerException`

Es la excepción específica de una llamada al comando `openssl` que ha fallado.

Expone el método `getCallResponse(): CallResponse` con el que se devuelve un objeto que
contiene el comando ejecutado, la salida de `STDOUT`, la salida `STDERR` y el código de salida.
