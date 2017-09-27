<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:fn="http://www.w3.org/2005/xpath-functions" xmlns:cfdi="http://www.sat.gob.mx/cfd/3" xmlns:ecc="http://www.sat.gob.mx/ecc" xmlns:psgecfd="http://www.sat.gob.mx/psgecfd" xmlns:donat="http://www.sat.gob.mx/donat" xmlns:divisas="http://www.sat.gob.mx/divisas" xmlns:detallista="http://www.sat.gob.mx/detallista" xmlns:ecb="http://www.sat.gob.mx/ecb" xmlns:implocal="http://www.sat.gob.mx/implocal" xmlns:terceros="http://www.sat.gob.mx/terceros" xmlns:iedu="http://www.sat.gob.mx/iedu" xmlns:ventavehiculos="http://www.sat.gob.mx/ventavehiculos" xmlns:pfic="http://www.sat.gob.mx/pfic" xmlns:tpe="http://www.sat.gob.mx/TuristaPasajeroExtranjero" xmlns:leyendasFisc="http://www.sat.gob.mx/leyendasFiscales" xmlns:spei="http://www.sat.gob.mx/spei" xmlns:nomina="http://www.sat.gob.mx/nomina" xmlns:registrofiscal="http://www.sat.gob.mx/registrofiscal" xmlns:pagoenespecie="http://www.sat.gob.mx/pagoenespecie" xmlns:consumodecombustibles="http://www.sat.gob.mx/consumodecombustibles" xmlns:valesdedespensa="http://www.sat.gob.mx/valesdedespensa" xmlns:aerolineas="http://www.sat.gob.mx/aerolineas" xmlns:notariospublicos="http://www.sat.gob.mx/notariospublicos" xmlns:vehiculousado="http://www.sat.gob.mx/vehiculousado" xmlns:servicioparcial="http://www.sat.gob.mx/servicioparcialconstruccion" xmlns:destruccion="http://www.sat.gob.mx/certificadodestruccion" xmlns:decreto="http://www.sat.gob.mx/renovacionysustitucionvehiculos" xmlns:obrasarte="http://www.sat.gob.mx/arteantiguedades" xmlns:aieps="http://www.sat.gob.mx/acreditamiento" xmlns:ecc11="http://www.sat.gob.mx/EstadoDeCuentaCombustible" xmlns:cce="http://www.sat.gob.mx/ComercioExterior" xmlns:ine="http://www.sat.gob.mx/ine" xmlns:nomina12="http://www.sat.gob.mx/nomina12" xmlns:cce11="http://www.sat.gob.mx/ComercioExterior11" version="2.0">

  <!-- Integración de complemento Nomina 03-05-2013-->
  <!-- Integración de complemento CFDI Registro Fiscal 27-11-2013-->
  <!-- Integración de complemento Pago en Especie 18-12-2013-->
  <!-- Integración de complemento Consumo de combustible 05-02-2014-->
  <!-- Integración de complemento Vales de despensa 05-02-2014-->
  <!-- Integración de complemento aerolineas 07-02-2014-->
  <!-- Integración de complemento notarios publicos 25-03-2014-->
  <!-- Integración de complemento vehiculo usado03-10-2014-->
  <!-- Integración de complemento Acreditación IEPS usado 02-10-2015-->
  <!-- Integración de complemento Acreditación Estado de cuenta combustible usado 01-11-2015-->
  <!-- Integración de complemento Comercio exterior usado 27-11-2015-->
  <!-- Integración de complemento INE usado 18-01-2016-->
  <!-- Integración de complemento INE1.1 usado 16-06-2016-->
  <!-- Integración de complemento NOMINA 1.2 usado 12-11-2016-->
  <!-- Integración de complemento Comercio exterior 1.1 usado 26-01-2017-->

  <!-- Con el siguiente método se establece que la salida deberá ser en texto -->
  <xsl:output method="text" version="1.0" encoding="UTF-8" indent="no"/>
  <!--
		En esta sección se define la inclusión de las plantillas de utilerías para colapsar espacios
	-->
  <xsl:include href="../../2/cadenaoriginal_2_0/utilerias.xslt"/>
  <!-- 
		En esta sección se define la inclusión de las demás plantillas de transformación para 
		la generación de las cadenas originales de los complementos fiscales 
	-->
  <xsl:include href="../../ecc/ecc.xslt"/>
  <xsl:include href="../../psgecfd/psgecfd.xslt"/>
  <xsl:include href="../../donat/donat11.xslt"/>
  <xsl:include href="../../divisas/divisas.xslt"/>
  <xsl:include href="../../ecb/ecb.xslt"/>
  <xsl:include href="../../detallista/detallista.xslt"/>
  <xsl:include href="../../implocal/implocal.xslt"/>
  <xsl:include href="../../terceros/terceros11.xslt"/>
  <xsl:include href="../../iedu/iedu.xslt"/>
  <xsl:include href="../../ventavehiculos/ventavehiculos11.xslt"/>
  <xsl:include href="../../pfic/pfic.xslt"/>
  <xsl:include href="../../TuristaPasajeroExtranjero/TuristaPasajeroExtranjero.xslt"/>
  <xsl:include href="../../leyendasFiscales/leyendasFisc.xslt"/>
  <xsl:include href="../../spei/spei.xslt"/>
  <xsl:include href="../../nomina/nomina11.xslt"/>
  <xsl:include href="../../cfdiregistrofiscal/cfdiregistrofiscal.xslt"/>
  <xsl:include href="../../pagoenespecie/pagoenespecie.xslt"/>
  <xsl:include href="../../consumodecombustibles/consumodecombustibles.xslt"/>
  <xsl:include href="../../valesdedespensa/valesdedespensa.xslt"/>
  <xsl:include href="../../aerolineas/aerolineas.xslt"/>
  <xsl:include href="../../notariospublicos/notariospublicos.xslt"/>
  <xsl:include href="../../vehiculousado/vehiculousado.xslt"/>
  <xsl:include href="../../servicioparcialconstruccion/servicioparcialconstruccion.xslt"/>
  <xsl:include href="../../certificadodestruccion/certificadodedestruccion.xslt"/>
  <xsl:include href="../../renovacionysustitucionvehiculos/renovacionysustitucionvehiculos.xslt"/>
  <xsl:include href="../../arteantiguedades/obrasarteantiguedades.xslt"/>
  <xsl:include href="../../acreditamiento/AcreditamientoIEPS10.xslt"/>
  <xsl:include href="../../EstadoDeCuentaCombustible/ecc11.xslt"/>
  <xsl:include href="../../ComercioExterior/ComercioExterior10.xslt"/>
  <xsl:include href="../../ine/ine10.xslt"/>
  <xsl:include href="../../ine/ine11.xslt"/>
  <xsl:include href="../../nomina/nomina12.xslt"/>
  <xsl:include href="../../ComercioExterior11/ComercioExterior11.xslt"/>
  <!-- Aquí iniciamos el procesamiento de la cadena original con su | inicial y el terminador || -->
  <xsl:template match="/">|<xsl:apply-templates select="/cfdi:Comprobante"/>||</xsl:template>
  <!--  Aquí iniciamos el procesamiento de los datos incluidos en el comprobante -->
  <xsl:template match="cfdi:Comprobante">
    <!-- Iniciamos el tratamiento de los atributos de comprobante -->
    <xsl:call-template name="Requerido">
      <xsl:with-param name="valor" select="./@version"/>
    </xsl:call-template>
    <xsl:call-template name="Requerido">
      <xsl:with-param name="valor" select="./@fecha"/>
    </xsl:call-template>
    <xsl:call-template name="Requerido">
      <xsl:with-param name="valor" select="./@tipoDeComprobante"/>
    </xsl:call-template>
    <xsl:call-template name="Requerido">
      <xsl:with-param name="valor" select="./@formaDePago"/>
    </xsl:call-template>
    <xsl:call-template name="Opcional">
      <xsl:with-param name="valor" select="./@condicionesDePago"/>
    </xsl:call-template>
    <xsl:call-template name="Requerido">
      <xsl:with-param name="valor" select="./@subTotal"/>
    </xsl:call-template>
    <xsl:call-template name="Opcional">
      <xsl:with-param name="valor" select="./@descuento"/>
    </xsl:call-template>
    <xsl:call-template name="Opcional">
      <xsl:with-param name="valor" select="./@TipoCambio"/>
    </xsl:call-template>
    <xsl:call-template name="Opcional">
      <xsl:with-param name="valor" select="./@Moneda"/>
    </xsl:call-template>
    <xsl:call-template name="Requerido">
      <xsl:with-param name="valor" select="./@total"/>
    </xsl:call-template>
    <xsl:call-template name="Requerido">
      <xsl:with-param name="valor" select="./@metodoDePago"/>
    </xsl:call-template>
    <xsl:call-template name="Requerido">
      <xsl:with-param name="valor" select="./@LugarExpedicion"/>
    </xsl:call-template>
    <xsl:call-template name="Opcional">
      <xsl:with-param name="valor" select="./@NumCtaPago"/>
    </xsl:call-template>
    <xsl:call-template name="Opcional">
      <xsl:with-param name="valor" select="./@FolioFiscalOrig"/>
    </xsl:call-template>
    <xsl:call-template name="Opcional">
      <xsl:with-param name="valor" select="./@SerieFolioFiscalOrig"/>
    </xsl:call-template>
    <xsl:call-template name="Opcional">
      <xsl:with-param name="valor" select="./@FechaFolioFiscalOrig"/>
    </xsl:call-template>
    <xsl:call-template name="Opcional">
      <xsl:with-param name="valor" select="./@MontoFolioFiscalOrig"/>
    </xsl:call-template>
    <!--
			Llamadas para procesar al los sub nodos del comprobante
		-->
    <xsl:apply-templates select="./cfdi:Emisor"/>
    <xsl:apply-templates select="./cfdi:Receptor"/>
    <xsl:apply-templates select="./cfdi:Conceptos"/>
    <xsl:apply-templates select="./cfdi:Impuestos"/>
    <xsl:apply-templates select="./cfdi:Complemento"/>
  </xsl:template>
  <!-- Manejador de nodos tipo Emisor -->
  <xsl:template match="cfdi:Emisor">
    <!-- Iniciamos el tratamiento de los atributos del Emisor -->
    <xsl:call-template name="Requerido">
      <xsl:with-param name="valor" select="./@rfc"/>
    </xsl:call-template>
    <xsl:call-template name="Opcional">
      <xsl:with-param name="valor" select="./@nombre"/>
    </xsl:call-template>
    <!--
			Llamadas para procesar al los sub nodos del comprobante
		-->
    <xsl:apply-templates select="./cfdi:DomicilioFiscal"/>
    <xsl:if test="./cfdi:ExpedidoEn">
      <xsl:call-template name="Domicilio">
        <xsl:with-param name="Nodo" select="./cfdi:ExpedidoEn"/>
      </xsl:call-template>
    </xsl:if>
    <xsl:for-each select="./cfdi:RegimenFiscal">
      <xsl:call-template name="Requerido">
        <xsl:with-param name="valor" select="./@Regimen"/>
      </xsl:call-template>
    </xsl:for-each>
  </xsl:template>
  <!-- Manejador de nodos tipo Receptor -->
  <xsl:template match="cfdi:Receptor">
    <!-- Iniciamos el tratamiento de los atributos del Receptor -->
    <xsl:call-template name="Requerido">
      <xsl:with-param name="valor" select="./@rfc"/>
    </xsl:call-template>
    <xsl:call-template name="Opcional">
      <xsl:with-param name="valor" select="./@nombre"/>
    </xsl:call-template>
    <!--
			Llamadas para procesar al los sub nodos del Receptor
		-->
    <xsl:if test="./cfdi:Domicilio">
      <xsl:call-template name="Domicilio">
        <xsl:with-param name="Nodo" select="./cfdi:Domicilio"/>
      </xsl:call-template>
    </xsl:if>
  </xsl:template>
  <!-- Manejador de nodos tipo Conceptos -->
  <xsl:template match="cfdi:Conceptos">
    <!-- Llamada para procesar los distintos nodos tipo Concepto -->
    <xsl:for-each select="./cfdi:Concepto">
      <xsl:apply-templates select="."/>
    </xsl:for-each>
  </xsl:template>
  <!-- Manejador de nodos tipo Impuestos -->
  <xsl:template match="cfdi:Impuestos">
    <xsl:for-each select="./cfdi:Retenciones/cfdi:Retencion">
      <xsl:apply-templates select="."/>
    </xsl:for-each>
    <xsl:call-template name="Opcional">
      <xsl:with-param name="valor" select="./@totalImpuestosRetenidos"/>
    </xsl:call-template>
    <xsl:for-each select="./cfdi:Traslados/cfdi:Traslado">
      <xsl:apply-templates select="."/>
    </xsl:for-each>
    <xsl:call-template name="Opcional">
      <xsl:with-param name="valor" select="./@totalImpuestosTrasladados"/>
    </xsl:call-template>
  </xsl:template>
  <!-- Manejador de nodos tipo Retencion -->
  <xsl:template match="cfdi:Retencion">
    <xsl:call-template name="Requerido">
      <xsl:with-param name="valor" select="./@impuesto"/>
    </xsl:call-template>
    <xsl:call-template name="Requerido">
      <xsl:with-param name="valor" select="./@importe"/>
    </xsl:call-template>
  </xsl:template>
  <!-- Manejador de nodos tipo Traslado -->
  <xsl:template match="cfdi:Traslado">
    <xsl:call-template name="Requerido">
      <xsl:with-param name="valor" select="./@impuesto"/>
    </xsl:call-template>
    <xsl:call-template name="Requerido">
      <xsl:with-param name="valor" select="./@tasa"/>
    </xsl:call-template>
    <xsl:call-template name="Requerido">
      <xsl:with-param name="valor" select="./@importe"/>
    </xsl:call-template>
  </xsl:template>
  <!-- Manejador de nodos tipo Complemento -->
  <xsl:template match="cfdi:Complemento">
    <xsl:for-each select="./*">
      <xsl:apply-templates select="."/>
    </xsl:for-each>
  </xsl:template>
  <!--
		Manejador de nodos tipo Concepto
	-->
  <xsl:template match="cfdi:Concepto">
    <!-- Iniciamos el tratamiento de los atributos del Concepto -->
    <xsl:call-template name="Requerido">
      <xsl:with-param name="valor" select="./@cantidad"/>
    </xsl:call-template>
    <xsl:call-template name="Requerido">
      <xsl:with-param name="valor" select="./@unidad"/>
    </xsl:call-template>
    <xsl:call-template name="Opcional">
      <xsl:with-param name="valor" select="./@noIdentificacion"/>
    </xsl:call-template>
    <xsl:call-template name="Requerido">
      <xsl:with-param name="valor" select="./@descripcion"/>
    </xsl:call-template>
    <xsl:call-template name="Requerido">
      <xsl:with-param name="valor" select="./@valorUnitario"/>
    </xsl:call-template>
    <xsl:call-template name="Requerido">
      <xsl:with-param name="valor" select="./@importe"/>
    </xsl:call-template>
    <!--
			Manejo de los distintos sub nodos de información aduanera de forma indistinta 
			a su grado de dependencia
		-->
    <xsl:for-each select=".//cfdi:InformacionAduanera">
      <xsl:apply-templates select="."/>
    </xsl:for-each>
    <!-- Llamada al manejador de nodos de Cuenta Predial en caso de existir -->
    <xsl:if test="./cfdi:CuentaPredial">
      <xsl:apply-templates select="./cfdi:CuentaPredial"/>
    </xsl:if>
    <!-- Llamada al manejador de nodos de ComplementoConcepto en caso de existir -->
    <xsl:if test="./cfdi:ComplementoConcepto">
      <xsl:apply-templates select="./cfdi:ComplementoConcepto"/>
    </xsl:if>
  </xsl:template>
  <!-- Manejador de nodos tipo Información Aduanera -->
  <xsl:template match="cfdi:InformacionAduanera">
    <!-- Manejo de los atributos de la información aduanera -->
    <xsl:call-template name="Requerido">
      <xsl:with-param name="valor" select="./@numero"/>
    </xsl:call-template>
    <xsl:call-template name="Requerido">
      <xsl:with-param name="valor" select="./@fecha"/>
    </xsl:call-template>
    <xsl:call-template name="Opcional">
      <xsl:with-param name="valor" select="./@aduana"/>
    </xsl:call-template>
  </xsl:template>
  <!-- Manejador de nodos tipo Información CuentaPredial -->
  <xsl:template match="cfdi:CuentaPredial">
    <xsl:call-template name="Requerido">
      <xsl:with-param name="valor" select="./@numero"/>
    </xsl:call-template>
  </xsl:template>
  <!-- Manejador de nodos tipo ComplementoConcepto -->
  <xsl:template match="cfdi:ComplementoConcepto">
    <xsl:for-each select="./*">
      <xsl:apply-templates select="."/>
    </xsl:for-each>
  </xsl:template>
  <!-- Manejador de nodos tipo Domicilio fiscal -->
  <xsl:template match="cfdi:DomicilioFiscal">
    <!-- Iniciamos el tratamiento de los atributos del Domicilio Fiscal -->
    <xsl:call-template name="Requerido">
      <xsl:with-param name="valor" select="./@calle"/>
    </xsl:call-template>
    <xsl:call-template name="Opcional">
      <xsl:with-param name="valor" select="./@noExterior"/>
    </xsl:call-template>
    <xsl:call-template name="Opcional">
      <xsl:with-param name="valor" select="./@noInterior"/>
    </xsl:call-template>
    <xsl:call-template name="Opcional">
      <xsl:with-param name="valor" select="./@colonia"/>
    </xsl:call-template>
    <xsl:call-template name="Opcional">
      <xsl:with-param name="valor" select="./@localidad"/>
    </xsl:call-template>
    <xsl:call-template name="Opcional">
      <xsl:with-param name="valor" select="./@referencia"/>
    </xsl:call-template>
    <xsl:call-template name="Requerido">
      <xsl:with-param name="valor" select="./@municipio"/>
    </xsl:call-template>
    <xsl:call-template name="Requerido">
      <xsl:with-param name="valor" select="./@estado"/>
    </xsl:call-template>
    <xsl:call-template name="Requerido">
      <xsl:with-param name="valor" select="./@pais"/>
    </xsl:call-template>
    <xsl:call-template name="Requerido">
      <xsl:with-param name="valor" select="./@codigoPostal"/>
    </xsl:call-template>
  </xsl:template>
  <!-- Manejador de nodos tipo Domicilio -->
  <xsl:template name="Domicilio">
    <xsl:param name="Nodo"/>
    <!-- Iniciamos el tratamiento de los atributos del Domicilio  -->
    <xsl:call-template name="Opcional">
      <xsl:with-param name="valor" select="$Nodo/@calle"/>
    </xsl:call-template>
    <xsl:call-template name="Opcional">
      <xsl:with-param name="valor" select="$Nodo/@noExterior"/>
    </xsl:call-template>
    <xsl:call-template name="Opcional">
      <xsl:with-param name="valor" select="$Nodo/@noInterior"/>
    </xsl:call-template>
    <xsl:call-template name="Opcional">
      <xsl:with-param name="valor" select="$Nodo/@colonia"/>
    </xsl:call-template>
    <xsl:call-template name="Opcional">
      <xsl:with-param name="valor" select="$Nodo/@localidad"/>
    </xsl:call-template>
    <xsl:call-template name="Opcional">
      <xsl:with-param name="valor" select="$Nodo/@referencia"/>
    </xsl:call-template>
    <xsl:call-template name="Opcional">
      <xsl:with-param name="valor" select="$Nodo/@municipio"/>
    </xsl:call-template>
    <xsl:call-template name="Opcional">
      <xsl:with-param name="valor" select="$Nodo/@estado"/>
    </xsl:call-template>
    <xsl:call-template name="Requerido">
      <xsl:with-param name="valor" select="$Nodo/@pais"/>
    </xsl:call-template>
    <xsl:call-template name="Opcional">
      <xsl:with-param name="valor" select="$Nodo/@codigoPostal"/>
    </xsl:call-template>
  </xsl:template>
</xsl:stylesheet>
