# Creación de CFDI 4.0

Por favor, lee la documentación de cómo [crear un CFDI 3.3](crear-cfdi.md).
Todas las reglas generales de creación aplican para CFDI 4.0.


## Migrar de CFDI 3.3 a CFDI 4.0

Por lo relacionado con esta librería, solo necesitarás cambiar lo que diga `CfdiUtils\Elements\Cfdi33`
por `CfdiUtils\Elements\Cfdi40`. Así como usar el objeto `CfdiUtils\CfdiCreator40`.

La versión 2.19.0 o mayor fue dotada con todos los elementos necesarios para hacer una migración
de CFDI 3.3 a CFDI 4.0 lo menos dolorosa posible.

Ten en cuenta que tendrás que modificar la información que pasas a los elementos porque se han agregado
varios de ellos, sin embargo, la *compatibilidad* ofrecida permite que te concentres en solo un problema:
*Aplicar los cambios del SAT a CFDI 4.0.*.

