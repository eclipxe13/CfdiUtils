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
