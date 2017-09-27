Include certificate files from SAT into tests/assets/certs/

Downloaded from http://www.sat.gob.mx/informacion_fiscal/factura_electronica/Paginas/certificado_sello_digital.aspx
File http://www.sat.gob.mx/informacion_fiscal/factura_electronica/Documents/solcedi/Cert_Sellos.zip

Commands:

```shell
# get certificate information:
openssl x509 -nameopt utf8,sep_multiline,lname -inform DER -noout -dates -serial -subject -fingerprint -pubkey -in CSD01_AAA010101AAA.cer

# convert private key from DER to PEM (password 12345678a):
openssl pkcs8 -inform DER -in CSD01_AAA010101AAA.key -out CSD01_AAA010101AAA.key.pem

# protect with password the private key, not required but used for test suite:
openssl rsa -in CSD01_AAA010101AAA.key.pem -des3 -out CSD01_AAA010101AAA_password.key.pem

# convert public key from DER to PEM, not required but used for test suite:
openssl x509 -inform DER -outform PEM -in CSD01_AAA010101AAA.cer -pubkey -out CSD01_AAA010101AAA.cer.pem
```

## sign and verify using openssl

```shell
# sign the document
openssl dgst -sha256 -sign CSD01_AAA010101AAA.key.pem -out data-sha256.bin data-to-sign.txt
# convert to base64
openssl base64 -in data-sha256.bin -out data-sha256.txt
# verify: Verified OK
openssl dgst -sha256 -verify CSD01_AAA010101AAA.cer.pem -signature data-sha256.bin data-to-sign.txt
```


