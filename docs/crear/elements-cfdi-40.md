# Elementos de CFDI versión 4.0

El espacio de nombres de `CfdiUtils\Elements\Cfdi40` permite trabajar en forma más fácil
con los nodos con nombres y acciones específicas y es la base de la creación de un CFDI 4.0.

Es la implementación de [elementos](../componentes/elements.md),
que son [nodos](../componentes/nodes.md) con métodos de ayuda.

## `Comprobante`

Representa el nodo raíz `Comprobante`.
Contiene los siguientes métodos de ayuda:

- `getInformacionGlobal(): InformacionGlobal`: Crea (si no existe) y obtiene el nodo único `InformacionGlobal`.
- `addInformacionGlobal(array $attributes = []): InformacionGlobal`: Agrega y devuelve el único nodo `InformacionGlobal`.
- `addCfdiRelacionados(array $attributes = []): CfdiRelacionados`: Agrega y devuelve un nuevo nodo `CfdiRelacionados`.
- `multiCfdiRelacionados(array ...$elementAttributes): Comprobante`: Agrega nuevos nodos `CfdiRelacionados`, es una forma rápida de llamar al método `multiTraslado` múltiples veces.
- `getEmisor(): Emisor`: Crea (si no existe) y obtiene el nodo único `Emisor`.
- `addEmisor(array $attributes = []): Emisor`: Agrega y devuelve el único nodo `Emisor`.
- `getReceptor(): Receptor`: Crea (si no existe) y obtiene el nodo único `Receptor`.
- `addReceptor(array $attributes = []): Receptor`: Agrega y devuelve el único nodo `Receptor`.
- `getConceptos(): Conceptos`: Crea (si no existe) y obtiene el nodo único `Conceptos`.
- `addConcepto(array $attributes = [], array $children = []): Concepto`: Agrega y devuelve un nuevo nodo `Concepto`.
- `getImpuestos(): Impuestos`: Crea (si no existe) y obtiene el nodo único `Impuestos`.
- `addImpuestos(array $attributes = []): Impuestos`: Agrega y devuelve el único nodo `Impuestos`.
- `addTraslado(array $attributes = []): Traslado`: Agrega y devuelve un nuevo nodo `Traslado` (en `Impuestos/Traslados`).
- `multiTraslado(array ...$elementAttributes): Comprobante`: Agrega nuevos nodo `Traslado`, es una forma rápida de llamar al método `addTraslado` múltiples veces.
- `addRetencion(array $attributes = []): Retencion`: Agrega y devuelve un nuevo nodo `Retencion` (en `Impuestos/Retenciones`).
- `multiRetencion(array ...$elementAttributes): Comprobante`: Agrega nuevos nodos de `Retencion`, es una forma rápida de llamar al método `addRetencion` múltiples veces.
- `getComplemento(): Complemento`: Crea (si no existe) y obtiene el nodo único `Complemento`.
- `addComplemento(NodeInterface $children): Comprobante`: Agrega el nodo `$children` dentro del único nodo `Complemento`.
- `getAddenda(): Addenda`: Crea (si no existe) y obtiene el nodo único `Addenda`.
- `addAddenda(NodeInterface $children): Comprobante`: Agrega el nodo `$children` dentro del único nodo `Addenda`.


## `CfdiRelacionados`

Representa el nodo `Comprobante/CfdiRelacionados`.

- `addCfdiRelacionado(array $attributes = []): CfdiRelacionado`: Agrega y devuelve un nuevo nodo `CfdiRelacionado`.
- `multiCfdiRelacionado(array ...$elementAttributes): CfdiRelacionados`: Agrega nuevos nodos de `CfdiRelacionado`, es una forma rápida de llamar al método `addCfdiRelacionado` múltiples veces.


## `CfdiRelacionado`

Representa el nodo `Comprobante/CfdiRelacionados/CfdiRelacionado`.


## `Emisor`

Representa el nodo `Comprobante/Emisor`.


## `Receptor`

Representa el nodo `Comprobante/Receptor`.


## `Conceptos`

Representa el nodo `Comprobante/Conceptos`.

- `addConcepto(array $attributes = []): Concepto`: Agrega y devuelve un nuevo nodo `Conceptos`.
- `multiConcepto(array ...$elementAttributes): Conceptos`: Agrega nuevos nodos de `Concepto`, es una forma rápida de llamar al método `addConcepto` múltiples veces.


## `Concepto`

Representa el nodo `Comprobante/Conceptos/Concepto`.

- `getImpuestos(): ConceptoImpuestos`: Crea (si no existe) y obtiene el nodo único `ConceptoImpuestos`.
- `addTraslado(array $attributes = []): Traslado`: Agrega y devuelve un nuevo nodo `Traslado` (en `Impuestos/Traslados`).
- `multiTraslado(array ...$elementAttributes): Concepto`: Agrega nuevos nodos `Traslado`, es una forma rápida de llamar al método `addTraslado` múltiples veces.
- `addRetencion(array $attributes = []): Retencion`: Agrega y devuelve un nuevo nodo `Retencion` (en `Impuestos/Retenciones`).
- `multiRetencion(array ...$elementAttributes): Concepto`: Agrega nuevos nodos `Retencion`, es una forma rápida de llamar al método `addRetencion` múltiples veces.
- `addInformacionAduanera(array $attributes = []): InformacionAduanera`: Agrega y devuelve un nuevo nodo `InformacionAduanera`.
- `multiInformacionAduanera(array ...$elementAttributes): Concepto`: Agrega nuevos nodos `InformacionAduanera`, es una forma rápida de llamar al método `addInformacionAduanera` múltiples veces.
- `addCuentaPredial(array $attributes = []): CuentaPredial`: Agrega y devuelve el único nodo `CuentaPredial`.
- `getComplementoConcepto(): ComplementoConcepto`: Crea (si no existe) y obtiene el nodo único `ComplementoConcepto`.
- `addComplementoConcepto(array $attributes = [], array $children = []): ComplementoConcepto`: Agrega y devuelve el único nodo `Complementoconcepto`.
- `addParte(array $attributes = [], array $children = []): Parte`: Agrega y devuelve un nuevo nodo `Parte`.
- `multiParte(array ...$elementAttributes)`:  Agrega nuevos nodos `Parte`, es una forma rápida de llamar al método `addParte` múltiples veces.


### `ConceptoImpuestos`

La clase `ConceptoImpuestos` es igual a la clase `Impuestos` con la única diferencia de orden:

El primer nodo de `Impuestos` dentro de `Concepto` debe ser `Traslados`.
Mientras que el primer nodo de `Impuestos` dentro de `Comprobante` debe ser `Retenciones`.

- `Comprobante`
    - `Conceptos`
        - `Impuestos`
            - `Traslados`
            - `Retenciones`
    - `Impuestos`
        - `Retenciones`
        - `Traslados`

También puedes notar que no existe el método `Concepto::addImpuestos(array $attributes = [])`
porque este nodo por definición no tiene atributos propios y, por lo tanto, no es necesario.


## `InformacionAduanera`

Representa el nodo `Comprobante/Conceptos/Concepto/InformacionAduanera`
y `Comprobante/Conceptos/Concepto/Parte/InformacionAduanera`.


## `CuentaPredial`

Representa el nodo `Comprobante/Conceptos/Concepto/CuentaPredial`.


## `ComplementoConcepto`

Representa el nodo `Comprobante/Conceptos/Concepto/ComplementoConcepto`.


## `Parte`

Representa el nodo `Comprobante/Conceptos/Concepto/Parte`.

- `addInformacionAduanera(array $attributes = []): InformacionAduanera`: Agrega y devuelve un nuevo nodo `InformacionAduanera`.
- `multiInformacionAduanera(array ...$elementAttributes): Parte`: Agrega nuevos nodos `InformacionAduanera`, es una forma rápida de llamar al método `addInformacionAduanera` múltiples veces.


## `Impuestos`

Representa el nodo `Comprobante/Impuestos` y también `Comprobante/Conceptos/Concepto/Impuestos`.

- `getTraslados(): Traslados`: Crea (si no existe) y obtiene el nodo único `Traslados`.
- `getRetenciones(): Retenciones`: Crea (si no existe) y obtiene el nodo único `Retenciones`.

Aunque el nodo `Impuestos` (hijo de `Comprobante`) es diferente que el nodo `Impuestos` (hijo de `Concepto`)
se puede utilizar la misma estructura de datos, porque los cambios se dan en atributos y no en hijos.


## `Traslados`

Representa el nodo `Comprobante/Impuestos/Traslados` y `Comprobante/Conceptos/Concepto/Impuestos/Traslados`.

- `addTraslado(array $attributes = []): Traslado`: Agrega y devuelve un nuevo nodo `Traslado`.
- `multiTraslado(array ...$elementAttributes): Traslados`: Agrega nuevos nodos `Traslado`, es una forma rápida de llamar al método `addTraslado` múltiples veces.


## `Retenciones`

Representa el nodo `Comprobante/Impuestos/Retenciones` y `Comprobante/Conceptos/Concepto/Impuestos/Retenciones`.

- `addRetencion(array $attributes = []): Retencion`: Agrega y devuelve un nuevo nodo `Retencion`.
- `multiRetencion(array ...$elementAttributes): Retenciones`: Agrega nuevos nodos `Retencion`, es una forma rápida de llamar al método `addRetencion` múltiples veces.


## `Traslado`

Representa el nodo `Comprobante/Impuestos/Retenciones/Traslado`
y `Conceptos/Concepto/Impuestos/Retenciones/Traslado`.


## `Retencion`

Representa el nodo `Comprobante/Impuestos/Retenciones/Retencion`
y `Conceptos/Concepto/Impuestos/Retenciones/Retencion`.
