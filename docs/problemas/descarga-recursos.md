# Descarga de recursos XSD y XSLT

Durante el proceso de descarga de los recursos, puede pasar que los orígenes de los archivos no estén disponibles (y pasa muy seguido).

Si el SAT presenta fallas al entregar los archivos XSD y XSLT, CfdiUtils va a fallar.

Para evitar este problema en PHPCfdi hicimos un proyecto que proporciona copias recientes de los archivos XSD y XSLT que se pueden descargar desde [phpcfdi/resources-sat-xml](https://github.com/phpcfdi/resources-sat-xml) y poner en el repositorio local definido para el componente [XmlResolver](https://cfdiutils.readthedocs.io/es/latest/componentes/xmlresolver/).

## Uso del recurso

Si deseas obtener los archivos directamente de este repositorio puedes ejecutar:

```bash
# descargar los recursos actualizados de github como resources-sat-xml.zip
wget -O resources-sat-xml.zip https://github.com/phpcfdi/resources-sat-xml/archive/master.zip
# descomprimir el contenido de la carpeta "resources" en la carpeta /tmp/sat
unzip resources-sat-xml.zip 'resources-sat-xml-master/resources/*' -d /tmp/sat
# eliminar resources-sat-xml.zip
rm resources-sat-xml.zip
```

Definir el lugar por defecto del repositorio de recursos de `XmlResolver`:

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

Mi recomendación es que, si vas a modificar o desactivar el repositorio local, tengas una fábrica de objetos (factory pattern) o bien una función que siempre te devuelva el objeto configurado tal y como lo necesitas.
