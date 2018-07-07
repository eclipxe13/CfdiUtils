# Guía para documentar CfdiUtils

En esta guía conocerás lo básico para crear y modificar la documentación para la librería


## Ubicación de la documentación

La documentación se encuentra publicada en <https://cfdiutils.readthedocs.org>.

Los archivos fuente de la documentación están en la carpeta `docs/` y además se apoya
de los archivos `mkdocs.yml` y `.markdownlint.json`.

La documentación es compilada (o transformada, o como le quieras decir) utilizando
la herramienta [`mkdocs`](https://www.mkdocs.org/).

Si deseas realizar un cambio en la documentación realiza el proceso normal de cualquier cambio
en GitHub (fork, pull, push & pull-request).

No somos expertos ni en ReadTheDocs ni en mkdocs, así que si tienes experiencia cuéntanos cómo
podemos mejorar el proyecto y su integración.


## Reglas

- La documentación se debe escribir en español con excepción del archivo `CHANGELOG.md`
- Términos como XML, XSD, XSLT se escriben en mayúsculas.
- Los archivos van escritos en minúsculas y estructurados en grupo, a excepción de `TODO.md` y `CHANGELOG.md`
- Todos los nombres de funciones, clases, propiedades, métodos, etc. deben escribirse con ` (acento grave)
- Se debe cumplir con la sintaxis de markdown aceptada por `markdownlint`, excepto:
  - Se puede usar la longitud de línea que sea
  - Se admiten hasta dos `NEW_LINE` seguidos
  - Los encabezados (*headings*) pueden acabar con signo de admiración e interrogación
  - Mira el archivo `.markdownlint.json`


## Flujo de trabajo

Estas herramientas te ayudarán para realizar la documentación y no tener problemas de construcción:

- [`mkdocs`](https://www.mkdocs.org/)`: Usada para previsualizar los cambios.
- [`markdownlint`](https://github.com/DavidAnson/markdownlint): Revisión de la sintaxis.
- `git`: Control de cambios.

Descargar el proyecto

```shell
git clone https://github.com/eclipxe13/cfdiutils
```

Ver los cambios en el navegador mientras suceden, esto abre un puerto en tu equipo
que puedes consultar en el navegador, por ejemplo: `http://127.0.0.1:8000/`

```shell
mkdocs serve
```

Realiza tus cambios, te recomiendo usar alguno de los editores que tienen soporte para
`markdownlink`, puedes encontrar una lista en <https://github.com/DavidAnson/markdownlint#related>

Antes de publicar, verifica tus cambios

```shell
node_modules/markdownlint-cli/markdownlint.js *.md docs/
```


## Instalación de `markdownlint`

El proyecto cuenta con un archivo `package.json` que contiene la dependencia de `markdownlint-cli`,
por lo que si no lo tienes instalado globalmente lo único que tendrías que hacer para instalarlo en el proyecto es:

```shell
npm install
```
