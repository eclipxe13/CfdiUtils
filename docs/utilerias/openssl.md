# Utilería OpenSSL

Esta utilería pretende ayudarte en las acciones comunes relacionadas con el trabajo de
certificados y llaves privadas para poder crear firmas.

Nota: Esta utilería no es una implementación de [`OpenSSL`](https://www.openssl.org/)
ni sustituye sus funciones principales.

## Tipos de archivos y formatos

### Archivos de certificado CER

Los archivos de certificado provistos por el SAT se encuentran en formato X509 DER.
PHP no puede trabajar con estos archivos en la forma original pues requiere el formato PEM.

Afortunadamente, cambiar a formato PEM solo requiere codificar en base 64 y poner unas cabeceras.

No incluya el certificado en formato PEM en su CFDI. Siempre hágalo con el formato X509 DER.

### Archivos de llave privada KEY

Los archivos de llave privada provistos por el SAT se encuentran en formato PKCS#8 DER.
PHP no puede trabajar con estos archivos en la forma original pues requiere el formato PEM.

Convertir archivos PKCS#8 DER a PEM no es una actividad que pueda hacer PHP.
Por esta razón estamos obligados a utilizar el comando `openssl`.

El siguiente comando permite cambiar del formato PKCS#8 DER con contraseña "12345678a" a
formato PEM sin contraseña (advertencia: **nunca almacene su llave privada sin contraseña**).

```shell
openssl pkcs8 -inform DER -passin pass:12345678a -in file.key -out file.passwordless.key
```

La llave privada en formato PEM ya es algo con lo que PHP puede trabajar.

### Archivos PEM

Los archivos PEM en realidad pueden contener múltiples contenidos.

Es decir, un archivo PEM puede contener el certificado, la llave publica e incluso la llave privada.

Aunque su uso más frecuente es que un archivo PEM contenga solamente un contenido y no múltiples,
no se debe considerar como un hecho. Por ello la librería ofrece métodos de extracción en lugar de métodos de equidad.

## Métodos relacionados con certificados

### `extractCertificate($contents): string`

Obtiene dentro de un contenido únicamente la parte del certificado.

Se espera que `$contents` se encuentre en formato PEM.

El valor devuelto será la primera ocurrencia de tipo `CERTIFICATE` o una cadena de caracteres vacía.

### `convertCertificateToPEM($contents): string`

Convierte de X509 DER a PEM.

Se espera que `$contents` se encuentre en formato DER.

No se verifica el contenido que está enviando, por lo que si le envía un certificado que ya está en formato PEM
se realizará una doble conversión resultando en un contenido inválido.

## Métodos relacionados con llaves privadas

### `extractPrivateKey($contents): string`

Obtiene dentro de un contenido únicamente la parte de la llave privada.

Se espera que `$contents` se encuentre en formato PEM.

El valor devuelto será la primera ocurrencia de tipo `PRIVATE KEY`, `RSA PRIVATE KEY` o `ENCRYPTED PRIVATE KEY`,
o una cadena de caracteres vacía.

### `convertPrivateKeyContentsDERToPEM($contents, $passPhrase): string`

Devuelve la llave privada convertida a PEM **sin contraseña**.

Se espera que `$contents` se encuentre en formato PEM.

Usa un archivo temporal para almacenar `$contents` y luego se llamará a `convertPrivateKeyFileDERToPEM`;
el archivo temporal siempre es eliminado.

### `convertPrivateKeyFileDERToPEM($privateKeyPath, $passPhrase): string`

Devuelve una llave privada convertida a PEM **sin contraseña**.

El método es un alias de `convertPrivateKeyFileDERToFilePEM` que simplemente usa
un archivo temporal para almacenar la llave convertida, este archivo será eliminado de forma inmediata.

### `convertPrivateKeyFileDERToFilePEM($privateKeyDerPath, $passPhrase, $privateKeyPemPath): string`

Devuelve una llave privada convertida a PEM **sin contraseña**.

Se espera que el contenido del archivo en `$privateKeyDerPath` se encuentre en formato DER.

Se espera que el contenido del archivo en `$privateKeyPemPath` sea diferente a `$privateKeyDerPath`.

Como esta operación no es posible hacerla con PHP, se utiliza la ejecución del comando `openssl`.

Tome en cuenta estas consideraciones de la ejecución que se hace sobre la conversión del archivo:

- La contraseña no se expone en la línea de comandos, se manda como variable de entorno.
- No se crea un archivo para la llave convertida.
- Si `openssl` escribió en `STDERR` estos valores son capturados y no expuestos.
- Se considera que la conversión falló si el comando devolvió un código de salida diferente de cero.
- La conversión no es predecible, la ejecución con los mismos parámetros devuelve un resultado diferente.

Nota: Lo mejor sería nunca escribir el archivo de salida, sin embargo, algunas versiones antiguas de openssl
no permiten enviar la salida a STDOUT, por esto se necesita guardar a un archivo.

### `protectPrivateKeyPEM($contents, $inPassPhrase, $outPassPhrase): string`

Abre la llave privada en formato PEM y crea una nueva en formato PEM pero con contraseña diferente.
Si no son diferentes manda un error.

El contenido `$contents` debe ser una llave privada en formato PEM.

La contraseña `$inPassPhrase` es la contraseña de apertura de la llave privada.
Puede ser una cadena de caracteres vacía.

La contraseña `$outPassPhrase` es la contraseña de la nueva llave privada.
Puede ser una cadena de caracteres vacía.

Este método utiliza las funciones de PHP y su resultado es una llave `ENCRYPTED PRIVATE KEY`.


## Ejecución del comando `openssl`

Cuando se construye el objeto `OpenSSL` se puede pasar la ubicación del archivo `openssl`.

Si lo hace este es el parámetro que se utilizará para armar el comando a ejecutar.

Si este parámetro está vació (o se omitió) entonces se intentará investigar la ruta
al comando que permita ejecutar `openssl`, el valor devuelto dependerá de su `PATH`.

Para investigar en dónde se encuentra `openssl` se usará el comando `where` en entornos Microsoft Windows
o `which` en entornos Linux, Mac y los demás.
