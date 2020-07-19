# Descarga de recursos XSD y XSLT

Durante el proceso de descarga de los recursos XML, puede pasar que los orígenes de los archivos no estén disponibles.
Los archivos XSD son necesarios para validar que el CFDI y sus complementos sean correctos, los archivos XSLT son
necesarios para generar las cadenas de origen siguiendo las reglas del SAT.

Si el SAT presenta fallas al entregar los archivos XSD y XSLT la librería `CfdiUtils` va a fallar.

Para mitigar este problema, en PhpCfdi existe un proyecto que genera copias recientes de los archivos XSD y XSLT
que se pueden descargar desde [`phpcfdi/resources-sat-xml`](https://github.com/phpcfdi/resources-sat-xml) y poner en el
repositorio local definido para el componente [`XmlResolver`](../componentes/xmlresolver.md).

Ver [PhpCfdi/Recursos SAT XML](https://www.phpcfdi.com/recursos/sat-xml/) para más información de los recursos XML.

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

## Configuración de `CfdiUtils`

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
