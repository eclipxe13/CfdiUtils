# Instalación

Para instalar `eclipxe/cfdiutils` es necesario usar el administrador de paquetes
[`composer`](https://getcomposer.org).

```shell
composer require eclipxe/cfdiutils
```

Las ventajas de usar composer son:

- Tendrás la última versión estable de la librería.
- Se validará que tengas la versión de PHP compatible.
- Se validará que tengas las dependencias de módulos requeridos.

## Qué incluye el paquete de distribución

No es lo mismo el proyecto de la librería que el paquete publicado en composer, esto es porque en el
proyecto se excluyen componentes relacionados con el desarrollo del proyecto, integración contínua, tests
y dependencias de desarrollo.

Lo que encontrarás en `vendor/eclipxe/cfdiutils/` es:

- `*.md`: Licencia y archivos generales.
- `build/`: Carpeta vacía, dentro se podría crear la carpeta `resources` donde por defecto se guardarán
  archivos `xsd`, `xslt` y `cer`. Esta ubicación se puede cambiar configurando el objeto
  [`XmlResolver`](../componentes/xmlresolver.md).
- `docs/`: Documentación del proyecto.
- `src/`: Código de ejecución de la librería.

## Instalación sin composer

Si tu proyecto no utiliza composer, te puede convenir usar este truco:

```shell
cd mi_proyecto
mkdir cfdiutils
cd cfdiutils
composer require eclipxe/cfdiutils
```

Dentro del script de PHP donde deseas incluir php incluye el archivo autoload generado:

```php
require __DIR__ . '/cfdiutils/vendor/autoload.php';
```
