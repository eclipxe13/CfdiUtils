# Estructura de datos Elements

Elements es una especialización de Nodes.
Es solo una estructura de datos, evita la tentación de insertar lógica más allá de la propia estructura.

Como todo elemento es un nodo entonces todos los métodos y propiedades de `Node` están presentes.
Por lo tanto, para escribir atributos se recomienda usar la forma de arreglo, por ejemplo:
`$comprobante['Descuento'] = '1234.56'` para establecer el atributo "Descuento" a "1234.56".

Cualquier elemento debe cumplir con la interfaz
`CfdiUtils\Elements\Common\ElementInterface` que es una extensión de `CfdiUtils\Nodes\NodeInterface` y agrega:
- `getElementName(): string`: Devuelve el nombre del elemento, como `cfdi:Complemento`
- `getFixedAttributes(): array`: Establece la lista de nodos predefinidos al crearse (útil para Complementos y Comprobante)

En última instancia, un elemento (`ElementInterface`) es un nodo (`NodeInterface`) por lo que puedes utilizar a bajo nivel
todo el poder de los nodos para trabajar con esta estructura de datos.
En el código `Elements` contiene codigo muy simple porque toda la lógica radica en `Nodes`.


## Cfdi33

El espacio de nombres de `CfdiUtils\Elements\Cfdi33` permite trabajar en forma más fácil
con los nodos con nombres y acciones específicas y es la base de la creación de un CFDI 3.3.

### Comprobante `cfdi:Comprobante`

Representa el nodo raiz Comprobante.
Contiene los siguientes métodos de ayuda:
- `getCfdiRelacionados(): CfdiRelacionados`: Crea u obtiene el nodo único CfdiRelacionados
- `addCfdiRelacionado(array $attributes = []): CfdiRelacionado`: Agrega y devuelve un nuevo nodo CfdiRelacionado
- `getEmisor(): Emisor`: Crea (si no existe) y obtiene el nodo único Emisor
- `addEmisor(array $attributes = []): Emisor`: Agrega y devuelve el único nodo Emisor
- `getReceptor(): Receptor`: Crea (si no existe) y obtiene el nodo único Receptor
- `addReceptor(array $attributes = []): Receptor`: Agrega y devuelve el único nodo Receptor
- `getConceptos(): Conceptos`: Crea (si no existe) y obtiene el nodo único Conceptos
- `addConcepto(array $attributes = [], array $children = []): Concepto`: Agrega y devuelve un nuevo nodo Concepto
- `getImpuestos(): Impuestos`: Crea (si no existe) y obtiene el nodo único Impuestos
- `addTraslado(array $attributes = []): Traslado`: Agrega y devuelve un nuevo nodo Traslado (en Impuestos / Traslados)
- `multiTraslado(array ...$elementAttributes): Comprobante`: Agrega nuevos nodo Traslado, es una forma rápida de llamar al método `addTraslado` múltiples veces
- `addRetencion(array $attributes = []): Retencion`:  Agrega y devuelve un nuevo nodo Retencion (en Impuestos / Retenciones)
- `multiRetencion(array ...$elementAttributes): Comprobante`:  Agrega nuevos nodo Retencion, es una forma rápida de llamar al método `addRetencion` múltiples veces


### CfdiRelacionados `cfdi:CfdiRelacionados`

Representa el nodo Comprobante / CfdiRelacionados.
- `addCfdiRelacionado(array $attributes = []): CfdiRelacionado`: Agrega y devuelve un nuevo nodo CfdiRelacionado


### CfdiRelacionado `cfdi:CfdiRelacionado`

Representa el nodo Comprobante / CfdiRelacionados / CfdiRelacionado.


### Emisor `cfdi:Emisor`

Representa el nodo Comprobante / Emisor.


### Receptor `cfdi:Receptor`

Representa el nodo Comprobante / Receptor.


### Conceptos `cfdi:Conceptos`

Representa el nodo Comprobante / Conceptos.
- `addConcepto(array $attributes = []): Concepto`: Agrega y devuelve un nuevo nodo Conceptos


### Conceptos `cfdi:Concepto`

Representa el nodo Comprobante / Conceptos / Concepto.
- `getImpuestos(): Impuestos`: Crea (si no existe) y obtiene el nodo único Impuestos
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


### InformacionAduanera `cfdi:InformacionAduanera`

Representa el nodo Comprobante / Conceptos / Concepto / InformacionAduanera
y Comprobante / Conceptos / Concepto / Parte / InformacionAduanera.


### CuentaPredial `cfdi:CuentaPredial`

Representa el nodo Comprobante / Conceptos / Concepto / CuentaPredial.


### ComplementoConcepto `cfdi:ComplementoConcepto`

Representa el nodo Comprobante / Conceptos / Concepto / ComplementoConcepto.


### Parte `cfdi:Parte`

Representa el nodo Comprobante / Conceptos / Concepto / Parte.
- `addInformacionAduanera(array $attributes = []): InformacionAduanera`: Agrega y devuelve un nuevo nodo InformacionAduanera
- `multiInformacionAduanera(array ...$elementAttributes): Parte`: Agrega nuevos nodo InformacionAduanera, es una forma rápida de llamar al método `addInformacionAduanera` múltiples veces


### Impuestos `cfdi:Impuestos`

Representa el nodo Comprobante / Impuestos y Comprobante / Conceptos / Concepto / Impuestos.
- `getTraslados(): Traslados`: Crea (si no existe) y obtiene el nodo único Traslados.
- `getRetenciones(): Retenciones`: Crea (si no existe) y obtiene el nodo único Retenciones.

Aunque el nodo impuestos hijo de comprobante es diferente que el nodo impuestos hijo de concepto se puede utilizar
la misma estructura de datos porque no está restringida a sus atributos o hijos. 


### Traslados `cfdi:Traslados`

Representa el nodo Comprobante / Impuestos / Traslados y Comprobante / Conceptos / Concepto / Impuestos / Traslados.
- `addTraslado(array $attributes = []): Traslado`: Agrega y devuelve un nuevo nodo Traslado.
- `multiTraslado(array ...$elementAttributes): Traslados`: Agrega nuevos nodo Traslado, es una forma rápida de llamar al método `addTraslado` múltiples veces


### Retenciones `cfdi:Retenciones`

Representa el nodo Comprobante / Impuestos / Retenciones y Comprobante / Conceptos / Concepto / Impuestos / Retenciones.
- `addRetencion(array $attributes = []): Retencion`: Agrega y devuelve un nuevo nodo Retencion.
- `multiRetencion(array ...$elementAttributes): Retenciones`:  Agrega nuevos nodo Retencion, es una forma rápida de llamar al método `addRetencion` múltiples veces


### Traslado `cfdi:Traslado`

Representa el nodo Comprobante / Impuestos / Retenciones / Traslado
y Conceptos / Concepto / Impuestos / Retenciones / Traslado.


### Retencion `cfdi:Retencion`

Representa el nodo Comprobante / Impuestos / Retenciones / Retencion
y Conceptos / Concepto / Impuestos / Retenciones / Retencion.
