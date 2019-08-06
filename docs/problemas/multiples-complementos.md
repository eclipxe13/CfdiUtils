# Múltiples complementos

Según la especificación del Anexo 20 (página 12), el nodo `cfdi:Comprobante/cfdi:Complemento`
debe aparecer ninguna o una vez, es decir, tiene una cardinalidad de `(0, 1)`.

Esto se confirma con la información del XSD publicada *adentro* del Anexo 20 (página 50), donde, al no definir
la propiedad `maxOccurs` entonces es por defecto `1`.

```text
<xs:element name="Complemento" minOccurs="0">
```

Sin embargo, en el archivo de definición de esquema XSD de CFDI 3.3 ubicado en
<http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv33.xsd> aparece diferente que lo publicado en el Anexo 20:

```text
<xs:element name="Complemento" minOccurs="0" maxOccurs="unbounded">
```

Por lo tanto, el problema es que las dos definiciones técnicas publicadas por la autoridad se contradicen.
Por un lado el Anexo 20 define una cardinalidad de `(0, 1)` y el esquema XSD define `(0, N)`.

Dado lo anterior, y que no existe ninguna regla en la Matriz de errores que prevenga esta situación,
es posible que existan comprobantes con múltiples `cfdi:Complemento` y una controversia de si son o no correctos.

Como es posible que existan comprobantes con múltiples `cfdi:Complemento`, entonces es necesario
corregir todas las implementaciones que consulten los complementos porque podríamos encontrar la información
en cualquiera de los nodos.

En lugar de corregir la implementación de la lectura de CFDI, lo que **recomiendo** es colapsar los
múltiples nodos `cfdi:Complemento` en solamente uno. Esto es válido porque, a pesar de cambiar la estructura del
XML, al colapsarlos exactamente en el mismo orden, la cadena de origen será la misma.

En la utilería `CfdiUtils\Cleaner\Cleaner` existe el método `collapseComprobanteComplemento()` que realiza
esta recomendación, además, al llamar al método `clean()` también se colapsan los complementos.

* **Complemento de concepto**

El otro lugar donde se pueden poner complementos en en el nodo
`cfdi:Comprobante/cfdi:Conceptos/cfdi:Concepto/cfdi:ComplementoConcepto`,
sin embargo este nodo no tiene el mismo problema de cardinalidad, en Anexo 20 y XSD esta definido como `(0, 1)`.

* **Complemento de retención e información de pagos**

Los documentos de CFDI de Retención e información de pagos también admiten complementos en el nodo
`cfdi:Comprobante/cfdi:Complemento`,
sin embargo este nodo no tiene el mismo problema de cardinalidad, en Anexo 20 y XSD esta definido como `(0, 1)`.
