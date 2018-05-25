# Backward compatibility breaks (not released yet), plan for version 3.0
- Remove deprecated classes:
    - `\CfdiUtils\CadenaOrigen\CadenaOrigenBuilder`
    - `\CfdiUtils\CadenaOrigen\DefaultLocations`
    - `\CfdiUtils\CadenaOrigen\CadenaOrigenLocations`
- Remove `\CfdiUtils\PemPrivateKey\PemPrivateKey::isOpened` to `\CfdiUtils\PemPrivateKey\PemPrivateKey::isOpen`
- Remove `static` methods from `\CfdiUtils\CfdiVersion`, instead  create an instance of the class  
- Remove `static` methods from `\CfdiUtils\TimbreFiscalDigital\TfdVersion`, instead create an instance of the class  


# Version 2.5.0 2018-05-24
- Add validations for `http://www.sat.gob.mx/Pagos` at namespace `\CfdiUtils\Validate\Cfdi33\RecepcionPagos`
    This is a big change that includes more than 50 validators that work in cascade.
    It implements almost all of the validations from the SAT "Matriz de errores".
- Append it to `\CfdiUtils\Validate\MultiValidatorFactory`
- Remove non existent validators discovery `Cfdi33/Timbre`
- Move logic of version discovery to a new class, change `CfdiVersion` and `TfdVersion` to implement this logic
- Deprecate `static` methods from `\CfdiUtils\CfdiVersion`, instead create an instance of the class
- Deprecate `static` methods from `\CfdiUtils\TimbreFiscalDigital\TfdVersion`, instead create an instance of the class  
- Fix deprecation notices existent docblocks
- Update deprecation notice to README
- Replace TODO with a more explained version


# Version 2.4.6 2018-05-24
- Fix validation of TIPOCOMP06, it was not checking correctly.
- Fix bug in validators that does not respect when the resolver does not have local path:
    - `CfdiUtils\Validate\Cfdi33\Standard\TimbreFiscalDigitalSello`
    - `CfdiUtils\Validate\Cfdi33\Xml\XmlFollowSchema`
- Fix bug when removing a `schemaLocation` attribute in `CfdiUtils\Cleaner\Cleaner`
- Refactor `CfdiUtils\ConsultaCfdiSat\WebService::request` and move the SOAP call
  to a protected method, this allow better testing of the class by mocking the call
- In `CfdiUtils\PemPrivateKey\PemPrivateKey` deprecate `isOpened` and add `isOpen`
- In `CfdiUtils\Cfdi::getNode` use `XmlNodeUtils` instead of `XmlNodeImporter`
- In `CfdiUtils\Cfdi::newFromString` create `new self` instead of `new static`.
  If using `new static` the constructor might be different and it would fail.
- In `CfdiUtils\CfdiVersion::fromXmlString` it no longer create a Cfdi object,
  it will just create a `DOMObject` and delegate to
  `fromDOMDocument` as in `TfdVersion`.
- Remove `CfdiUtils\Elements\Pagos10\Pago::multiImpuestos`,
  it should never exists and must not have any use case.
- Improve testing on:
    - `CfdiUtils\Elements\Pagos10\Pagos`
    - `CfdiUtils\Validate\Cfdi33\Standard\ConceptoImpuestos`
- Improve docblocks and fix typos in several files
- Add new parameter to development script `tests/validate.php`:
  `--no-cache` that tell resolver to not use local cache.
- Improve travis disabling xdebug always and only use it in phpunit code coverage

# Version 2.4.5 2018-05-12
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
- Add docblocks to `CfdiUtils\Cfdi`
- Building:
    - Add .phplint.yml to export-ignore (standard line)
    - Travis-CI: Declare `FULL_BUILD_PHP_VERSION` for easy understanding
- Add more dependences: `ext-dom`, `ext-xsl`, `ext-simplexml`, `ext-mbstring`

# Version 2.4.4 2018-05-11
- FIX: Unable to load a PEM file using filename on windows (Closes #33)
- Do not use bcmath function to convert from decimal to hexadecimal the serial number of a certificate

# Version 2.4.3 2018-04-26
- FIX: The attribute `cfdi:Comprobante@Descuento` must not be deleted if any attribute
  `cfdi:Comprobante/cfdi:Conceptos/cfdi:Concepto@Descuento` exists. (Closes: #50)
- FIX: When validating a CFDI, the validator `CfdiUtils\Validate\Cfdi33\Standard\SelloDigitalCertificado`
  was too hard. In common practice, must allow `-`, `-` and compare without special chars like `ü`. (Closes #51)
- Add a **development** script `tests/validate.php` to validate existing files.
  WARNING: This can change at any time! Do not depend on this file or its results!

# Version 2.4.2 2018-04-23
- Fix `\CfdiUtils\Nodes\XmlNodeExporter::export`, it was not appending root element to xml document.
- Allow `\CfdiUtils\Nodes\XmlNodeUtils::nodeToXmlString` to export including xml header `<?xml ... ?>`.
  Default behavior is to not include xml header, it remains unchanged.
- Explicitly `\CfdiUtils\CfdiCreator33::asXml()` returns the string with xml header.
- By default, `\DOMDocument` objects are created with version 1.0 and encoding UTF-8.
- Add tests to validate previous changes.

# Version 2.4.1 2018-04-11
- Fix `\CfdiUtils\Certificado\Certificado` when reading serial number.
  - Use `serialNumberHex` if available, if not then use `serialNumber` and convert to hex.
- Move serial number string conversion to class `\CfdiUtils\Certificado\SerialNumber`.
  This class is not for public use but for use inside `Certificate`.

# Version 2.4.0 2018-02-08
- Add the feature to order the children nodes for a `CfdiUtils\Nodes\Nodes` object.
  This feature is used in the namespace `CfdiUtils\Elements` to set the correct order of the
  children nodes without worry about the creation order.
- Add `CfdiUtils\Elements\Addenda` helper class.
- Add `CfdiUtils\Elements\Pagos10` namespace for "complemento de pagos 1.0".
- Add `CfdiUtils\Cleaner\Cleaner` utility class that allows to remove `cfdi:Addenda`,
  non SAT nodes, non SAT namespaces and unused namespaces.
- Build: The project no longer depends on `jakub-onderka/php-parallel-lint`,
  now uses `overtrue/phplint` that does the same task but stores a cache.

# Version 2.3.2 2018-01-29
- Fix how total is formatted in the expression of `\CfdiUtils\ConsultaCfdiSat\RequestParameters`
    - Version 3.2 was removing zero trailing decimals instead of using 6 fixed chars
    - Version 3.3 was not using 1 leading zero (for integers) and 1 trailing zero (for decimals)
- On method `\CfdiUtils\Certificado\NodeCertificado::obtain()` change logic
  and throw exception if temporary file cannot be created

# Version 2.3.1 2018-01-25
- Add elements helpers `CfdiUtils\Elements\Tfd11\TimbreFiscalDigital` to work with "TimbreFiscalDigital"

# Version 2.3.0 2018-01-25
- Add a client `\CfdiUtils\ConsultaCfdiSat\WebService` for the SAT WebService
  https://consultaqr.facturaelectronica.sat.gob.mx/ConsultaCFDIService.svc?singleWsdl
- Fix bug, must use `children()` method instead of `children` property.
  Did not appears before because the variable using the property was always
  a `Node` but other implementation of `NodeInterface` would cause this to break.
- Add a lot of fixes in docblocks to move `@param $var` to `@param type $var`.
- Add extensions requirements to composer.json: libxml, openssl & soap.
- Upgrade `phpstan/phpstan-shim` to version 0.9.1, the not-simple-to-see bug fixed
  in this version was found by `phpstan` - https://github.com/phpstan/phpstan  


# Version 2.2.0 2018-01-24
- Refactor namespace `\CfdiUtils\CadenaOrigen` (backwards compatible):
    - Instead of one only xslt builder now it includes:
        - `DOMBuilder`: Uses the regular PHP based method
        - `GenkgoXslBuilder`: Uses the library genkgo/xsl xslt version 2 library
        - `SaxonbCliBuilder`: Uses the command line saxonb-xslt command
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


# Version 2.1.0 2018-01-17
- Fix `SumasConceptos` to work also with "ImpuestosLocales"
- Add elements helpers `CfdiUtils\Elements\ImpLocal10\ImpuestosLocales` to work with "ImpuestosLocales"
- Add `CfdiUtils\Certificado\CerRetriever` that works with `CfdiUtils\XmlResolver\XmlResolver` to download
  a certificate from the SAT repository
- Add a new validator `CfdiUtils\Validate\Cfdi33\Standard\TimbreFiscalDigitalSello` to validate that the SelloSAT
  is actually the signature of the Timbre Fiscal Digital. If not then the CFDI was modified
- Add a new real and valid CFDI to test, this allow `TimbreFiscalDigitalSello` to check real data and pass
- Update test with `cfdi33-valid.xml` to allow fail `TimbreFiscalDigitalSello`
- Travis: Remove xdebug for all but PHP 7.0

# Version 2.0.1 2018-01-03
- Small bugfixes thanks to scrutinizer-ci.com
- Fix some docblocks
- Travis: Build also with PHP 7.2

# Version 2.0.0 2018-01-01
- This library has been changed deeply.
- It can write CFDI version 3.3 using `CfdiUtils\Elements\Cfdi33` and helper class `CfdiUtils\CfdiCreator33`
- It can read CFDI files version 3.2 and 3.3 using `CfdiUtils\Cfdi`
- It can validate a CFDI
- Rely on `CfdiUtils\Nodes` to perform most operations.
- `CadenaOrigen` object have been split into two different objects: `CadenaOrigenLocation` and `CadenaOrigenBuilder`.
- New object helpers like `Elements`, `Certificado`, `PemPrivateKey` & `TimbreFiscalDigital`
- Include wiki for documentation


# Version 1.0.3 2017-10-09
- Fix a bug to read the RFC when a certificate does not contain the pattern RFC / CURP but only RFC in the
  subject x500UniqueIdentifier field


# Version 1.0.2 2017-09-28 - Thanks phpstan!
- After using `phpstan/phpstan` change the execution plan on `CadenaOrigenLocations`.
  The function previous function `throwLibXmlErrorOrMessage(string $message)` always
  throw an exception but it was not clear in the flow of `build` method.
  Now it returns a \RuntimeException and that is thrown. So it is easy for an analysis tool
  to know that the flow has been stopped.
- Also fix case of calls `XSLTProcessor::importStylesheet` and `XSLTProcessor::transformToXml`
- Check with `isset` that `LibXMLError::$message` exists, phpstan was failing for this.


# Version 1.0.1 2017-09-27
- Remove Travis CI PHP nightly builds, it fail with require-dev dependencies.


# Version 1.0.0 2017-09-27
- Initial release
