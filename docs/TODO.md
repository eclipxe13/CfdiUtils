# eclipxe/CfdiUtils To Do List

### Prepare for version 3

Version 3 will deprecate some classes and methods, it may be good point of start to migrate the project
to a new namespace `PhpCfdi\CfdiUtils` that is managed by

#### Deprecations:


### CfdiVersion & TfdVersion

The classes `CfdiUtils\CfdiVersion` and `CfdiUtils\TimbreFiscalDigital\CfdiVersion`
share the same logic and methos. They are detected as code smell and it would be better
to have a single class to implement the logic and extend that class to provide configuration.


### Status of a Cfdi using the SAT webservice

This is already implemented in `CfdiUtils\ConsultaCfdiSat\WebService` but there are two
ideas than need a solution:

* Find a way to not depend on PHP SOAP but in something that can do async
  request and configure the connection like setting a proxy, maybe depending on guzzle.

* Create a cache of the WSDL page (?)


### Validation rules for Pagos

The validation rules for "Complemento de Recepción de pagos" are included since version 2.6 but
they require more cases of use and a better understanding of the rules published by SAT.


### Validation rules for ComercioExterior

Create validation rules for "Complemento de Comercio Exterior"


## Ideas not to be implemented

### Add a pretty command line utility to validate cfdi files

This will be implemented on a different project, for testing proposes there is the file `tests/validate.php`

  
### Implement catalogs published by SAT

This will be implemented on a different project.

