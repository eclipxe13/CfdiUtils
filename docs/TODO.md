# eclipxe/CfdiUtils To Do List

- [ ] Solve the problem of sorting node childrens
- [ ] For the \CfdiUtils\ConsultaCfdiSat\WebService
      - [ ] Find a way to not depend on PHP SOAP but in something that can do async
            request and configure the connection like setting a proxy,
            maybe depending on guzzle.
      - [ ] Create a cache of the WSDL page (?)
- [ ] Create validation rules to detect errors on CFDI
    - [X] Validate Matriz de errores del CFDI version 3.3
    - [X] Validate SelloSAT at TimbreFiscalDigital
    - [ ] Validate ComplementoComercioExterior
- [ ] Add a pretty command line utility to validate cfdi files
- [X] Full code coverage on `CfdiUtils\Elements`
- [ ] Implement catalogs published by SAT
    - Doubt: This catalogs may be on a database or hardcoded ?
