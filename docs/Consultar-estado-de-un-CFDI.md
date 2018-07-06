El SAT cuenta con un webservice para consultar el estado de un CFDI.

Servicio: https://consultaqr.facturaelectronica.sat.gob.mx/ConsultaCFDIService.svc?singleWsdl
Documentación: ftp://ftp2.sat.gob.mx/asistencia_servicio_ftp/publicaciones/cfdi/WS_ConsultaCFDI.pdf

Para poderlo consumir se han implementado varias clases dentro del espacio de nonbres `\CfdiUtils\ConsultaCfdiSat`.
- `WebService`: Objeto que permite consumir el servicio.
- `Config`: Objeto que permite configurar la consulta.
- `RequestParameters`: Objeto que contiene los parámetros del CFDI que se va a consultar.
- `StatusResponse`: Objeto que contiene la respuesta del servicio.


### Datos que se requieren para hacer una consulta

Ocurre que el webservice de consulta SAT toma como entrada la información que está dentro del código QR del CFDI.
Este código tiene diferentes representaciones para diferentes versiones, entre los cambios más significativos están:

- En el CFDI 3.3 se incluye una ruta https://verificacfdi.facturaelectronica.sat.gob.mx/default.aspx
- En el CFDI 3.3 se incluyen los últimos 8 caracteres del sello (de los cuales los últimos 2 siempre serán `==`)
- En el CFDI 3.3 el total del comprobante se expresa con diferente formato
- El orden de los componentes cambian de la versión 3.2 a la versión 3.3

Por lo anterior, al construir la cadena de consulta, dependemos de la versión del CFDI.


#### Acerca del total en la expresión impresa en CFDI 3.3

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


### Configuración de la consulta

La consulta se puede configurar enviándole un objeto `Config` al consumidor.
Las opciones disponibles son:

- `timeout`: Define en segundos el tiempo máximo de espera, por omisión es 10.
- `verifyPeer`: Define si SSL debe verificar el certificado de conexión.
- `wsUrl`: Define la ubicación del WSDL.

La consulta usa la librería de SOAP de PHP y por el momento no es posible configurarla
con un contexto o un cliente de conexión, por lo que si estás detrás de un proxy lo mejor
que puedes hacer es poner en la lista blanca el recurso del SAT o instalar un proxy inverso
y cambiar la URL.


### Datos que entrega la consulta

El servicio entrega dos valores: estado de la consulta y estado del cfdi

El estado de la consulta tiene tres posibles respuestas:
- `S - Comprobante obtenido satisfactoriamente`
- `N - 601: La expresión impresa proporcionada no es válida`
- `N - 602: Comprobante no encontrado` de este no he podido encontrar un input que me lo devuelva

El estado del cfdi tiene tres posibles respuestas:
- `Vigente`
- `No Encontrado`
- `Cancelado`

Dado lo anterior, los estados normales que podría entregar el servicio son:

| Consulta | CFDI          | Explicación                                                     |
| -------- | ------------- | --------------------------------------------------------------- |
| S        | Vigente       | La consulta fue hecha y al momento el CFDI estaba vigente       |
| S        | Cancelado     | La consulta fue hecha y al momento el CFDI estaba cancelado     |
| N - 601  | No Encontrado | La consulta fue hecha pero la información impresa es incorrecta |
| N - 602  | No Encontrado | La consulta fue hecha pero el CFDI no existe                    |

El problema que encontré es que alterando solo 1 dato (el total) esperaba encontrar un estado de `N - 602`
pero el estado devuelto fue `N - 601`.

### Ejemplo de uso

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

// suponiendo que la consulta fue hecha y el resultado es que el CFDI está cancelado
$response->responseWasOk(); // true
$response->isVigente(); // false
$response->isCancelled(); // true
$response->isNotFound(); // false
$response->getCode(); // S - ...
$response->getCfdi(); // Cancelado
```

### Posibles futuros cambios

Usar alguna librería como https://github.com/phpro/soap-client o https://github.com/meng-tian/async-soap-guzzle
en lugar de la extensión SOAP de PHP.
Esto podría llevar a mejores opciones de configuración como establecer un proxy o generar consultas asíncronas.

Crear un objeto que permita, a partir de un contenido XML, generar objeto `RequestParameters` apropiado.
