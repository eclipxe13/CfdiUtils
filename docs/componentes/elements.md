# Estructura de datos Elements

El espacio de nombres `CfdiUtils\Elements` es una especialización de [`CfdiUtils\Nodes`](nodes.md).

Se trata solo de una estructura de datos, no caigas en la tentación de insertar lógica más allá de la propia estructura.

Todo *elemento* es un *nodo*, entonces todos los métodos y propiedades de `NodeInterface` están presentes.

Entonces, para escribir atributos se recomienda usar la forma de arreglo, por ejemplo:
`$comprobante['Descuento'] = '1234.56'` para establecer el atributo "Descuento" a "1234.56".

Cualquier elemento debe cumplir con la interfaz `CfdiUtils\Elements\Common\ElementInterface`
que es una extensión de `CfdiUtils\Nodes\NodeInterface` y agrega:

- `getElementName(): string`: Devuelve el nombre del elemento, como `cfdi:Complemento`
- `getFixedAttributes(): array`: Establece la lista de nodos predefinidos al crearse (útil para Complementos y Comprobante)
- `getChildrenOrder(): array`: establece el orden de los nodos hijos

En última instancia, un *elemento* (`ElementInterface`) es un *nodo* (`NodeInterface`)
por lo que puedes utilizar a bajo nivel todo el poder de los nodos para trabajar con esta estructura de datos.


## Nomenclatura genérica

Los elementos deberían seguir esta nomenclatura genérica para nombrar sus métodos.


### Prefijo `get*`

La nomenclatura con el prefijo `get*` se escribe en la forma `ElementoPadre::getElementoHijo(): ElementoHijo`
y se espera devolver una única instancia de `ElementoHijo`. Si la instancia no existe entonces se crea.


### Prefijo `add*`

La nomenclatura con el prefijo `add*` se escribe la forma `ElementoPadre::addElementoHijo($attributes): ElementoHijo`
y se espera crear una instancia de `ElementoHijo` con los atributos datos, agregarla a los hijos de `ElementoPadre`
y la instancia de `ElementoHijo` creada.

Cuando se utiliza `add*` hay dos comportamientos esperados:

- Si solo debe haber un hijo de un determinado tipo, entonces no se crea uno nuevo y se ocupa el existente.
- Si puede haber más de un hijo de un determinado tipo, entonces se crea uno nuevo y se agrega a los hijos.

Por eso, como solo debe haber un nodo emisor dentro de un comprobante,
entonces `Comprobante::addEmisor(['RegimenFiscal' => '601'])` tiene este comportamiento:

- Se obtiene el elemento `Emisor`, si no existe se crea uno vacío.
- Se escriben los atributos pasados al elemento obtenido.
- Se devuelve el elemento.

Por el contrario, como puede haber varios Cfdi Relacionados, entonces
`CfdiRelacionados::addCfdiRelacionado(['UUID' => $uuid])` tiene este comportamiento:

- Se crea un elemento de tipo `CfdiRelacionado` con los atributos pasados.
- Se agrega el elemento recién creado a los hijos de `CfdiRelacionados`.
- Se devuelve el elemento creado.

Existe un caso donde lo que se espera entregar como atributo al prefijo `add*` es en realidad un hijo.
Esto sucede en `addComplemento` y `addAddenda`.


### Prefijo `multi*`

La nomenclatura con el prefijo `multi*` se escribe la forma `ElementoPadre::multiElementoHijo(...$attributes): ElementoPadre`
y se espera crear múltiples una instancia de `ElementoHijo` con los atributos datos, agregarla a los hijos de `ElementoPadre`
y la instancia de `ElementoPadre` creada.

Otra forma de decirlo: es como los métodos `add*` pero se le pueden mandar varios arreglos de atributos y se creará un elemento para cada parámetro enviado.

Por lo anterior, `CfdiRelacionados::multiCfdiRelacionado([ ['UUID' => $uuid1], ['UUID' => $uuid2] ])` agregará dos hijos
y devolverá la misma instancia del objeto llamado.
