# Complemento de Nómina 1.2 revisión B

El espacio de nombres de `CfdiUtils\Elements\Nomina12` permite trabajar en forma más fácil con los nodos
con nombres y acciones específicas para implementar el Complemento de Nómina versión 1.2, revisión B
vigente a partir del 01 de enero del 2020.

La documentación del complemento la puedes encontrar en el sitio oficial del SAT:

- Recibo de nómina <https://www.sat.gob.mx/consultas/97722/comprobante-de-nomina>
  y <http://omawww.sat.gob.mx/tramitesyservicios/Paginas/complemento_nomina.htm>.
- Estándar técnico <http://omawww.sat.gob.mx/tramitesyservicios/Paginas/documentos/Nomina111219.pdf>.
- Catálogos <http://www.sat.gob.mx/sitio_internet/cfd/catalogos/Nomina/catNomina.xsd>.

Según la documentación técnica el XML **debe cumplir** con la siguiente especificación:

- Prefijo de namespace: `nomina12`.
- Namespace: `http://www.sat.gob.mx/nomina12`.
- Archivo XSD: `http://www.sat.gob.mx/sitio_internet/cfd/nomina/nomina12.xsd`.

## Jerarquía de nodos

En la siguiente lista se puede ver la jerarquía, el orden y el número de apariciones mínimas y máximas de los nodos
en el Complemento de Nómina versión 1.2, revisión B.

```text
[0,1] Nomina
      [0,1] Emisor
            [0,1] EntidadSNCF
      [1,1] Receptor
            [0,N] SubContratacion
      [0,1] Percepciones
            [0,N] Percepcion
                  [0,1] AccionesOTitulos
                  [0,N] HorasExtra
            [0,1] JubilacionPensionRetiro
            [0,1] SeparacionIndemnizacion
      [0,1] Deducciones
            [0,N] Deduccion
      [0,1] OtrosPagos
            [0,N] OtroPago
                  [0,1] SubsidioAlEmpleo
                  [0,1] CompensacionSaldosAFavor
      [0,1] Incapacidades
            [0,N] Incapacidad
```

## Nodos por número máximo de apariciones

Hay dos tipos de nodos según el número máximo de apariciones, el primer tipo es de los que admiten máximo una aparición
como `[0,1] Percepciones`, en el segundo tipo se admiten múltiples apariciones como `[0,N] Percepcion`. Ejemplificando:

```xml
<nomina12:Nomina>
    <nomina12:Percepciones> <!-- solo puede aparecer 1 vez -->
        <nomina12:Percepcion/> <!-- aparece múltiples veces -->
        <nomina12:Percepcion/>
    </nomina12:Percepciones>
</nomina12:Nomina>
```

## Métodos para agregar nodos

Los métodos de ayuda para nodos de máximo una sola aparición tienen la forma `getElemento(): Elemento`
y `addElemento(array $attributes): Elemento`. En donde `Elemento` se sustituye por el nombre del nodo.
En este caso, `addElemento` siempre trabaja con el elemento que previamente exista.

Los métodos de ayuda para nodos de múltiples apariciones tienen la forma `addElemento(array $attributes): Elemento`
y `multiElemento(array $attributes): self`. En donde `Elemento` se sustituye por el nombre del nodo y `self` es el
elemento que contiene el componente.
En este caso, `addElemento` siempre agrega un nuevo elemento.

```php
<?php
$nomina = new \CfdiUtils\Elements\Nomina12\Nomina();

// acceso por prefijo get (Emisor es de 1 aparición)
$emisor = $nomina->getEmisor();
$emisor['Curp'] = '...';

// agregar con prefijo add (Receptor es de 1 aparición)
$receptor = $nomina->addReceptor(['NumEmpleado' => 'JFIK000045']);

// agregar con prefijo add (Subcontratacion es de múltiples)
$receptor->addSubContratacion(['RfcLabora' => 'EKU9003173C9', 'PorcentajeTiempo' => '50']); // devuelve SubContratacion
$receptor->addSubContratacion(['RfcLabora' => 'XXXX010101XXX', 'PorcentajeTiempo' => '60']); // devuelve SubContratacion

// agregar con prefijo multi (Subcontratacion es de múltiples)
$receptor->multiSubContratacion(
    ['RfcLabora' => 'EKU9003173C9', 'PorcentajeTiempo' => '50'],
    ['RfcLabora' => 'XXXX010101XXX', 'PorcentajeTiempo' => '60']
); // devuelve Receptor (exactamente $receptor)
```

### Métodos de ayuda de los elementos

#### Elemento `Nomina`

- `Nomina::getEmisor(): Emisor`.
- `Nomina::addEmisor(array $attributes): Emisor`.
- `Nomina::getReceptor(): Receptor`.
- `Nomina::addReceptor(array $attributes): Receptor`.
- `Nomina::getPercepciones(): Percepciones`.
- `Nomina::addPercepciones(array $attributes): Percepciones`.
- `Nomina::getDeducciones(): Deducciones`.
- `Nomina::addDeducciones(array $attributes): Deducciones`.
- `Nomina::getOtrosPagos(): OtrosPagos`.
- `Nomina::addOtrosPagos(array $attributes): OtrosPagos`.
- `Nomina::getIncapacidades(): Incapacidades`.
- `Nomina::addIncapacidades(array $attributes): Incapacidades`.

#### Elemento `Emisor`

- `Emisor::getEntidadSNCF(): EntidadSNCF`.
- `Emisor::addEntidadSNCF(array $attributes): EntidadSNCF`.

#### Elemento `Receptor`

- `Receptor::addSubContratacion(array $attributes): SubContratacion`.
- `Receptor::multiSubContratacion(array $attributes, array $attributes, ...): Receptor`.

#### Elemento `Percepciones`

- `Percepciones::addPercepcion(array $attributes): Percepcion`.
- `Percepciones::multiPercepcion(array $attributes, array $attributes, ...): Percepcion`.
- `Percepciones::getJubilacionPensionRetiro(): JubilacionPensionRetiro`.
- `Percepciones::addJubilacionPensionRetiro(array $attributes): JubilacionPensionRetiro`.
- `Percepciones::getSeparacionIndemnizacion(): SeparacionIndemnizacion`.
- `Percepciones::addSeparacionIndemnizacion(array $attributes): SeparacionIndemnizacion`.

#### Elemento `Percepcion`

- `Percepcion::getAccionesOTitulos(): AccionesOTitulos`.
- `Percepcion::addAccionesOTitulos(array $attributes): AccionesOTitulos`.
- `Percepcion::addHorasExtra(array $attributes): HorasExtra`.
- `Percepcion::multiHorasExtra(array $attributes, array $attributes, ...): HorasExtra`.

#### Elemento `Deducciones`

- `Deducciones::addDeduccion(array $attributes): Deduccion`.
- `Deducciones::multiDeduccion(array $attributes, array $attributes, ...): Deducciones`.

#### Elemento `OtrosPagos`

- `OtrosPagos::addOtroPago(array $attributes): OtroPago`.
- `OtrosPagos::multiOtroPago(array $attributes, array $attributes, ...): OtrosPagos`.


#### Elemento `Incapacidades`

- `Incapacidades::addIncapacidad(array $attributes): addIncapacidad`.
- `Incapacidades::multiaddIncapacidad(array $attributes, array $attributes, ...): Incapacidades`.

### Agregar el complemento de nómina al comprobante

Cuando se tiene un comprobante, se puede utilizar el método `Comprobante::addComplemento()` para insertar
el elemento `Nomina` al comprobante.

```php
<?php
// clase de ayuda de creación del CFDI 3.3
$creator = new \CfdiUtils\CfdiCreator33();
// acceso al elemento Comprobante (el nodo principal del CFDI)
$comprobante = $creator->comprobante();

$nomina = new \CfdiUtils\Elements\Nomina12\Nomina();
// ... llenar la información de $nomina

// agregar $nomina como complemento del $comprobante
$comprobante->addComplemento($nomina);
```
