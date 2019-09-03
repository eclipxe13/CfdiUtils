# Complementos que no están implementados

No todos los complementos están disponibles para utilizarse con las clases
de ayuda `CfdiUtils\Elements`. Sin embargo, este no es motivo para no poder
agregar el nodo a la estructura del CFDI.

Recuerda que en realidad, la forma en como esta librería almacena la
información es utilizando [nodos](../componentes/nodes.md) `CfdiUtils\Nodes\Node`.
Por lo que usando esta estructura será muy fácil agregar la información.

Nodos de `<Complemento/>` y `<ComplementoConcepto/>`:
<http://www.sat.gob.mx/informacion_fiscal/factura_electronica/Paginas/complementos_factura_cfdi.aspx>

En el siguiente ejemplo voy a agregar la información necesaria del complemento de
[leyenda fiscal](http://www.sat.gob.mx/informacion_fiscal/factura_electronica/Documents/Complementoscfdi/leyendasFisc.pdf)

Y voy a partir de la **suposición** (**no real**) de que al facturar consultoría en
desarrollo de software tengo que poner una leyenda fiscal con la licencia
del software desarrollado.

```php
<?php

$creator = new \CfdiUtils\CfdiCreator33();
$comprobante = $creator->comprobante();
$comprobante->addAttributes([
    // ... atributos del comprobante
]);
// ... llenar la información del comprobante

// Creación del nodo de LeyendasFiscales
$leyendasFisc = new \CfdiUtils\Nodes\Node(
    'leyendasFisc:LeyendasFiscales', // nombre del elemento raíz
    [ // nodos obligatorios de XML y del nodo
        'xmlns:leyendasFisc' => 'http://www.sat.gob.mx/leyendasFiscales',
        'xsi:schemaLocation' => 'http://www.sat.gob.mx/leyendasFiscales'
            . ' http://www.sat.gob.mx/sitio_internet/cfd/leyendasFiscales/leyendasFisc.xsd',
        'version' => '1.0',
    ]
);

$leyendasFisc->addChild(new \CfdiUtils\Nodes\Node('leyendasFisc:Leyenda', [
    'disposicionFiscal' => 'RESDERAUTH',
    'norma' => 'Artíclo 2. Fracción IV.',
    'textoLeyenda' => 'El software desarrollado se entrega con licencia MIT'
]));

// Agregar el nodo $leyendasFisc a los complementos del CFDI
$comprobante->addComplemento($leyendasFisc);

// ... más instrucciones

$creator->saveXml('archivo_con_complemento.xml');
```

Dado el ejemplo anterior, el comprobante contendrá la siguiente información:

```xml
<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3"
    xsi:schemaLocation="http://www.sat.gob.mx/cfd/3 http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv33.xsd">
    <!-- ... nodos del comprobante ... -->
    <cfdi:Complemento>
        <!-- ... otros complementos ... -->
        <leyendasFisc:LeyendasFiscales version="1.0" xmlns:leyendasFisc="http://www.sat.gob.mx/leyendasFiscales"
            xsi:schemaLocation = "http://www.sat.gob.mx/leyendasFiscales http://www.sat.gob.mx/sitio_internet/cfd/leyendasFiscales/leyendasFisc.xsd">
            <leyendasFisc:Leyenda disposicionFiscal="RESDERAUTH" norma = "Artíclo 2. Fracción IV." textoLeyenda = "El software desarrollado se entrega con licencia MIT" />
        </leyendasFisc:LeyendasFiscales>
    </cfdi:Complemento>
</cfdi:Comprobante>
```
