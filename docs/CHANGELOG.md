# Version 1.0.2 2017-09-28 - Thanks phpstan!
- After using `phpstan/phpstan` change the execution plan on `CadenaOrigen`.
  The function previous function `throwLibXmlErrorOrMessage(string $message)` always
  throw an exception but it was not clear in the flow of `build` method.
  Now it returns a \RuntimeException and that is thrown. So it is easy for an analysis tool
  to know that the flow has been stopped. 
- Also fix case of calls `XSLTProcessor::importStylesheet` and `XSLTProcessor::transformToXml`
- Check with `isset` that LibXMLError::$message exists, phpstan was failing for this.


# Version 1.0.1 2017-09-27
- Remove Travis CI PHP nightly builds, it fail with require-dev dependencies.


# Version 1.0.0 2017-09-27
- Initial release
