# Guía para desarrollar CfdiUtils

Esta es una guía rápida que pretende guiarte para que puedas desarrollar la librería.

## Código de conducta

Revisa nuestro [COC][] y nuestra página de [CONTRIBUTING][].

En resumen:

* No toleraremos la discriminación y el maltrato.
* Cuando reportes un problema procura documentar lo más posible tu caso.
* Cuando desees contribuir al código, realiza pruebas.
* Apégate al estándar de codificación.
* Usa las herramientas básicas, antes de enviar tu PR ejecuta:
    * Actualizar herramientas: `phive update && composer update`.
    * Revisar la construcción del proyecto: `composer dev:build`.

## Dependencias de desarrollo

Requieres tener instalado y disponible `git` `composer`, `phive` y `php`.

Opcionalmente podrías tener instalado `saxonb-xslt`.

El proyecto es compatible con PHP 7.4.
Respeta esta compatibilidad, no agregues características de versiones superiores.

## Primeros pasos

Descargar el proyecto

```shell
git clone https://github.com/eclipxe13/cfdiutils
```

Instalar las dependencias, opcionalmente puedes poner `--prefer-dist` para instalar
los paquetes con

```shell
composer update
```

## Pruebas

Para probar que no se están violando las reglas de estilo

```shell
tools/phpcs -sp --colors
tools/php-cs-fixer fix --using-cache=no --dry-run --verbose
```


El proyecto viene acompañado de archivos de pruebas de PHPUnit

```shell
vendor/bin/phpunit
```

También ejecutamos PHPStan sobre archivos de orígenes y pruebas

```shell
tools/phpstan analyse
```


## Comandos de ayuda

Para corregir todos los problemas de estilo que encuentre

```shell
tools/php-cs-fixer fix --verbose
tools/phpcbf --colors -sp
```


[coc]: https://github.com/eclipxe13/CfdiUtils/blob/master/CODE_OF_CONDUCT.md
[contributing]: https://github.com/eclipxe13/CfdiUtils/blob/master/CONTRIBUTING.md
