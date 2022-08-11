# CfdiUtils Changelog file

## Backward compatibility breaks (not released yet), plan for version 3.0

- Remove deprecated classes:
    - `\CfdiUtils\CadenaOrigen\CadenaOrigenBuilder`
    - `\CfdiUtils\CadenaOrigen\DefaultLocations`
    - `\CfdiUtils\CadenaOrigen\CadenaOrigenLocations`
- Remove `\CfdiUtils\PemPrivateKey\PemPrivateKey::isOpened` to `\CfdiUtils\PemPrivateKey\PemPrivateKey::isOpen`
- Remove `CfdiUtils\ConsultaCfdiSat\Config::getWsdlUrl()`
- Remove `static` methods from `\CfdiUtils\CfdiVersion`, instead create an instance of the class
- Remove `static` methods from `\CfdiUtils\TimbreFiscalDigital\TfdVersion`, instead create an instance of the class
- Remove `trigger_error` on `\CfdiUtils\Elements\Cfdi33\Comprobante::getCfdiRelacionados` when called with arguments.
- Change signature of `CfdiUtils\Elements\Cfdi33\CfdiRelacionados::multiCfdiRelacionado` to receive as parameters
  `array ...$elementAttributes` instead of `array $elementAttributes`.
- Refactor `\CfdiUtils\Certificado\SerialNumber` to be immutable, this change will remove `loadHexadecimal`,
  `loadDecimal` and `loadAscii`.
- Remove `CfdiUtils\Certificado\SerialNumber::baseConvert` method. Should be private or not exists at all.
- Add a method `NodeInderface::exists` as an alias of `NodeInderface::offsetExists`. Replace usages in code.
- Remove static `CfdiUtils\PemPrivateKey\PemPrivateKey::isPEM` method.
- Add a method to execute `CfdiUtils\ConsultaCfdiSat\StatusResponse` using an expression instead of `RequestParameters`.
- Make `CfdiUtils\ConsultaCfdiSat\StatusResponse::__constructor()` third and fourth arguments non-optional.
  Now they are optional to avoid incompatibility changes.
- Remove `CfdiUtils\ConsultaCfdiSat\Config::DEFAULT_SERVICE_URL`
- Remove `CfdiUtils\ConsultaCfdiSat\Config::getWsdlLocation()`, `CfdiUtils\ConsultaCfdiSat\Config::getWsdlLocation()`
  and fix `CfdiUtils\ConsultaCfdiSat\Config::__construct()`.
- Remove file `ConsultaCFDIServiceSAT.svc.xml`.
- Change visibility of `CfdiUtils\Cleaner\Cleaner#removeIncompleteSchemaLocation()` to private.
- Remove deprecated constant `CfdiUtils\Cfdi::CFDI_NAMESPACE`.
- Remove `CfdiUtils\Validate\Cfdi33\Xml\XmlFollowSchema`.
- Remove classes `CfdiUtils\Elements\Cfdi33\Helpers\SumasConceptosWriter` and `CfdiUtils\Elements\Cfdi40\Helpers\SumasConceptosWriter`.
- Merge methods from `\CfdiUtils\Nodes\NodeHasValueInterface` into `\CfdiUtils\Nodes\NodeInterface`.
- Remove deprecated constant `CfdiUtils\Retenciones\Retenciones::RET_NAMESPACE`.

## Version 2.23.3 2022-08-11

Fix CFDI 4.0, must include `Comprobante/Impuestos/Traslados/Traslado@TipoFactor=Exento` when exists at least one
node `Comprobante/Conceptos/Concepto/Impuestos/Traslados/Traslado@TipoFactor=Exento`.
The node must contain attribute `TipoFactor=Exento` and the rounded sum of the attributes `Base`
grouped by attribute `Impuesto`.
Thanks `BrodyAG` for noticing this issue, and `@yairtestas` for your guidance to find the solution.

## Version 2.23.2 2022-06-29

Use `Symfony/Process` instead of `ShellExec`.
Remove internal classes `ShellExec` and `ShellExecResult`.
Rename internal class `ShellExecTemplate` to `CommandTemplate`.

## Version 2.23.1 2022-06-20

Fix hardcoded *Regímenes* catalog to fix CFDI33 validation `EmisorRegimenFiscal`.

Thanks `@celli33` for your contribution.

## Version 2.23.0 2022-06-15

Add `CfdiUtils\Elements\PlataformasTecnologicas10` *Elements* to work with "Complemento Servicios de Plataformas Tecnológicas".

Thanks `@gam04` for your contribution.

## Version 2.22.0 2022-05-15

Add support to read and create a RET 2.0 (*Retenciones e información de pagos 2.0*) document.

- Add helper elements on namespace `CfdiUtils\Elements\Retenciones20`.
- Add `CfdiUtils\Retenciones\RetencionVersion`.
- Add `CfdiUtils\Retenciones\RetencionesCreator20`.
- Move shared methods from `CfdiUtils\Retenciones\RetencionesCreator10` to `CfdiUtils\Retenciones\RetencionesCreatorTrait`.
- Refactor `CfdiUtils\Retenciones\Retenciones` to read versions 1.0 and 2.0.
- Improve documentation about RET 2.0.

Thanks `@gam04` for your contribution.

## Version 2.21.0 2022-04-29

- Introduce `\CfdiUtils\Nodes\NodeHasValueInterface` to work with nodes simple text content.
- The class `\CfdiUtils\Nodes\Node` implements `\CfdiUtils\Nodes\NodeHasValueInterface`.
- The XML node importers and exporters now can read and write simple text content.

## Version 2.20.2 2022-04-05

Allow installing `Genkgo/Xsl` version 1.1.0; used for PHP >= 7.4.

Test: Fix test that was overriding `retenciones/sample-before-tfd.xml` file.

Include the following unreleased changes

### 2022-03-18

Fix build since GitHub Action `sudo-bot/action-scrutinizer` is failing.
Use `scrutinizer/ocular` package instead.

Test: When creating a pago, use `addSumasConceptos` to populate `SubTotal` and `Total`.

CI: Always run `apt-get update` before `apt-get install`.


## Version 2.20.1 2022-03-08

Add PHP 8.1 minimal compatibility.

Skip tests on `GenkgoXslBuilderTest` because the library `genkgo/xsl` is not compatible with PHP 8.1.

Skip tests `WebServiceConsumingTest::testSoapClientHasSettings` because cannot access `SoapClient` private properties.

Add dependence on `symfony/process` to include 6.0. This allows to install the library on PHP 8.1.

Upgrade `eclipxe/xmlschemavalidator` from version 2.x to version 3.x (fully PHP 8.1 compatible).

Add `#[\ReturnTypeWillChange]` or fix return types on implemented classes like
`IteratorAggregate::getIterator(): Traversable`.

Add information about how tu run locally GitHub Actions using  `nektos/act` tool.


## Version 2.20.0 2022-02-22

Add `CfdiUtils\Elements\Pagos20` *Elements* to work with "Complemento para recepción de Pagos 2.0".
Thanks @EmmanuelJCS.


## Version 2.19.1 2022-02-09

Fix `EmisorRegimenFiscal` validation. Add `626 - RESICO`. Thanks `@celli33`.

The following changes apply only to development and has been applied to main branch.

- Removed duplicated assert in Comprobante #84.
- Added new regimen 626 in validator for EmisorRegimenFiscal class.


## Version 2.19.0 2022-01-17

Add CFDI 4.0 compatibility: read, validate and create:

- `CfdiUtils\Cfdi` object now can read CFDI 4.0.
- `CfdiUtils\CfdiValidator40` object was introduced to validate CFDI 4.0 with only the following validations:
    - The document follows de CFDI 4.0 specification (namespace, root element prefix and name).
    - The document follows de CFDI 4.0 schema.
    - The document has a valid `NoCertificado`, `Certificado` and `Sello`.
    - The document has a valid `TimbreFiscalDigital` and information matches with `cfdi:Comprobante@Sello`.
- `CfdiUtils\CfdiCreator40` object was introduced to create CFDI 4.0.
- The helper elements `CfdiUtils\Elements\Cfdi40` were created.
- Add *minimal* documentation to read, validate and create CFDI 4.0.

The following are development details:

- Created `\CfdiUtils\Validate\Xml\XmlFollowSchema` as a standard validator, it does not depend on the type of CFDI.
- The validator `CfdiUtils\Validate\Cfdi33\Xml\XmlFollowSchema` is now an extended class
  of `\CfdiUtils\Validate\Xml\XmlFollowSchema`. The class is also deprecated.
- Add `CfdiUtils\Certificado::getPemContentsOneLine()`.
- Add CFDI 4.0 information to:
    - `CfdiUtils\CadenaOrigen\CfdiDefaultLocations`.
    - `CfdiUtils\Certificado\NodeCertificado`.
    - `CfdiUtils\CfdiVersion`.
- Deprecate `CfdiUtils\Cfdi::CFDI_NAMESPACE`.
- Add `CfdiUtils\CfdiCreateObjectException` that contains all the failures when try to construct a `CfdiUtils\Cfdi` object.
- Extract and share logic for several objects that are CFDI 3.3 and CFDI 4.0 compatible:
    - `CfdiUtils\CfdiValidator33` to `CfdiUtils\Validate\CfdiValidatorTrait`.
    - `CfdiUtils\Validate\Cfdi33\Standard\SelloDigitalCertificado` to `CfdiUtils\Validate\Common\SelloDigitalCertificadoValidatorTrait`.
    - `CfdiUtils\Validate\Cfdi33\Standard\TimbreFiscalDigitalSello` to `CfdiUtils\Validate\Common\TimbreFiscalDigitalSelloValidatorTrait`.
    - `CfdiUtils\Validate\Cfdi33\Standard\TimbreFiscalDigitalVersion` to `CfdiUtils\Validate\Common\TimbreFiscalDigitalVersionValidatorTrait`.
    - `CfdiUtils\CfdiCreator33` to `CfdiUtils\Validate\CfdiCreatorTrait`.
    - `CfdiUtils\Elements\Cfdi33\Helpers\SumasConceptosWriter` to `CfdiUtils\SumasConceptos\SumasConceptosWriter`.
- The certificate PAC used for testing `30001000000400002495` is included.
- The class `CfdiUtilsTests\Validate\ValidateTestCase` was renamed to `CfdiUtilsTests\Validate\Validate33TestCase` and
  extracted to `CfdiUtilsTests\Validate\ValidateBaseTestCase` because it shares a lot of logic with `CfdiUtilsTests\Validate\Validate40TestCase`.
- The class `CfdiUtils\SumasConceptos\SumasConceptosWriter` can handle both CFDI 3.3 & 3.4.

This version introduces this *soft* breaking compatibility changes, your implementation should not be affected:

```text
[BC] REMOVED: These ancestors of CfdiUtils\Validate\Cfdi33\Xml\XmlFollowSchema have been removed: ["CfdiUtils\\Validate\\Cfdi33\\Abstracts\\AbstractVersion33"]
```

Other changes:

- Update license year, happy new year.
- Update PHPUnit config file.


## Version 2.18.3 2022-01-15

Fix *Carta Porte 1.0* add missing element `Notificado`. Thanks `@celli33`.


## Version 2.18.2 2021-12-17

Fix *Carta Porte 2.0* XML Namespace.


## Version 2.18.1 2021-12-17

Remove `development/` from distribution package.


## Version 2.18.0 2021-12-17

Add `CfdiUtils\Elements\CartaPorte20` *Elements* to work with "Carta Porte 2.0".

Add *Elements Maker*, a development tool to create element classes based on a specification file.

Fix `dev:coverage` composer script.


## Version 2.17.0 2021-12-10

The helper object `SumasConceptosWriter` also writes the sum of *impuestos locales* when they are present.
Thanks, `@celli33` and `@luffinando` for your help.


## Version 2.16.1 2021-12-08

Fix bug when create expression to query for the SAT status and the RFC (*emisor* or *receptor*) contains
the characters `&` or `Ñ`. The service requires that the expression is XML "encoded".
Thanks, `@ramboram` and `@TheSpectroMX` for your help.

Refactor test script `tests/estadosat.php`.

Fix typos on *"Complemento de Nómina versión 1.2, revisión B"* documentation.


## Version 2.16.0 2021-11-09

Add `CfdiUtils\Elements\CartaPorte10` *Elements* to work with "Carta Porte 1.0".

Include the following unreleased changes

### 2021-07-05

- Fix documentation badge point to *latest* instead of *stable*.
- Fix build on *Read The Docs* by adding a config file and Python requirements file.

### 2021-05-18

- The *Certificado de Sello Digital (CSD)* `CSD01_AAA010101AAA` is expired, it has been changed to `EKU9003173C9`.
- Upgrade `php-cs-fixer` to version `3.0`.


## Version 2.15.1 2021-04-04

- Fix try to close public key when it didn't exist. Good catch PHPStan!

### Migrate from Travis-CI and AppVeyor to GitHub Actions

I'm more than grateful to both platforms for supporting this open source project for the previous years.
I recommend both platforms as an alternative to GitHub Actions.


## Version 2.15.0 2021-03-17

Improvements:

- Include validation web service version 1.3 new response `ValidacionEFOS` as
  `StatusResponse::getValidationEfos() string` and `StatusResponse::isEfosListed() bool`.
- Update `ConsultaCFDIServiceSAT.svc.xml`. It is unused, but exists for compatibility.

General:

- Upgrade to PHPUnit 9.5 and upgrade test suite.
- Test classes are declared as final.
- Remove support for PHP 7.0, PHP 7.1 and PHP 7.2.
- Compatibilize with PHP 8.0 / OpenSSL:
    - openssl functions does not return resources but objects.
    - On deprecated functions run only if PHP version is lower than 8.0 and put annotations for `phpcs`.

Bugfixes:

- Validation `SELLO04` fails when there are special characters like `é` and `LC_CTYPE` is not setup.
- Fix `COMPIMPUESTOSC01` description typo.

There are some soft backwards incompatibility changes:

- Method `__construct()` of class `CfdiUtils\Validate\Cfdi33\Standard\FechaComprobante` became final
- Method `__construct()` of class `CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pago` became final
- The return type of `CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pago#getValidators()` changed from no type to array
- The parameter $decimals of `CfdiUtils\Utils\Format::number()` changed from no type to a non-contravariant int
- The parameter $content of `CfdiUtils\Cleaner\Cleaner::staticClean()` changed from no type to a non-contravariant string.

Development environment:

- AppVeyor: Only run PHPUnit
- Travis-CI: On PHP != 7.4 only run PHPUnit
- Travis-CI: On PHP == 7.4 run all the build commands
- PHPStan: Upgrade to version 0.12, downgrade level to 5.


## Version 2.14.2 2021-03-16

### `FormaPago` on `N - Nómina`

- Validation `TIPOCOMP03` does not apply on documents type `N - Nómina`.


## Version 2.14.1 2021-03-14

### `MetodoPago` on `N - Nómina`

- Remove redundant validations `METPAG01` and `METPAG02`.
- Validation `TIPOCOMP04` does not apply on documents type `N - Nómina`.

### Download certificates (unreleased 2020-10-08)

- Looks like web service at `https://rdc.sat.gob.mx/` is having issues (again). This is breaking testing.
  To mitigate the problem, a new testing class `CertificateDownloaderHelper` has been created to retry the
  download if it fails, for a maximum of 5 attempts. This change does not create a new release version.

### Travis-CI build (unreleased 2020-12-03)

- Add `XDEBUG_MODE=coverage` on Travis-CI configuration file since it upgrade to `XDebug v3.0.0` and it contains
  a bug that makes `PHPUnit` break with the message: *Use of undefined constant XDEBUG_CC_UNUSED - assumed 'XDEBUG_CC_UNUSED'*.
  This will be resolved on <https://github.com/xdebug/xdebug/pull/699> but not released (yet).


## Version 2.14.0 2020-10-01

- Add `Retenciones` reader to work with *CFDI de retenciones e información de pagos*.
- Refactor `Cfdi` and `Retenciones` to use recently created `XmlReaderTrait`.


## Version 2.13.1 2020-10-01

- Fix validation `FORMAPAGO01`, it only applies when `Complemento de Pago` exists.
- Add `Retenciones` reader to work with *CFDI de retenciones e información de pagos*.


## Version 2.13.0 2020-08-28

- Add helper elements for *Complemento de Nómina 1.2 revisión B*.
    - Root element is `CfdiUtils\Elements\Nomina12\Nomina`.
    - Add test with 100% coverage on the namespace.
    - Add documentation.
    - **Important** It does not contain any validators but standard `XSD`.


## Version 2.12.11 2020-08-16

- Fix TimbreFiscalDigital XSLT URL locations, updated from SAT documentation.
  For more information check [`phpcfdi/sat-ns-registry`](https://github.com/phpcfdi/sat-ns-registry) project.


## Version 2.12.10 2020-07-18

- Documentation "Descarga de recursos XSD y XSLT"

    - *2020-07-14*: Documentation: "Descarga de recursos XSD y XSLT"
    - *2020-07-18*: Documentation: Add last document to `mkdocs:nav`, format rewording and links.

- Add `tests/resource-sat-xml-download`, include it on travis build.

SAT has been failing providing XSD and XSLT files. This tool obtains (via `tests/resource-sat-xml-download`) a fresh copy
of those files from [`phpcfdi/resources-sat-xml`](https://github.com/phpcfdi/resources-sat-xml) project for development.

- Add script to install [`phpcfdi/resources-sat-xml`](https://github.com/phpcfdi/resources-sat-xml) on AppVeyor build.

- Fix default locations for TFD 1.0.

In the past, SAT allowed at least 2 different URLS for TFD 1.0 on XSD and XSLT files. In this version this is
normalized with [`phpcfdi/sat-ns-registry`](https://github.com/phpcfdi/sat-ns-registry) project.

- Add a new cleaner method `Cleaner::fixKnownSchemaLocationsXsdUrls` to override the XSD file URLS for CFDI and TFD.

This replaces any known and found url ignoring case and put the correct one,
it also replaces `http://www.sat.gob.mx/sitio_internet/TimbreFiscalDigital/TimbreFiscalDigital.xsd` (unused)
with `http://www.sat.gob.mx/sitio_internet/cfd/TimbreFiscalDigital/TimbreFiscalDigital.xsd` (official).

- Improve explanation on `TFDSELLO01` when unable to get certificate.

The assertion `TFDSELLO01` *El Sello SAT del Timbre Fiscal Digital corresponde al certificado SAT*, now includes the
exception message when unable to obtain a certificate.

- Remove insecure downloader from testing.

This was introduced previously because the webserver was using invalid SSL certificates.
This problem does not exist anymore (since 2019-10-24).


## Version 2.12.9 2020-04-25

- Review and fix `CreateComprobantePagosCaseTest`.
- Add doc-blocks on `StatusResponse` and fix script `tests/estadosat.php`.
- Remove `overtrue/phplint` from development dependencies.


## Version 2.12.8 2020-01-07

- Change License year to 2020.
- Change running dependence of `symfony/process` to allow version `^0.5`
- Change development dependence `phpstan/phpstan-shim` to `phpstan/phpstan` (versions `^0.9` to `^0.11`)


## Version 2.12.7 2019-12-04

- Add 2 new default cleans, before loading the CFDI as XML DOM Document:
    - Change invalid `xmlns:schemaLocation` to `xsi:schemaLocation`. SAT uses to create *"valid"* CFDI with this error.
    - Remove `xmlns="http://www.sat.gob.mx/cfd/3"` when also `xmlns:cfdi="http://www.sat.gob.mx/cfd/3"` is found.
- Improve `SerialNumber` to use `map + impode` instead of concatenation.
- Improve `SerialNumber` to `substr` instead of `strpos` to check if a string start with text.
- (DOC) Add cleaner notes and example about clean before load.
- (DOC) Simplify example on `RequestParameters` usage.
- (DEV) Add support for PHP 7.4 on Travis-CI
- (DEV) Add support for PHP 7.4 on AppVeyor
    - Downgrade `chocolatey` to 0.10.13
    - Add `--no-progress` to `choco` commands
    - Setup extensions declared without prefix `php_` on PHP versions lower than 7.2
    - Add `PHP_CS_FIXER` environment variables


## Version 2.12.6 2019-10-23

- Fix `REGFIS01` validation when receiving an RFC with non-ASCII chars (like `Ñ`).
  It does not validate that the RFC is correct, that validation is on `EMISORRFC01`.
- Add installation to documentation.
- Improve `php-cs-fixer` rules.
- Fix travis build on PHP version `7.4snapshot`.


## Version 2.12.5 2019-10-15

- Fix bug when creating a `Certificado` object and PEM content's length is less than 2000 bytes.
    - Now it does not care about the length.
    - If the content is a valid base64 string but is a path then will be used as a path.
    - When checking that the file exists, catches if the path has control characters (like in DER content).
    - Previous validation now makes `file_exists` to not throw an `Error`.


## Version 2.12.4 2019-10-10

- The validation `CONCEPDESC01` was not correctly set, it was checking concept discount against document subtotal,
  it was fixed to concept discount against concept import. It covers SAT rule `CFDI33151`, not `CFDI33109`.
- New validation `DESCUENTO01` that verify document discount against document subtotal, covers `CFDI33109`.
- Development: include `build/` empty folder (with `.gitignore` to exclude all)
- Continuous Integration:
    - Travis remove `sudo: false` and build on `dist: xenial`.
    - Scrutinizer add more time to wait for coverage test.
    - Remove CodeClimate integration.


## Version 2.12.3 2019-09-26

- `CfdiUtils\Certificado\Certificado` can be created using PEM contents and not only a certificate path.
- `CfdiUtils\Creator33` can use a certificate without associated filename.
- `CfdiUtils\RetencionesCreator10` can use a certificate without associated filename.
- `CfdiUtils\Certificado\NodeCertificado` can obtain the certificate without creating a temporary file.


## Version 2.12.2 2019-09-24

- When cannot load a Xml string include `LibXMLError` information into exception, like:

    ```text
    Cannot create a DOM Document from xml string
    XML Fatal [L: 1, C: 7]: Input is not proper UTF-8
    ```

- Include composer `support` sections `source` and `chat`
- Development: Exclude correct file `.appveyor.yml` (was `.appveyor.xml`)


## Version 2.12.1 2019-09-11

- Trigger E_USER_DEPRECATED on `CfdiUtils\Cleaner\Cleaner#removeIncompleteSchemaLocation()`
- Trigger E_USER_DEPRECATED on `CfdiUtils\Certificado\SerialNumber#baseConvert()`
- Improvements on docs/index
- Remove several development files from final package
- Development:
    - Fix `.editorconfig`
    - Integrate `codeclimate`, evaluate for a while to consider a replacement for `scrutinizer`
    - Add PHP 7.4snapshot
    - Remove Symfony Insight config file
    - On `composer dev:build` it also calls `composer dev:check-style`


## Version 2.12.0 2019-09-03

- Add `CfdiCreator33::moveSatDefinitionsToComprobante()` method to move xml definitions
  (`xmlns:*` and `xsi:schemaLocation`) to root node where namespace starts with `http://www.sat.gob.mx`.
- Add `CfdiUtils\Nodes\NodeNsDefinitionsMover` that allows to move all (or filtered) namespace definitions
  (`xmlns:*` and `xsi:schemaLocation`) to root node.
- Improve `XmlNodeImporter` to read xml as `<node xmlns="namespace"/>`.
- Create `SchemaLocations`, this utility helps to manage the content of `xsi:schemaLocation` attribute.
- Refactor `Cleaner`, internal improvements and use of `SchemaLocations`.
- Mark `CfdiUtils\Cleaner\Cleaner:removeIncompleteSchemaLocation()` as internal, it should always be private
- Fix composer.json links


## Version 2.11.0 2019-08-06

- Add `Cleaner::collapseComprobanteComplemento()` to deal with more than one `cfdi:Complemento`.
- Append call to `Cleaner::collapseComprobanteComplemento()` on `Cleaner::clean()`.
- Create test that demostrate issues when *`TimbreFiscalDigital`* is on a second `cfdi:Complemento`
  and that collapsing removes the issues and do not change the *"cadena de origen"*.
- Document SAT issue with multiple `cfdi:Complemento` (problems and clean).
- Travis: since `mkdocs` version is newer, there is no need to change `nav` to `pages` to compile docs.
- PHPStan: create `phpstan.neon.dist` with `inferPrivatePropertyTypeFromConstructor`.


## Version 2.10.4 2019-06-27

- Add `Xml::createElement` and `Xml::createElementNS` to deal with non escaped ampersand `&`
  on `DOMDocument::createElement` and `DOMDocument::createElementNS`.
- Improve `Rfc::obtainDate` with invalid length dates and tests


## Version 2.10.3 2019-05-29

- Add static methods to `CfdiUtils\Utils\Xml`, these methods are created to help to fix issues found by `phpstan`:
    - `Xml::documentElement(DOMDocument $document): DOMElement`: Safe helper to get `$document->documentElement`
    - `Xml::ownerDocument(DOMNode $node): DOMDocument`: Safe helper to get `$node->ownerDocument`
- Fix [`phpstan`](https://github.com/phpstan/phpstan) 0.11.6 issues, this must solve all travis build


## Version 2.10.2 2019-04-08

- Fix bug on `QuickReader` getting the content of falsy values (like `"0"`) return an empty string.
  Thanks `@jaimeres`. (Closes #48)


## Version 2.10.1 2019-04-02

- When any `Throwable` is thrown on `XmlFollowSchema` validator fail with error,
  previous behavior was to just catch `SchemaValidatorException`.
- Add specific tests for `XmlFollowSchema`.
- Move community chat from gitter to discord <https://discord.gg/aFGYXvX>


## Version 2.10.0 2019-03-26

- Include in `CfdiUtils\ConsultaCfdiSat\StatusResponse` the values of `EsCancelable` and `EstatusCancelacion`.
- Change SOAP call to avoid WSDL requirement.
- Deprecate uses of WSDL in `CfdiUtils\ConsultaCfdiSat\Config`.
- Update *Estado SAT* documentation.
- SymfonyInsight, add config file `.symfony.insight.yaml`


## Version 2.9.0 2019-03-15

- Add `CfdiUtils\OpenSSL`, a helper class to work with `openssl` commands and CER, KEY and PEM files.
  Fully tested and [documented](https://cfdiutils.readthedocs.io/es/latest/utilerias/openssl/).
- Improve other components by depending on `CfdiUtils\OpenSSL`:
    - `CfdiUtils\Certificado\Certificado`:
        - Use `OpenSSLPropertyTrait`.
        - Allow to construct using a `OpenSSL` object, default to create a new one.
        - Remove protected method `changeCerToPem`.
    - `CfdiUtils\PemPrivateKey\PemPrivateKey`:
        - Use `OpenSSLPropertyTrait`.
        - Allow to construct using a `OpenSSL` object, default to create a new one.
        - Deprecate static method `isPEM`, use `OpenSSL` instead.
        - Fix test since OpenSSL is stricter on getting contents.
        - Since this version, other private keys are allowed, as the created by PHP `ENCODED PRIVATE KEY`.
- Improvements on `SaxonbCliBuilder`:
    - Improve compatibility on MS Windows by using `ShellExec`.
    - Deprecate method `createCommand`.
    - Use new features from internal class `TemporaryFile`.
- Test:
    - Increase coverage of `XmlResolverPropertyTrait`.
    - Cover `SumasConceptosWriter::getComprobante()`.
    - Cover `CfdiUtils\Certificado\SerialNumber::loadHexadecimal` when throw exception.
    - Cover `CfdiUtils\Nodes\Attributes::import` (and constructor) when throw exception.
- `Genkgo/Xsl` upgraded to 0.6 (compatible with PHP 7.0), also fix suggestion on `composer.json` file.
- Internal: `TemporaryFile` now is able to cast itself to string returning the path to file,
  retrieve contents, store contents and remove file after run some function even if exception was thrown.
- Internal: Add `ShellExec` class that works around with `symfony/process` component. Also added:
    - `ShellExecResponse`: contains the response of ShellExec::run().
    - `ShellExecTemplate`: basic command array creation from a string template.
- Internal: Move internal to `CfdiUtils\Internal`. Check `@internal` annotation on all elements. Add README.md
- CI: `AppVeyor` complete refactor, now uses correctly caches and upgrade php if required.
- CI: Only run `phpstan` on PHP 7.3.
- Dev: `composer dev:build` now runs `phpunit  --testdox --verbose --stop-on-failure`.


## Version 2.8.1 2019-02-05

- Extract base convert logic from `CfdiUtils\Certificado\SerialNumber::baseConvert` to new internal classes:
    - `CfdiUtils\Utils\Internal\BaseConverterSequence` Value object to store the character maps.
    - `CfdiUtils\Utils\Internal\BaseConverter` Object that perform the conversion.
- Fix possible bug converting from an inferior to a superior base thanks to new test on `BaseConverter`.
- Classes inside `CfdiUtils\Utils\Internal\` namespace should not be used outside the library.
  Changing this will not be considered a backward compatibility break.
- Deprecate `CfdiUtils\Certificado\SerialNumber::baseConvert`.
- Create `CfdiUtils\Utils\Internal\TemporaryFile` to avoid using directly `\tempnam` and throw `\RuntimeException`
- Replace usages of `\tempnam` with `TemporaryFile::create()` on:
    - `CfdiUtils\CadenaOrigen\SaxonbCliBuilder`
    - `CfdiUtils\Certificado\NodeCertificado`
    - `CfdiUtils\Validate\Cfdi33\Standard\TimbreFiscalDigitalSello`
    - `CfdiUtilsTests\Certificado\NodeCertificadoTest`
- Fix possible bug on `CfdiUtils\Cleaner\Cleaner` when making an XPath query.
- Fix docblock on `CfdiUtils\QuickReader\QuickReader` on magic method `__get`.
- Fix issues on functions expecting a variable of certain type but receiving false instead. Thanks `phpstan`!
- Call `NodeInderface::offsetExists($name)` instead of `isset(NodeInderface[$name])`.
  The reasons behind this change are:
    - `isset` is not a *function* but a *keyword*, making `phpstan` or other tools to fail on this.
    - `isset` should be understand as *variable is defined and is not `NULL`*,
      which in the case of `NodeInderface[$name]` is never `NULL`.
  The previous change does not have to be replicated in the users of this library. It is internal.
  In future version (when BCB are allowed) will introduce a better method for this operation
  `NodeInderface::exists(string $name): bool` and will fix documentation to better use this method instead of `isset`.
- Fix documentation on `docs/leer/leer-cfdi.md` about method `NodeInterface::getNode()`. Thanks `@ReynerHL`.


## Version 2.8.0 2019-01-29

- Initial attempt to create a RET 1.0 (*CFDI de retenciones e información de pagos*):
    - Add namespace `\CfdiUtils\Retenciones`.
    - Add class `\CfdiUtils\Retenciones\RetencionesCreator10`.
    - Add test for green path on creating a CFDI without TFD.
    - Add test to ensure that `validate` method is checking document against schema.
    - Add namespace `\Elements\Retenciones10` to add helper elements for `retenciones:Retenciones`.
    - Add namespace `\Elements\Dividendos10` to add helper elements for `dividendos:Dividendos`.
    - Add namespace `\Elements\PagosAExtranjeros10` to add helper elements for `pagosaextranjeros:Pagosaextranjeros`.
- `CfdiUtils\CfdiCreator33` constructor docblock was setting type of attributes as `string[]` when it should be `array`.
  Values can be scalar and objects with `__toString()` implemented.
- Inside attributes, when casting an attribute value to string fails then show the attribute name in exception.


## Version 2.7.6 2019-01-17

- Rename validation code `XDS01` to `XSD01` as reported by `@blacktrue`
- `SumasConceptos` must ignore `Comprobante/Conceptos/Concepto/Impuestos/Traslados/Traslado`
   when `TipoFactor == Exento`.
- Add tests on how `SumasConceptos` should work with `TipoFactor == Exento` and how attributes are written.
- Travis: since `mkdocs` version is old, just change `nav` to `pages` to compile docs.
- Docs: Improve texts and examples on *Cadena de Origen*.


## Version 2.7.5 2019-01-04

- Make `XsltBuilderPropertyTrait` follow other `*PropertyTrait`
- Improve documentation on *Cadena de Origen*
- Fix `mkdocs.yml` config, `pages` is deprecated, now use `nav`


## Version 2.7.4 2018-12-12

- Add `CfdiUtils\Certificado\Certificado::getSerialObject` to return **a copy** of the instance
  of `CfdiUtils\Certificado\SerialNumber` in case is required.
- Add `CfdiUtils\Certificado\SerialNumber::loadAscii` helper function
- Improve tests on `SerialNumberTest` to include `loadAscii`
- Improve tests on `CertificadoTest` to include `getSerialObject`


## Version 2.7.3 2018-12-05

- Fix previous release since it did not publish the changes made on 2.7.2.


## Version 2.7.2 2018-12-05

- Add method `CfdiUtils\Certificado\Certificado::getCertificateName(): string` to obtain the certificate
  name as returned by `openssl_x509_parse`.


## Version 2.7.1 2018-12-04

- Fix wrong use of `escapeshellcmd` replacing with `escapeshellarg`
- Add argument `-c|--clean` to script `tests/validate.php` to perform clean before validate
- Fix `CfdiCreator33::newUsingNode` since not all attributes where correctly imported (`xsi:schemaLocation`)
- Fix calling `CfdiCreator33::putCertificado` on imported cfdi (Emisor child is a `NodeInterface` but not `Emisor`)
- Refactor `CfdiUtils\ConsultaCfdiSat\WebService` to be able to use a local copy of WSDL file since SAT does not
  allow to download it anymore.
    - Add `ConsultaCFDIServiceSAT.svc.xml` to namespace folder
    - Add `Config::wsdlLocation` static method to get `ConsultaCFDIServiceSAT.svc.xml` file path
    - Deprecate `Config::getWsdlUrl` in favor of `Config::getServiceUrl`
    - Add `Config::getServiceUrl`
    - Add `Config::wsdlLocation` property
- Add `--local-wsdl` parameter to `tests/estadosat.php` script
- Add a new step on `CfdiUtils\Cleaner\Cleaner` that removes from `xsi:schemaLocations` the namespaces that are
  not followed by a string that ends on `.xsd`


## Version 2.7.0 2018-10-19

- Reintroduce `MontoGreaterOrEqualThanSumOfDocuments` as `PAGO30`.
    - There is a legal contradiction in `Pagos` between `Guía de llenado` and `Matriz de errores`
    - For more information check documentation <https://cfdiutils.readthedocs.io/es/latest/problemas/contradicciones-pagos/>
- Change `CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\ValidatePagoException` to use status property.
- Change `CfdiUtils\Validate\Cfdi33\RecepcionPagos\Pagos\ValidateDoctoException` to extends `ValidatePagoException`.
- Honor status from `ValidatePagoException` or `ValidateDoctoException`
- Tests use XmlResolver from `CfdiUtilsTests\TestCase` instead of creating a new one
- Fix docblock `CfdiUtils\Nodes\Nodes::searchNodes`
- Improve doc-blocks on `CfdiUtils\Certificado\Certificado`
- Documentation:
    - Create `docs/problemas/contradicciones-pagos.md`
    - Create `docs/problemas/descarga-certificados.md` to document error `TFDSELLO01`
    - Create examples on `docs/componentes/certificado.md` on object creation
- Change tests to not ssl verify peer due SAT web server configuration errors (expired certificate)
    - Add `CfdiUtilsTests\TestCase::newInsecurePhpDownloader(): DownloaderInterface`
    - Use insecure downloader in `CfdiUtilsTests\CfdiValidator33Test`
    - Use insecure downloader in `CfdiUtilsTests\Validate\Cfdi33\Standard\TimbreFiscalDigitalSelloTest`
    - Use insecure downloader in `CfdiUtilsTests\Certificado\CerRetrieverTest`
    - Also add note to `docs/TODO.md` to remove this insecure downloader when SAT server is fine
- Change composer scripts and prefix `dev:`, commands are now:
    - `dev:build`: run dev:fix-style dev:tests and dev:docs, run before pull request
    - `dev:check-style`: search for code style errors using php-cs-fixer and phpcs
    - `dev:fix-style`: fix code style errors using php-cs-fixer and phpcbf
    - `dev:docs`: search for code style errors using `markdownlint` and build docs using `mkdocs`
    - `dev:test`: run `phplint`, `phpunit` and `phpstan`
    - `dev:coverage`: run `phpunit` with `xdebug` and storage coverage in `build/coverage/html`


## Version 2.6.6 2018-10-04

- After previous update on validation `PAGO09` and more testing found that it requires to round lower and upper limits.
- Create more 1 case with specific data and 1 test with 20 cases with random data.


## Version 2.6.5 2018-10-04

- Fix validation `PAGO09`:
    - Before 2018-09-01 `pagos10:Pago@Monto` must be less or equal than sum of `pago10:DoctoRelacionado@ImpPagado`
      in the same currency as `pagos10:Pago`.
    - Since 2018-09-01 according to *Guía de llenado del comprobante al que se le incorpore el complemento para
      recepción de pagos, page 22* it is required that  `pagos10:Pago@Monto` must be in an interval.
    - Fix samples from `tests/assets/pagos/` since new validation make it fail.
    - Rename validation class `MontoGreaterOrEqualThanSumOfDocuments` to `MontoBetweenIntervalSumOfDocuments`
- Refactor `CfdiUtils\Certificado\Certificado` extracting obtain public key routine to an internal method.
- Create tests for trait `CalculateDocumentAmountTrait`.


## Version 2.6.4 2018-09-04

- Fix validation `TIPOCAMBIO02`:
    - Previous validation only allows value `"1"` if currency `MXN`.
      Now it allows any value equals to `1` considering 6 decimal numbers, so the following values
      are valid: `"1"`, `"1.00"`, `"1.000000"`
    - Change description from `... debe ser "1"...` to `... debe tener el valor "1"...`
- Fix Scrutinizer issue in `Validate/Cfdi33/Standard/ComprobanteImpuestos.php`:
  *Using logical operators such as and instead of && is generally not recommended*
- `CfdiUtils\ConsultaCfdiSat\WebService` is using SAT Web Service but since 2018-08 it is randomly failing.
    The test that are consuming <https://consultaqr.facturaelectronica.sat.gob.mx/ConsultaCFDIService.svc?singleWsdl>
    was moved to a different test case class `WebServiceConsumingTest` and are marked as skipped when `\SoapFault`
    is thrown instead of fail
- Fix `xmlns:xsi` definition case to `XMLSchema`
- Allow to install phpunit 7 if php >= 7.1
- Fix `phpunit.xml.dist` configuration file removing redundant options and setting missing options
- Solve PHPStan 0.10.x issues, not yet upgraded since it contains several bugfixes


## Version 2.6.3 2018-08-21

- Fix validations `COMPIMPUESTOSC02` and `COMPIMPUESTOSC03`
    Previously both or any should exist (`xnor`): nodes `Traslado|Retencion` and attributes `TotalImpuestosTrasladados|TotalImpuestosRetenidos`
    Now it allows to have `Impuestos` totals even when related nodes does not exist
    This is because is not mandatory by Anexo 20
    What is mandatory is that if nodes exists then totals must exist
- Add helper method to create a `RequestParameters` from a `Cfdi`
- Fix: add missing dependence `ext-iconv` into `composer.json`
- Testing: add helper development script `tests/estadosat.php`
- Testing: Change compareNames & castNombre visibility for direct testing


## Version 2.6.2 2018-07-17

- Dependence on <https://github.com/eclipxe13/XmlSchemaValidator> has been set to `^2.0.1`
  to fix validation using XSD local repository on MS Windows.
- Improve doc-blocks in property traits
- Restore previous error handler on `ComprobanteGetCfdiRelacionadosTest`
- Make sure that input file on `PemPrivateKey` is not a directory and is readable
- On MS Windows send to `NUL` instead of `/dev/null`
- Convert from `UTF-8` to `ASCII//TRANSLIT` can add single quotes, remove it.
- Add [AppVeyor](https://ci.appveyor.com/project/eclipxe13/cfdiutils) continuous integration
- Add documentation about developing this library on Windows
- Allow to set `saxonb` path using environment variable `saxonb-path`


## Version 2.6.1 2018-07-16

- Fix order of `Impuestos` children (thanks `@aldolinares`):
    - When is inside `Comprobante` the order is `Retenciones` then `Traslados`
    - When is inside `Concepto` the order is `Traslados` then `Retenciones`
- Add `testMultiRelacionado` in `ComprobanteTest`
- Fix Markdown syntax errors in a lot of documents
- Use self instead of static in doc-blocks, static is not standard
- Add badges to `docs/index.md`


## Version 2.6.0 2018-07-06 - bugfixes, quickreader & welcome readthedocs & mkdocs

- Create `QuickReader`, utility for easy navigate and extract information from a CFDI
- Fix `Rfc` to don't throw an exception if checksum fails, SAT is not following its own standard
- Add `Rfc::checkSum()` and `Rfc::checkSumMatch()` to know if the Rfc is following the checksum
- Fix tests that expect Rfc checksum failure
- Fix tests comments on `testDescuentoNotSetIfAllConceptosDoesNotHaveDescuento`
- Fix `CfdiUtils\Elements\Cfdi33\Comprobante::getCfdiRelacionados` to don't receive a parameter.
    - For backwards compatibility when it receives a parameter do the same thing but trigger a `E_USER_NOTICE`
    - Create a special test case `ComprobanteGetCfdiRelacionadosTest` that catch the `E_USER_NOTICE` error
- Add `CfdiUtils\Elements\Cfdi33\Comprobante::addCfdiRelacionados(array $attributes)`
- Add `CfdiUtils\Elements\Cfdi33\Comprobante::multiCfdiRelacionado(array $attributes)`
- Add tests to assert that `Comprobante/Impuestos/(Traslados/Traslado|Retenciones/Retencion)@Impuesto` is rounded
- Minor fix at doc-blocks for packed arguments
- Change all documentation to move from GitHub Wiki to ReadTheDocs <https://cfdiutils.readthedocs.io/>
    - More documentation pages & a lot of fixes
    - Add `.markdownlint.json` to run with `markdownlint-cli` (`node`), add to travis build process
    - Add `mkdocs.yml` to run with `mkdocs` (`python`), add to travis build process
    - Fix markdown files according to `markdownlint`
    - Add `composer docs` and append to general `composer build`


## Version 2.5.1 2018-06-26

- Fix edge case for validations `SELLO03` and `SELLO04` on `SelloDigitalCertificado`.
  In some cases, the authority does not require a certificate from emisor and uses one certificate for its own.
  Therefore, the `Rfc` and `Nombre` of the `Emisor` does not match with the certificate. This produces a false error.
  To avoid this issue, the validation of `Rfc` and `Nombre` matching with the certificate data must not perform when:
    - The `cfdi:Comprobante@NoCertificado` is the same as the `tfd:TimbreFiscalDigital@NoCertificadoSAT`
    - The "complemento" `registrofiscal:CFDIRegistroFiscal` exists


## Version 2.5.0 2018-05-24

- Add validations for `http://www.sat.gob.mx/Pagos` at namespace `\CfdiUtils\Validate\Cfdi33\RecepcionPagos`
    This is a big change that includes more than 50 validators that work in cascade.
    It implements almost all the validations from the SAT "Matriz de errores".
- Append it to `\CfdiUtils\Validate\MultiValidatorFactory`
- Remove non-existent validators discovery `Cfdi33/Timbre`
- Move logic of version discovery to a new class, change `CfdiVersion` and `TfdVersion` to implement this logic
- Deprecate `static` methods from `\CfdiUtils\CfdiVersion`, instead create an instance of the class
- Deprecate `static` methods from `\CfdiUtils\TimbreFiscalDigital\TfdVersion`, instead create an instance of the class
- Fix deprecation notices existent doc-blocks
- Update deprecation notice to README
- Replace TODO with a more explained version


## Version 2.4.6 2018-05-24

- Fix validation of `TIPOCOMP06`, it was not checking correctly.
- Fix bug in validators that does not respect when the resolver does not have local path:
    - `CfdiUtils\Validate\Cfdi33\Standard\TimbreFiscalDigitalSello`
    - `CfdiUtils\Validate\Cfdi33\Xml\XmlFollowSchema`
- Fix bug when removing a `schemaLocation` attribute in `CfdiUtils\Cleaner\Cleaner`
- Refactor `CfdiUtils\ConsultaCfdiSat\WebService::request` and move the SOAP call
  to a protected method, this allows better testing of the class by mocking the call
- In `CfdiUtils\PemPrivateKey\PemPrivateKey` deprecate `isOpened` and add `isOpen`
- In `CfdiUtils\Cfdi::getNode` use `XmlNodeUtils` instead of `XmlNodeImporter`
- In `CfdiUtils\Cfdi::newFromString` create `new self` instead of `new static`.
  If using `new static` the constructor might be different, and it would fail.
- In `CfdiUtils\CfdiVersion::fromXmlString` it no longer create a Cfdi object,
  it will just create a `DOMObject` and delegate to
  `fromDOMDocument` as in `TfdVersion`.
- Remove `CfdiUtils\Elements\Pagos10\Pago::multiImpuestos`,
  it should never exist and must not have any use case.
- Improve testing on:
    - `CfdiUtils\Elements\Pagos10\Pagos`
    - `CfdiUtils\Validate\Cfdi33\Standard\ConceptoImpuestos`
- Improve doc-blocks and fix typos in several files
- Add new parameter to development script `tests/validate.php`:
  `--no-cache` that tell resolver to not use local cache.
- Improve travis disabling xdebug always and only use it in phpunit code coverage

## Version 2.4.5 2018-05-12

- Fix: change xml namespace prefix `pagos10` to `pago10`
- Refactor `CfdiUtils\Certificado\SerialNumber::baseConvert`
- Add `CfdiUtils\Certificado\SerialNumber::asDecimal()`
- Fix `CfdiUtils\Cleaner\Cleaner` since internal `DOMDocument` can be null
- Allow attributes `CfdiUtils\Elements\Cfdi33\Comprobante::getCfdiRelacionados`
- Do not use `CfdiUtils\CadenaOrigen\DefaultLocations` at any place of the project
- Add util `\CfdiUtils\Utils\CurrencyDecimals`, help to work with decimals by currency
- Improve `CfdiUtils\Validate\Cfdi33\Standard\ComprobanteDecimalesMoneda` with previous class
- Add util `\CfdiUtils\Utils\Rfc`, help to work with strict RFC validations
- Add `\CfdiUtils\Validate\Cfdi33\Standard\ReceptorRfc` to validate the RFC of the CFDI receiver
- Add `\CfdiUtils\Validate\Cfdi33\Standard\EmisorRfc` to validate the RFC of the CFDI emitter
    - Fix `CfdiUtilsTests\CfdiValidator33Test::testValidateWithCorrectData` since used RFC is not valid
    - Fix `CfdiUtilsTests\CreateComprobanteCaseTest::testCreateCfdiUsingComprobanteElement` since used RFC is not valid
- Add doc-blocks to `CfdiUtils\Cfdi`
- Building:
    - Add `.phplint.yml` to export-ignore (standard line)
    - Travis-CI: Declare `FULL_BUILD_PHP_VERSION` for easy understanding
- Add more dependencies: `ext-dom`, `ext-xsl`, `ext-simplexml`, `ext-mbstring`

## Version 2.4.4 2018-05-11

- FIX: Unable to load a PEM file using filename on Windows (Closes #33)
- Do not use bcmath function to convert from decimal to hexadecimal the serial number of a certificate

## Version 2.4.3 2018-04-26

- FIX: The attribute `cfdi:Comprobante@Descuento` must not be deleted if any attribute
  `cfdi:Comprobante/cfdi:Conceptos/cfdi:Concepto@Descuento` exists. (Closes: #50)
- FIX: When validating a CFDI, the validator `CfdiUtils\Validate\Cfdi33\Standard\SelloDigitalCertificado`
  was too hard. In common practice, must allow `-`, `-` and compare without special chars like `ü`. (Closes #51)
- Add a **development** script `tests/validate.php` to validate existing files.
  WARNING: This can change at any time! Do not depend on this file or its results!

## Version 2.4.2 2018-04-23

- Fix `\CfdiUtils\Nodes\XmlNodeExporter::export`, it was not appending root element to xml document.
- Allow `\CfdiUtils\Nodes\XmlNodeUtils::nodeToXmlString` to export including xml header `<?xml ... ?>`.
  Default behavior is to not include xml header, it remains unchanged.
- Explicitly `\CfdiUtils\CfdiCreator33::asXml()` returns the string with xml header.
- By default, `\DOMDocument` objects are created with version 1.0 and encoding UTF-8.
- Add tests to validate previous changes.

## Version 2.4.1 2018-04-11

- Fix `\CfdiUtils\Certificado\Certificado` when reading serial number.
    - Use `serialNumberHex` if available, if not then use `serialNumber` and convert to hex.
- Move serial number string conversion to class `\CfdiUtils\Certificado\SerialNumber`.
  This class is not for public use but for use inside `Certificate`.

## Version 2.4.0 2018-02-08

- Add the feature to order the children nodes for a `CfdiUtils\Nodes\Nodes` object.
  This feature is used in the namespace `CfdiUtils\Elements` to set the correct order of the children nodes without worry about the creation order.
- Add `CfdiUtils\Elements\Addenda` helper class.
- Add `CfdiUtils\Elements\Pagos10` namespace for "complemento de pagos 1.0".
- Add `CfdiUtils\Cleaner\Cleaner` utility class that allows to remove `cfdi:Addenda`,
  non SAT nodes, non SAT namespaces and unused namespaces.
- Build: The project no longer depends on `jakub-onderka/php-parallel-lint`,
  now uses `overtrue/phplint` that does the same task but stores a cache.

## Version 2.3.2 2018-01-29

- Fix how total is formatted in the expression of `\CfdiUtils\ConsultaCfdiSat\RequestParameters`
    - Version 3.2 was removing zero trailing decimals instead of using 6 fixed chars
    - Version 3.3 was not using 1 leading zero (for integers) and 1 trailing zero (for decimals)
- On method `\CfdiUtils\Certificado\NodeCertificado::obtain()` change logic
  and throw exception if temporary file cannot be created

## Version 2.3.1 2018-01-25

- Add elements helpers `CfdiUtils\Elements\Tfd11\TimbreFiscalDigital` to work with "TimbreFiscalDigital"


## Version 2.3.0 2018-01-25

- Add a client `\CfdiUtils\ConsultaCfdiSat\WebService` for the SAT WebService
  `https://consultaqr.facturaelectronica.sat.gob.mx/ConsultaCFDIService.svc?singleWsdl`
- Fix bug, must use `children()` method instead of `children` property.
  Did not appear before because the variable using the property was always
  a `Node` but other implementation of `NodeInterface` would cause this to break.
- Add a lot of fixes in doc-blocks to move `@param $var` to `@param type $var`.
- Add extensions requirements to composer.json: libxml, openssl & soap.
- Upgrade `phpstan/phpstan-shim` to version 0.9.1, the not-simple-to-see bug fixed
  in this version was found by `phpstan` - <https://github.com/phpstan/phpstan>


## Version 2.2.0 2018-01-24

- Refactor namespace `\CfdiUtils\CadenaOrigen` (backwards compatible):
    - Instead of one only xslt builder now it includes:
    - `DOMBuilder`: Uses the regular PHP based method
    - `GenkgoXslBuilder`: Uses the library `genkgo/xsl` xslt version 2 library
    - `SaxonbCliBuilder`: Uses the command line `saxonb-xslt` command
    - Build process implementations must return `XsltBuildException` (before they return `RuntimeException`)
    - All builders must implement `XsltBuilderInterface`
    - Add `XsltBuilderPropertyInterface` and `XsltBuilderPropertyTrait`.
    It does not have `hasXsltBuilderProperty`method.
    - `DefaultLocations` has been deprecated in favor of `CfdiDefaultLocations`
    - `CadenaOrigenBuilder` has been deprecated in favor of `DOMBuilder`
    - `CadenaOrigenLocations` has been deprecated, will not be replaced
- Implement `XsltBuilderPropertyInterface` and `XsltBuilderPropertyTrait` in objects that use
  to create `CadenaOrigenBuilder` objects.
- For `CfdiCreator33` and `CfdiValidator33` will create a default DOMBuilder object if none set.
- Hydrator also receive and hydrates this by using `RequireXsltBuilderInterface`.
- `CertificadoPropertyInterface` and `CertificadoPropertyTrait` has been created.
- Improve the tests.


## Version 2.1.0 2018-01-17

- Fix `SumasConceptos` to work also with "ImpuestosLocales"
- Add elements helpers `CfdiUtils\Elements\ImpLocal10\ImpuestosLocales` to work with "ImpuestosLocales"
- Add `CfdiUtils\Certificado\CerRetriever` that works with `CfdiUtils\XmlResolver\XmlResolver` to download
  a certificate from the SAT repository
- Add a new validator `CfdiUtils\Validate\Cfdi33\Standard\TimbreFiscalDigitalSello` to validate that the SelloSAT
  is actually the signature of the Timbre Fiscal Digital. If not then the CFDI was modified
- Add a new real and valid CFDI to test, this allows `TimbreFiscalDigitalSello` to check real data and pass
- Update test with `cfdi33-valid.xml` to allow fail `TimbreFiscalDigitalSello`
- Travis: Remove xdebug for all but PHP 7.0

## Version 2.0.1 2018-01-03

- Small bugfixes thanks to Scrutinizer
- Fix some doc-blocks
- Travis: Build also with PHP 7.2

## Version 2.0.0 2018-01-01

- This library has been changed deeply.
- It can write CFDI version 3.3 using `CfdiUtils\Elements\Cfdi33` and helper class `CfdiUtils\CfdiCreator33`
- It can read CFDI files version 3.2 and 3.3 using `CfdiUtils\Cfdi`
- It can validate a CFDI
- Rely on `CfdiUtils\Nodes` to perform most operations.
- `CadenaOrigen` object have been split into two different objects: `CadenaOrigenLocation` and `CadenaOrigenBuilder`.
- New object helpers like `Elements`, `Certificado`, `PemPrivateKey` & `TimbreFiscalDigital`
- Include wiki for documentation


## Version 1.0.3 2017-10-09

- Fix a bug to read the RFC when a certificate does not contain the pattern RFC / CURP but only RFC in the
  subject x500UniqueIdentifier field


## Version 1.0.2 2017-09-28 - Thanks phpstan!

- After using `phpstan/phpstan` change the execution plan on `CadenaOrigenLocations`.
  The function previous function `throwLibXmlErrorOrMessage(string $message)` always
  throw an exception, but it was not clear in the flow of `build` method.
  Now it returns a \RuntimeException and that is thrown. So it is easy for an analysis tool
  to know that the flow has been stopped.
- Also fix case of calls `XSLTProcessor::importStylesheet` and `XSLTProcessor::transformToXml`
- Check with `isset` that `LibXMLError::$message` exists, `phpstan` was failing for this.


## Version 1.0.1 2017-09-27

- Remove Travis CI PHP nightly builds, it fails with require-dev dependencies.


## Version 1.0.0 2017-09-27

- Initial release
