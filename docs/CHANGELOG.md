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
