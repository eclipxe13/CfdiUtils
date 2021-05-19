Include certificate files from SAT into tests/assets/certs/

Downloaded from http://omawww.sat.gob.mx/tramitesyservicios/Paginas/certificado_sello_digital.htm
File http://omawww.sat.gob.mx/tramitesyservicios/Paginas/documentos/RFC-PAC-SC.zip

Commands:

```shell
# get certificate information:
openssl x509 -nameopt utf8,sep_multiline,lname -inform DER -noout -dates -serial -subject -fingerprint -pubkey -in EKU9003173C9.cer

# convert private key from DER to PEM (password 12345678a):
openssl pkcs8 -inform DER -in EKU9003173C9.key -out EKU9003173C9.key.pem

# protect with password the private key, not required but used for test suite:
openssl rsa -in EKU9003173C9.key.pem -des3 -out EKU9003173C9_password.key.pem

# convert public key from DER to PEM, not required but used for test suite:
openssl x509 -inform DER -outform PEM -in EKU9003173C9.cer -pubkey -out EKU9003173C9.cer.pem
```

## sign and verify using openssl

```shell
# sign the document
openssl dgst -sha256 -sign EKU9003173C9.key.pem -out data-sha256.bin data-to-sign.txt
# convert to base64
openssl base64 -in data-sha256.bin -out data-sha256.txt
# verify: Verified OK
openssl dgst -sha256 -verify EKU9003173C9.cer.pem -signature data-sha256.bin data-to-sign.txt
```


