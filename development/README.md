# Development tools

En este espacio se encuentran algunas herramientas de desarrollo internas.

**Importante:** Estas herramientas no están diseñadas para trabajar fuera de esta librería ni se deben
incluir en el paquete distribuible.

## ElementsMarker

Esta herramienta fabrica elementos usando un archivo de especificación.

```php
php development/bin/elements-maker.php specification-file output-directory
```

Para un ejemplo del contenido del argumento `specification-file` se puede ver el archivo
`development/ElementsMaker/specifications/cartaporte20.json`.

El argumento `output-directory` es donde se generarán los archivos de tipo `Element` de acuerdo a la especificación.

El siguiente es el ejemplo de cómo se crearon los elementos de `CartaPorte20`.

```shell
rm -rf src/CfdiUtils/Elements/CartaPorte20
mkdir -p src/CfdiUtils/Elements/CartaPorte20
php development/bin/elements-maker.php development/ElementsMaker/specifications/cartaporte20.json src/CfdiUtils/Elements/CartaPorte20/
composer dev:fix-style
```
