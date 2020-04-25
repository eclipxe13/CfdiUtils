# Guía para desarrollar CfdiUtils

Esta es una guía rápida que pretende guiarte para que puedas desarrollar la librería.

## Código de conducta

Revisa nuesto [COC][] y nuestra página de [CONTRIBUTING][].

En resumen:

* No toleraremos la discriminación y el maltrato.
* Cuando reportes un problema procura documentar lo más posible tu caso.
* Cuando desees contribuir al código, realiza pruebas.
* Apégate al estándar de codificación.
* Usa las herramientas básicas, antes de enviar tu PR ejecuta: `composer dev:build`.

## Dependencias de desarrollo

Requieres tener instalado y disponible `git` `composer` y `php`.

Opcionalmente podrías tener instalado `saxonb-xslt`.

El proyecto es compatible con PHP 7.0.
Respeta esta compatilibilidad, no agregues características de versiones superiores.

## Primeros pasos

Descargar el proyecto

```shell
git clone https://github.com/eclipxe13/cfdiutils
```

Instalar las dependencias, opcionalmente puedes poner `--prefer-dist` para instalar
los paquetes con

```shell
composer install
```

## Pruebas

Para probar que no se están violando las reglas de estilo

```shell
vendor/bin/phpcs -sp --colors src/ tests/
vendor/bin/php-cs-fixer fix --using-cache=no --dry-run --verbose
```


El proyecto viene acompañado de archivos de pruebas de PHPUnit

```shell
vendor/bin/phpunit
```

También ejecutamos PHPStan sobre archivos de orígenes y pruebas

```shell
vendor/bin/phpstan analyse --level max src/ tests/
```


## Comandos de ayuda

Para corregir todos los problemas de estilo que encuentre

```shell
vendor/bin/php-cs-fixer fix --verbose
vendor/bin/phpcbf --colors -sp src/ tests/
```


[coc]: https://github.com/eclipxe13/CfdiUtils/blob/master/CODE_OF_CONDUCT.md
[contributing]: https://github.com/eclipxe13/CfdiUtils/blob/master/CONTRIBUTING.md
