# Estructura de datos Node

Esta estructura de datos permite administrar en memoria una colección de nodos con hijos de tipo nodo
donde cada uno tiene una colección de atributos. Los nodos no tienen referencia de padre.


## Objeto `CfdiUtils\Nodes\Node`

Esta es la estructura básica. Un nodo debe tener un nombre y esta propiedad no se puede cambiar.
Su contructor admite tres parámetros:

- `string $name`: Nombre del nodo, se eliminan espacios en blanco al inicio y al final, no permite vacíos.
- `string[] $attributes`: arreglo de elementos clave/valor que serán importados como atributos
- `string[] $nodes`: arreglo de elementos `Node` que serán importados como nodos hijo


### Atributos de nodos `attributes(): CfdiUtils\Nodes\Attributes`

Se accesa a sus atributos utilizando la forma de arreglos de php siguiendo estas reglas básicas:

- La lectura de un nodo siempre devuelve una cadena de caracteres aunque el atributo no exista.
- La escritura de un nodo es siempre con una cadena de caracteres, también puede ser un objeto
  que implemente el método `__toString()`

Los atributos se manejan con una colección de tipo `Attributes` y se pueden obtener usando el método
`attributes()`.

```php
<?php
use \CfdiUtils\Nodes\Node;

// creación de un nodo con atributos
$node = new Node('root', [
    'id' => '1'
]);

echo $node['id']; // '1'
echo $node['no-existe']; // cadena de caracteres vacía
echo isset($node['no-existe']) ? 'sí' : 'no'; // no
$node['atributo'] = 'valor'; // establece el valor
unset($node['foo']); // elimina el atributo 'foo'

// recorrer la colección de atributos
foreach ($node->attributes() as $attributeName => $attributeValue) {
    echo $attributeName, ': ', $attributeValue;
}
```


### Nodos (`children(): CfdiUtils\Nodes\Nodes`)

Los nodos hijos se manejan a través de una colección de nodos `Nodes`.
Se puede acceder al objeto `Nodes` usando el método `children()`.

Cuanto se itera el objeto en realidad se está iterando sobre la colección de nodos.

La clase `Node` tiene estos métodos de ayuda que sirven para trabajar directamente sobre la colección Nodes:

- iterador: el `foreach` se realiza sobre la colección de nodos.
- `addChild(Node $node)`: agrega un nodo en la colección de nodos.


### Métodos de búsqueda

Un objeto de tipo `Node` tiene los siguientes métodos para poder interactuar con sus hijos:

- `searchAttribute(string ...$searchPath): string`: Devuelve el valor de un atributo según una búsqueda.
- `searchNode(string ...$searchPath): Node|NULL`: Devuelve un objeto de tipo `Node` o `NULL` según una búsqueda.
- `searchNodes(string ...$searchPath): Nodes`: Devuelve un objeto de tipo `Nodes` según una búsqueda.

La búsqueda se refiere a los nombres de los hijos, por ejemplo:
`$node->searchNode('orden', 'articulos', 'articulo')`
busca dentro de los hijos de `$node` el **primer** nodo llamado `orden`,
si existe busca el primer nodo dentro de `orden` que se llame `articulos`,
si existe busca el primer nodo dentro de `articulos` que se llame `articulo`,
si existe devuelve dicho elemento.
Si alguno no existiera entonces devuelve un valor nulo.

De la misma forma, `$node->searchNodes('orden', 'articulos', 'articulo')` devuelve una colección
con los elementos llamados `articulo` que están dentro de `orden/articulos`.

Nota: Si se agrega o elimina un elemento a colección devuelta por `searchNodes`, dicho nodo no se agregará o modificará en el padre.
Esto es porque la colección devuelta por `searchNodes` es simplemente una agrupación adicional a la estructura principal
de nodos. Sin embargo, los hijos de esta colección sí hacen referencia a los nodos de la estructura principal,
por lo que cualquier cambio a los hijos sí será reflejado.

Cuando se require obtener solamente un valor de atributo de un nodo se puede utilizar `searchAtribute`,
la búsqueda se comporta igual que `searchNode`. Si el nodo buscado no existe devolverá una cadena vacía.
Por ejemplo: `$node->searchNodes('orden', 'articulos', 'nota')` devolverá el atributo `nota` de `orden/articulos`.


## Clase CfdiUtils\Nodes\Nodes

Esta clase representa una colección de `Node`. Al iterar en el objeto se recorrerá cada uno de los nodos.

Se pueden hacer las operaciones básicas como:
`add(Node $node)`,
`indexOf(Node $node)`,
`remove(Node $node)`,
`removeAll()`,
`exists(Node $node)`,
`get(int $index)`.

Adicionalmente se pueden usar los métodos:
`firstNodeWithName(string name): Node|null`,
`getNodesByName(string $nodeName): Nodes` y
`importFromArray(Nodes[] $nodes)`


## Clase CfdiUtils\Nodes\Attributes

Esta clase representa una colección de atributos identificados por nombre.
Al iterar en el objeto se devolverá cada uno de los attributos en forma de clave/valor.

Adicionalmente esta clase permite el uso de acceso como arreglo, por lo que permite:

- `$attributes[$name]` como equivalente de `$attributes->get($name)`
- `$attributes[$name] = $value` como equivalente de `$attributes->set($name, $value)`
- `isset($attributes[$name])` como equivalente de `$attributes->exists($name)`
- `unset($attributes[$name])` como equivalente de `$attributes->remove($name)`

Se pueden hacer las operaciones básicas como:

- `get(string $name): string`
- `set(string $name, string $value)`
- `remove(string $name)`
- `removeAll()`
- `exists(string $name)`


## XmlNodeUtils

Esta es una clase de utilerías que contiene métodos estáticos que permiten crear estructuras de nodos desde XML
y generar XML a partir de los nodos. Recuerde que los nodos solo pueden almacenar atributos y nodos hijos.

Actualmente permite exportar e importar a/desde: `DOMDocument`, `DOMElement`, `SimpleXmlElement` y `string` (con contenido válido).

**Advertencias:**

- Los nodos no tienen campo de contenido y no son una reescritura fiel de DOM.
- Los nodos solo contienen atributos e hijos.
- Importar XML que no siga la estructura de atributos/hijos exclusivamente puede resultar en pérdida de datos.
