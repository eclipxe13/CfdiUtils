# Guía para trabajar en MS Windows

Microsoft windows no es el entorno de desarrollo primario, sin embargo, aquí unos consejos
para poder desarrollar (código o documentación) en esta plataforma.

Recuerda consultar la [Guía del desarrollador](guia-desarrollador.md)
y la [Guía del documentador](guia-documentador.md) como primeros pasos.

## Chocolatey

La forma más conveniente de preparar un entorno de desarrollo dentro de MS Windows es
utilizar [`Chocolatey`][chocolatey]. Este es un gestor de paquetes (tipo `apt` o `yum`) que permite la
instalación y actualización de software de manera ágil.

Recuerda que para instalar, desinstalar o actualizar paquetes requieres privilegios administrativos.

Estos son algunos comandos útiles para la instalación de paquetería:

```shell
choco install -y git php saxonhe
```


## git

Para evitar problemas con git y los finales de línea, es importante que configures tu entorno
de desarrollo de la siguiente forma.

```shell
git config --global core.autocrlf input
```

> Referencias:
>
> * <https://help.github.com/articles/dealing-with-line-endings/>


## Ejecución de pruebas locales

Para que puedas ejecutar los comandos que forman las pruebas del proyecto (lo "construyen") ejecuta:

```shell
:: verificar que se están compliendo las reglas de estilo
vendor\bin\phpcs -sp src tests
vendor\bin\php-cs-fixer fix --dry-run --verbose

:: hacer las correcciones de estilo de forma automática
vendor\bin\phpcbf -sp src tests
vendor\bin\php-cs-fixer fix --verbose


:: ejecutar las pruebas
vendor\bin\phpunit

:: ejecutar el analizador el analizador estático
vendor\bin\phpstan.bat --no-progress analyse --level max src tests
```

Lamentablemente, no se puede ejecutar `composer dev:build` o alguno de los comandos personalizados
definidos `composer.json` porque no funcionan correctamente.


## SaxonB

En [chocolatey] no se encuentra el código de SaxonB pero sí el de SaxonHE.

El ejecutable se instala en `C:\ProgramData\chocolatey\bin\SaxonHE\bin\Transform.exe`
y es compatible con la clase `SaxonBCliBuilder`.
Si no lo tienes instalado no habrá problema, solamente los test relacionados se marcarán como saltados.
Si no quieres que se salten puedes instalar SaxonHE y configurar una variable de entorno con lo que
los test reconocerán el lugar donde está instalado y podrá ejecutar los tests:

```shell
:: definir la variable de entorno
SET saxonb-path=C:\ProgramData\chocolatey\bin\SaxonHE\bin\Transform.exe

:: mostrar el contenido de la variable de entorno
ECHO %saxonb-path%

:: ejecutar los tests
vendor\bin\phpunit
```


## Documentación

En teoría, si tienes instalado [`nodejs`][nodejs] y [`python`][python], ya sea usando [`chocolatey`][chocolatey] o
por algún instalador, deberías de poder ejecutar las herramientas de construcción de paquetes sin mayor complicación
siguiendo los pasos generales de la [Guía del documentador](guia-documentador.md).

```shell
:: instalar las dependencias de nodejs para markdownlint
npm install
:: revisar la sintaxis de markdown
node node_modules\markdownlint-cli\markdownlint.js *.md docs

:: instalar mkdocs usando chocolatey
choco install -y mkdocs mkdocs-material
:: construyendo los documentos
mkdocs build --strict --site-dir build\docs
:: sirviendo los documentos
mkdocs serve
```


## GNU/Linux en MS Windows

Con las últimas versiones de MS Windows es posible ejecutar en una máquina virtual interna
alguna versión de GNU/Linux como Ubuntu o SUSE. Si sigues este camino, solo ten en cuenta que,
aunque estés en un sistema operativo MS Windows en realidad los comandos se ejecutan en otra
*máquina virtual* por lo que las pruebas y comandos que ejecutes será como Linux, no como MS Windows.


[chocolatey]: https://chocolatey.org/
[nodejs]: https://nodejs.org/es/
[python]: https://www.python.org/
