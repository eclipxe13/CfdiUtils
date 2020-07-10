# Almacenamiento local de recursos del SAT

El SAT publica diferentes recursos para diferentes tareas,
los recursos más usuales son:

- Archivos XSD: Son archivos de esquemas XML y sirven para comprobar que
  un archivo es correcto con respecto a ciertas reglas.
- Archivos XSLT: Son archivos de transformaciones XML y sirven para transformar
  el contenido de un archivo XML en otro contenido.
  El SAT los utiliza para generar cadenas de origen.
- Archivos CER: Son archivos de certificado comúnmente utilizados para verificar
  que una firma es válida con respecto a un emisor.
  La firma es lo que el sat llama sello y el emisor se distingue por un certificado.

Estos recursos están disponibles en internet, pero son grandes  y tienen cambios esporádicos. Por ejemplo, el archivo de catálogos del SAT mide 6.3 MB.
Por ello es conveniente tener una copia local de los recursos.

El problema viene cuando esos recursos no se pueden simplemente descargar y almacenar.
Muchos recursos dependen de otros y sus rutas de dependencia no son relativas,
por esto es necesario descargar y manipular los recursos para cambiar las dependencias.

Por suerte esta librería viene con una utilería para mantener copias locales de los recursos según nos convenga.

Internamente, cuando se solicita un recurso, la librería busca la mejor opción según esté configurada:

- Si no se ha configurado un repositorio local entonces devuelve la ruta del recurso remoto.
- Si se ha configurado un repositorio local entonces busca si existe.
    - Si existe devuelve la ruta del recurso local.
    - Si no existe lo descarga y devuelve la ruta del recurso local.


## Repositorio local por defecto

Por defecto, la librería utilizará como repositorio local el lugar donde esté instalada y le agregará:
`<project-folder>/build/resources/`. Por lo que, generalmente, si estás usando `composer` entonces el lugar donde están
los recursos es: `<composer-json-folder>/vendor/eclipxe/cfdiutils/build/resources/`.


## Cómo manipular el lugar por defecto del repositorio

La forma de modificarlo es creando una instancia del objeto `CfdiUtils\XmlResolver\XmlResolver`
y especificando la dirección del repositorio en `localPath`, esto se puede lograr con el constructor del objeto
o bien con el método `setLocalPath(string $localPath = null)`.
En ambos casos (constructor o método) se aplican las siguientes reglas:

- Si se pasa una cadena de caracteres vacía se desconfigura el repositorio local,
  por lo que no se almacenarán recursos localmente.
- Si se pasa `null` el valor se establece a `defaultLocalPath()`,
  es decir `<project-folder>/build/resources/`.
- Si se pasa otro valor entonces será usado, por ejemplo `/tmp/sat/`.

Después, es necesario que ese objeto se utilice en los otros objetos que estamos usando, por ejemplo:

```php
<?php
$myLocalResourcePath = '/tmp/sat';
$myResolver = new \CfdiUtils\XmlResolver\XmlResolver($myLocalResourcePath);

// ponerlo utilizando setXmlResolver
$cfdiCreator = new \CfdiUtils\CfdiCreator33();
$cfdiCreator->setXmlResolver($myResolver);

// ponerlo utilizando el constructor
$cfdiValidator = new \CfdiUtils\CfdiValidator33($myResolver);
```

Mi recomendación es que, si vas a modificar o desactivar el repositorio local, tengas
una fábrica de objetos (factory pattern) o bien una función que siempre te devuelva
el objeto configurado tal y como lo necesitas.


!!! note ""
    Durante el proceso de descarga de los recursos, puede pasar que los orígenes de los archivos no estén disponibles (y pasa muy seguido). Si el SAT presenta fallas al entregar los archivos XSD y XSLT, CfdiUtils va a fallar. Para evitar este problema en PHPCfdi hicimos un proyecto que proporciona copias recientes de los archivos XSD y XSLT que se pueden descargar desde [phpcfdi/resources-sat-xml](https://github.com/phpcfdi/resources-sat-xml) y poner en el repositorio local definido.


## Cómo invalidar el caché del almacenamiento local

**En corto: Simplemente bórralos.**

No se trata de un caché, porque no hay fechas de caducidad de los recursos.

Cuando descargas algún recurso este podría descargar hijos y a su vez estos podrían descargar nuevos hijos.
De igual forma, no solo se descargan recursos del SAT, también podrían descargarse recursos de terceros.
Por eso te recomiendo que, si hubo algún cambio en los archivos XSD del SAT elimines entonces cualquier archivo
de tipo `*.xsd` dentro de la carpeta `<repositorio>/www.sat.gob.mx`.


## Configurando el objeto que se encarga de la descarga de archivos

Imagina ahora que tu proyecto corre en un servidor dentro de una red corporativa que tiene
salida a internet usando un proxy con usuario y contraseña.
La librería por defecto no puede obtener los recursos que necesita.
Sin embargo, para ello existe la interface `\XmlResourceRetriever\Downloader\DownloaderInterface`
(esta interface no pertenece a este proyecto, pertenece a `XmlResourceRetriever`).

Tu puedes implementar el `DownloaderInterface` en una clase que utilice `curl` o `guzzle`
o ejecute un comando en la shell como `wget` y luego crear tu objeto `XmlResolver` con este descargador.

```php
<?php
class MyDownloader implements \XmlResourceRetriever\Downloader\DownloaderInterface
{
    public function downloadTo(string $source, string $destination)
    {
        // my logic to download...
    }
}

// crear tu propia instancia de tu descargador
$myDownloader = new MyDownloader();

// crear el resolvedor con el downloader desde el contructor
$myResolver = new \CfdiUtils\XmlResolver\XmlResolver(null, $myDownloader);

// establecer el descargador después de que se ha creado el resolvedor
$myResolver->setDownloader($myDownloader);

// establecer el descargador a un descargador simple (ver PhpDownloader)
$myResolver->setDownloader(null);
```

En el creador de CFDI, aun cuando no se especifique, por defecto se crea un resolvedor.
Puedes utilizar este resolvedor y simplemente configurarlo con otro descargador:

```php
<?php
/** @var \XmlResourceRetriever\Downloader\DownloaderInterface $myDownloader */
$creator = new \CfdiUtils\CfdiCreator33();
$creator->getXmlResolver()->setDownloader($myDownloader);
```
