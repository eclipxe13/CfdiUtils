# Elementos de Cfdi versión 33

El espacio de nombres de `CfdiUtils\Elements\Cfdi33` permite trabajar en forma más fácil
con los nodos con nombres y acciones específicas y es la base de la creación de un CFDI 3.3.

Es la implementación de [elementos](../componentes/elements.md),
que son [nodos](../componentes/nodes.md) con métodos de ayuda.

## Comprobante `cfdi:Comprobante`

Representa el nodo raiz Comprobante.
Contiene los siguientes métodos de ayuda:

- `getCfdiRelacionados(): CfdiRelacionados`: Crea (si no existe) y obtiene el nodo único CfdiRelacionados
- `addCfdiRelacionados(array $attributes = []): CfdiRelacionados`: Agrega y devuelve el único nodo CfdiRelacionados
- `addCfdiRelacionado(array $attributes = []): CfdiRelacionado`: Agrega y devuelve un nuevo nodo CfdiRelacionado
- `addCfdiRelacionados(array ...$attributes): Comprobante`: Agrega nuevos nodos CfdiRelacionado, es una forma rápida de llamar al método `addCfdiRelacionado` múltiples veces
- `getEmisor(): Emisor`: Crea (si no existe) y obtiene el nodo único Emisor
- `addEmisor(array $attributes = []): Emisor`: Agrega y devuelve el único nodo Emisor
- `getReceptor(): Receptor`: Crea (si no existe) y obtiene el nodo único Receptor
- `addReceptor(array $attributes = []): Receptor`: Agrega y devuelve el único nodo Receptor
- `getConceptos(): Conceptos`: Crea (si no existe) y obtiene el nodo único Conceptos
- `addConcepto(array $attributes = [], array $children = []): Concepto`: Agrega y devuelve un nuevo nodo Concepto
- `getImpuestos(): Impuestos`: Crea (si no existe) y obtiene el nodo único Impuestos
- `addImpuestos(array $attributes = []): Impuestos`: Agrega y devuelve el único nodo Impuestos
- `addTraslado(array $attributes = []): Traslado`: Agrega y devuelve un nuevo nodo Traslado (en Impuestos / Traslados)
- `multiTraslado(array ...$elementAttributes): Comprobante`: Agrega nuevos nodo Traslado, es una forma rápida de llamar al método `addTraslado` múltiples veces
- `addRetencion(array $attributes = []): Retencion`:  Agrega y devuelve un nuevo nodo Retencion (en Impuestos / Retenciones)
- `multiRetencion(array ...$elementAttributes): Comprobante`:  Agrega nuevos nodo Retencion, es una forma rápida de llamar al método `addRetencion` múltiples veces
- `getComplemento(): Complemento`: Crea (si no existe) y obtiene el nodo único Complemento
- `addComplemento(NodeInterface $children): Comprobante`: Agrega el nodo $children dentro del único nodo Complemento
- `getAddenda(): Addenda`: Crea (si no existe) y obtiene el nodo único Addenda
- `addAddenda(NodeInterface $children): Comprobante`: Agrega el nodo $children dentro del único nodo Addenda


## CfdiRelacionados `cfdi:CfdiRelacionados`

Representa el nodo Comprobante / CfdiRelacionados.

- `addCfdiRelacionado(array $attributes = []): CfdiRelacionado`: Agrega y devuelve un nuevo nodo CfdiRelacionado


## CfdiRelacionado `cfdi:CfdiRelacionado`

Representa el nodo Comprobante / CfdiRelacionados / CfdiRelacionado.


## Emisor `cfdi:Emisor`

Representa el nodo Comprobante / Emisor.


## Receptor `cfdi:Receptor`

Representa el nodo Comprobante / Receptor.


## Conceptos `cfdi:Conceptos`

Representa el nodo Comprobante / Conceptos.

- `addConcepto(array $attributes = []): Concepto`: Agrega y devuelve un nuevo nodo Conceptos


## Conceptos `cfdi:Concepto`

Representa el nodo Comprobante / Conceptos / Concepto.

- `getImpuestos(): ConceptoImpuestos`: Crea (si no existe) y obtiene el nodo único ConceptoImpuestos
- `addTraslado(array $attributes = []): Traslado`: Agrega y devuelve un nuevo nodo Traslado (en Impuestos / Traslados)
- `multiTraslado(array ...$elementAttributes): Concepto`: Agrega nuevos nodo Traslado, es una forma rápida de llamar al método `addTraslado` múltiples veces
- `addRetencion(array $attributes = []): Retencion`:  Agrega y devuelve un nuevo nodo Retencion (en Impuestos / Retenciones)
- `multiRetencion(array ...$elementAttributes): Concepto`:  Agrega nuevos nodo Retencion, es una forma rápida de llamar al método `addRetencion` múltiples veces
- `addInformacionAduanera(array $attributes = []): InformacionAduanera`: Agrega y devuelve un nuevo nodo InformacionAduanera
- `multiInformacionAduanera(array ...$elementAttributes): Concepto`: Agrega nuevos nodo InformacionAduanera, es una forma rápida de llamar al método `addInformacionAduanera` múltiples veces
- `addCuentaPredial(array $attributes = []): CuentaPredial`: Agrega y devuelve el único nodo CuentaPredial
- `getComplementoConcepto(): ComplementoConcepto`: Crea (si no existe) y obtiene el nodo único ComplementoConcepto
- `addComplementoConcepto(array $attributes = [], array $children = []): ComplementoConcepto`: Agrega y devuelve el único nodo Complementoconcepto
- `addParte(array $attributes = [], array $children = []): Parte`: Agrega y devuelve un nuevo nodo Parte
- `multiParte(array ...$elementAttributes)`:  Agrega nuevos nodo Parte, es una forma rápida de llamar al método `addParte` múltiples veces


### ConceptoImpuestos

La clase `ConceptoImpuestos` es igual a la clase `Impuestos` con la única diferencia de orden:

El primer nodo de `Impuestos` dentro de `Concepto` debe ser `Traslados`.
Mientras que el primer nodo de `Impuestos` dentro de `Comprobante` debe ser `Retenciones`.

- Comprobante
    - Conceptos
        - Impuestos
            - Traslados
            - Retenciones
    - Impuestos
        - Retenciones
        - Traslados

También puedes notar que no existe el método `Concepto::addImpuestos(array $attributes = [])`
porque este nodo por definición no tiene atributos propios y por lo tanto no es necesario.


## InformacionAduanera `cfdi:InformacionAduanera`

Representa el nodo Comprobante / Conceptos / Concepto / InformacionAduanera
y Comprobante / Conceptos / Concepto / Parte / InformacionAduanera.


## CuentaPredial `cfdi:CuentaPredial`

Representa el nodo Comprobante / Conceptos / Concepto / CuentaPredial.


## ComplementoConcepto `cfdi:ComplementoConcepto`

Representa el nodo Comprobante / Conceptos / Concepto / ComplementoConcepto.


## Parte `cfdi:Parte`

Representa el nodo Comprobante / Conceptos / Concepto / Parte.

- `addInformacionAduanera(array $attributes = []): InformacionAduanera`: Agrega y devuelve un nuevo nodo InformacionAduanera
- `multiInformacionAduanera(array ...$elementAttributes): Parte`: Agrega nuevos nodo InformacionAduanera, es una forma rápida de
  llamar al método `addInformacionAduanera` múltiples veces


## Impuestos `cfdi:Impuestos`

Representa el nodo Comprobante / Impuestos y también Comprobante / Conceptos / Concepto / Impuestos.

- `getTraslados(): Traslados`: Crea (si no existe) y obtiene el nodo único Traslados.
- `getRetenciones(): Retenciones`: Crea (si no existe) y obtiene el nodo único Retenciones.

Aunque el nodo impuestos (hijo de comprobante) es diferente que el nodo impuestos (hijo de concepto)
se puede utilizar la misma estructura de datos porque los cambios se dan a nivel de atributos y no de hijos.


## Traslados `cfdi:Traslados`

Representa el nodo Comprobante / Impuestos / Traslados y Comprobante / Conceptos / Concepto / Impuestos / Traslados.

- `addTraslado(array $attributes = []): Traslado`: Agrega y devuelve un nuevo nodo Traslado.
- `multiTraslado(array ...$elementAttributes): Traslados`: Agrega nuevos nodo Traslado, es una forma rápida de llamar al método `addTraslado` múltiples veces


## Retenciones `cfdi:Retenciones`

Representa el nodo Comprobante / Impuestos / Retenciones y Comprobante / Conceptos / Concepto / Impuestos / Retenciones.

- `addRetencion(array $attributes = []): Retencion`: Agrega y devuelve un nuevo nodo Retencion.
- `multiRetencion(array ...$elementAttributes): Retenciones`:  Agrega nuevos nodo Retencion, es una forma rápida de llamar al método `addRetencion` múltiples veces


## Traslado `cfdi:Traslado`

Representa el nodo Comprobante / Impuestos / Retenciones / Traslado
y Conceptos / Concepto / Impuestos / Retenciones / Traslado.


## Retencion `cfdi:Retencion`

Representa el nodo Comprobante / Impuestos / Retenciones / Retencion
y Conceptos / Concepto / Impuestos / Retenciones / Retencion.
