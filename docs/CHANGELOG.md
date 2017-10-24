# Version 2.0.0 2017-10-24
- This library has been changed deeply.
- It can write CFDI version 3.3 using `CfdiUtils\Elements\Cfdi33` and helper class `CfdiUtils\CfdiCreator33`
- It can read CFDI files version 3.2 and 3.3 using `CfdiUtils\Cfdi`
- It can validate a CFDI
- Rely on `CfdiUtils\Nodes` to perform most operations.
- `CadenaOrigen` object have been split into two different objects: `CadenaOrigenLocation` and `CadenaOrigenBuilder`.
- 
 
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
