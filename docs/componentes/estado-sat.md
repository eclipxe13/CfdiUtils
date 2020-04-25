# Consulta del estado de un CFDI en el WebService del SAT

El SAT cuenta con un webservice para consultar el estado de un CFDI.

- Servicio: <https://consultaqr.facturaelectronica.sat.gob.mx/ConsultaCFDIService.svc>
- Documentación: <ftp://ftp2.sat.gob.mx/asistencia_servicio_ftp/publicaciones/cfdi/WS_ConsultaCFDI.pdf>

Para poderlo consumir se han implementado varias clases dentro del espacio de nonbres `\CfdiUtils\ConsultaCfdiSat`.

- `WebService`: Objeto que permite consumir el servicio.
- `Config`: Objeto que permite configurar la consulta.
- `RequestParameters`: Objeto que contiene los parámetros del CFDI que se va a consultar.
- `StatusResponse`: Objeto que contiene la respuesta del servicio.


## Datos que se requieren para hacer una consulta

Ocurre que el webservice de consulta SAT toma como entrada la información que está dentro del código QR del CFDI.
Este código tiene diferentes representaciones para diferentes versiones, entre los cambios más significativos están:

- En el CFDI 3.3 se incluye una ruta <https://verificacfdi.facturaelectronica.sat.gob.mx/default.aspx>
- En el CFDI 3.3 se incluyen los últimos 8 caracteres del sello (de los cuales los últimos 2 siempre serán `==`)
- En el CFDI 3.3 el total del comprobante se expresa con diferente formato
- El orden de los componentes cambian de la versión 3.2 a la versión 3.3

Por lo anterior, al construir la cadena de consulta, dependemos de la versión del CFDI.


### Acerca del total en la expresión impresa en CFDI 3.3

El SAT extablece en el Anexo 20 que el total se debe expresar como:

- Total del comprobante máximo a 25 posiciones
- 18 posiciones para los enteros
- 1 para caracter `"."`
- 6 para los decimales
- Se deben omitir los ceros no significativos
- Precedido por el texto `"&tt="`
- Todo el conjunto va de 7 a 29 posiciones

Por lo anterior, al excluir los ceros no significativos, no considerar como
no significativo si el grupo (decimal o entero) no existe, por ejemplo:

Si el total es por 99 centavos entonces la expresión deberá ser `&tt=0.99`
Si el total es por 1 peso entonces la expresión deberá ser `&tt=1.0`
Si el total es por cero entonces la expresión deberá ser `&tt=0.0`


## Configuración de la consulta

La consulta se puede configurar enviándole un objeto `Config` al consumidor.
Las opciones disponibles son:

- `timeout`: Define en segundos el tiempo máximo de espera, por omisión es `10`.
- `verifyPeer`: Define si SSL debe verificar el certificado de conexión, por omisión es `true`.
- `serviceUrl`: Define la ubicación del WSDL, por omisión es `https://consultaqr.facturaelectronica.sat.gob.mx/ConsultaCFDIService.svc`.

La consulta usa la librería de SOAP de PHP y por el momento no es posible configurarla
con un contexto o un cliente de conexión, por lo que si estás detrás de un proxy lo mejor
que puedes hacer es poner en la lista blanca el recurso del SAT o instalar un proxy inverso
y cambiar la URL.


## Datos que entrega la consulta

El servicio entrega cuatro valores: estado de la consulta, estado del cfdi,
estado de cancelabilidad y estado de cancelación.

### CodigoEstatus (estado de consulta)

Este estado está relacionado a la solicitud de información al SAT. No al CFDI.

- `S - Comprobante obtenido satisfactoriamente`
- `N - 601: La expresión impresa proporcionada no es válida`
- `N - 602: Comprobante no encontrado` de este no he podido encontrar un input que me lo devuelva

### Estado (estado del cfdi)

Este estado se debe entender como que el SAT reconoce el CFDI y su estado general.

- `Vigente`: El comprobante está vigente al momento de la consulta
- `Cancelado`: El comprobante está cancelado al momento de la consulta
- `No Encontrado`: El comprobante no se encuentra en la base de datos del SAT


### EsCancelable (estado de cancelabilidad)

Se refiere a que si en el momento de la consulta el CFDI se puede cancelar.

- `No cancelable`: No se puede cancelar, tal vez ya hay documentos relacionados.
- `Cancelable sin aceptación`: Se puede cancelar de inmediato.
- `Cancelable con aceptación`: Se puede cancelar pero se va a tener que esperar respuesta.

### EstatusCancelacion (estado de cancelación)

Se refiere al estado de la cancelación solicitada previamente.

- `(ninguno)`: El estado vacío es que no tiene estado de cancelación, porque no fue solicitada.
- `Cancelado sin aceptación`: Se canceló y no fue necesaria la aceptación.
- `En proceso`: En espera de que el receptor la autorice.
- `Plazo vencido`: Cancelado por vencimiento de plazo en que el receptor podía denegarla.
- `Cancelado con aceptación`: Cancelado con el consentimiento del receptor.
- `Solicitud rechazada`: No se realizó la cancelación por rechazo.

## Estados mutuamente excluyentes

CodigoEstatus | Estado        | EsCancelable              | EstatusCancelacion       | Explicación
------------- | ------------- | ------------------------- | ------------------------ | -----------------------------------------------------
N - ...       | *             | *                         | *                        | El SAT no sabe del CFDI con los datos ofrecidos
S - ...       | Cancelado     | *                         | Plazo vencido            | Cancelado por plazo vencido
S - ...       | Cancelado     | *                         | Cancelado con aceptación | Cancelado con aceptación del receptor
S - ...       | Cancelado     | *                         | Cancelado sin aceptación | No fue requerido preguntarle al receptor y se canceló
S - ...       | Vigente       | No cancelable             | *                        | No se puede cancelar
S - ...       | Vigente       | Cancelable sin aceptación | *                        | Se puede cancelar pero no se ha realizado solicitud
S - ...       | Vigente       | Cancelable con aceptación | (ninguno)                | Se puede cancelar pero no se ha realizado solicitud
S - ...       | Vigente       | Cancelable con aceptación | En proceso               | Se hizo la solicitud y se está en espera
S - ...       | Vigente       | Cancelable con aceptación | Solicitud rechazada      | Se hizo la solicitud y fue rechazada


## Ejemplo de uso a partir de un archivo

```php
<?php

use \CfdiUtils\Cfdi;
use \CfdiUtils\ConsultaCfdiSat\WebService;
use \CfdiUtils\ConsultaCfdiSat\RequestParameters;

// los datos del cfdi que se van a consultar
$cfdi = Cfdi::newFromString(file_get_contents('cfdi.xml'));
$request = RequestParameters::createFromCfdi($cfdi);

$service = new WebService();
$response = $service->request($request); // $response contiene toda la información
```

## Ejemplo de uso a partir de datos conocidos

```php
<?php

use \CfdiUtils\ConsultaCfdiSat\WebService;
use \CfdiUtils\ConsultaCfdiSat\RequestParameters;

// los datos del cfdi que se van a consultar
$request = new RequestParameters(
    '3.3', // version del cfdi
    'AAA010101AAA', // rfc emisor
    'COSC8001137NA', // rfc receptor
    '1,234.5678', // total (puede contener comas de millares)
    'CEE4BE01-ADFA-4DEB-8421-ADD60F0BEDAC', // UUID
    '... abcfe/1234==' // sello
);

$service = new WebService();
$response = $service->request($request);

// obtener las respuestas
$response->getCode(); // S - ...
$response->getCfdi(); // Vigente
$response->getCancellable(); // Cancelable con aceptación
$response->getCancellationStatus(); // En proceso
```

## Problema con el webservice del SAT

El SAT a partir de octubre 2018 dejó de publicar el archivo de contrato WSL del servicio web ubicado en
<https://consultaqr.facturaelectronica.sat.gob.mx/ConsultaCFDIService.svc>, sin embargo el servicio
sigue funcionando.

Hasta antes de la versión 2.10 se necesitaba un archivo WSDL,
a partir de 2.10 ya no se necesita y la llamada SOAP se hace correctamente.


## Posibles futuros cambios

Usar alguna librería como <https://github.com/phpro/soap-client> o <https://github.com/meng-tian/async-soap-guzzle>
en lugar de la extensión SOAP de PHP.

Esto podría llevar a mejores opciones de configuración como establecer un proxy o generar consultas asíncronas.

Crear un objeto que permita, a partir de un contenido XML, generar objeto `RequestParameters` apropiado.
