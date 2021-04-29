# Limpieza de un CFDI

Frecuentemente se reciben archivos de CFDI que fueron firmados y son válidos pero contienen errores.

Sucede que, después de que el SAT (o el PAC en nombre del SAT) ha firmado un CFDI estos suelen ser alterados
con información que no pertenece a la cadena de origen. Lamentablemente esto es permitido por el SAT.

Un caso común de alteración es agregar más nodos al nodo `cfdi:Addenda`, como la información contenida
no pertenece a la cadena de origen entonces no se considera que el documento ha sido alterado.
Y hasta cierto punto esto no está mal. El problema viene cuando la información introducida contiene errores de XML.

La librería `CfdiUtils` hasta la versión `2.x` contaba con objetos propios de limpieza, a partir de la versión `3.x`
esta funcionalidad se movió a la librería [`phpcfdi/cfdi-cleaner`](https://github.com/phpcfdi/cfdi-cleaner).
