# `\CfdiUtils\CfdiCertificado`

Esta clase ayuda para la extracción de un certificado de un CFDI. Extiende a la clase `\CfdiUtils\Cfdi`. 

Para obtener el certificado de un cfdi ofrece los siguientes métodos:

- `obtain(): Certificado` guarda el certificado en un archivo temporal y devuelve un objeto de tipo `\CfdiUtils\Certificado`
  con su información. El archivo temporal es eliminado antes de la salir de la función.
- `save(string $filename): void` extrae y guarda el certificado en un archivo
- `extract(): string` extrae el archivo decodificándolo de base64
