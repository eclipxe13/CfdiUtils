<?php
namespace CfdiUtils\Validate\Cfdi33\Xml;

use CfdiUtils\Nodes\Node;
use CfdiUtils\Nodes\XmlNodeUtils;
use CfdiUtils\Validate\Asserts;
use CfdiUtils\Validate\Cfdi33\Abstracts\AbstractVersion33;
use CfdiUtils\Validate\Contracts\RequireXmlResolverInterface;
use CfdiUtils\Validate\Contracts\RequireXmlStringInterface;
use CfdiUtils\Validate\Status;
use CfdiUtils\Validate\Traits\XmlStringPropertyTrait;
use CfdiUtils\XmlResolver\XmlResolverPropertyInterface;
use CfdiUtils\XmlResolver\XmlResolverPropertyTrait;
use XmlSchemaValidator\Schema;
use XmlSchemaValidator\SchemaValidator;

/**
 * XmlFollowSchema
 * Esta clase no es descubrible, esto es porque generalmente se busca que sea la primera validación.
 * Si falla el objeto Asserts devuelto tiene la bandera mustStop activa.
 *
 * Valida que:
 * - XDS01: El contenido XML sigue los esquemas XSD
 *
 * Para poder generar la validación se necesita el contenido XML, este puede ser establecido por sus propiedades.
 * En caso de no existir entonces el contenido se generará desde el atributo Node.
 * Para poder usar el resolvedor de recursos (usar archivos xsd locales) se debe especificar el XmlResolver.
 *
 * A pesar de no ser descubierto, se puede hidratar el objeto con sus interfaces y el uso de un Hydrater
 */
class XmlFollowSchema extends AbstractVersion33 implements
    XmlResolverPropertyInterface,
    RequireXmlStringInterface,
    RequireXmlResolverInterface
{
    use XmlStringPropertyTrait;
    use XmlResolverPropertyTrait;

    public function validate(Node $comprobante, Asserts $asserts)
    {
        $assert = $asserts->put('XDS01', 'El contenido XML sigue los esquemas XSD');

        // obtain content
        if ('' === $content = $this->getXmlString()) {
            $content = XmlNodeUtils::nodeToXmlString($comprobante);
        }

        // create the schema validator object
        $schemaValidator = new SchemaValidator($content);

        // validate using resolver->retriever or using the simple method
        try {
            if ($this->hasXmlResolver()) {
                $this->validateUsingRetriever($schemaValidator);
            } else {
                if (! $schemaValidator->validate()) {
                    throw new \Exception($schemaValidator->getLastError());
                }
            }
        } catch (\Exception $exception) {
            // validate failure
            $assert->setStatus(Status::error(), $exception->getMessage());
            $asserts->mustStop(true);
            return;
        }

        // set final status
        $assert->setStatus(Status::ok());
    }

    private function validateUsingRetriever(SchemaValidator $schemaValidator)
    {
        // obtain the retriever, throw its own exception if non set
        $retriever = $this->getXmlResolver()->newXsdRetriever();

        // obtain the list of schemas
        $schemas = $schemaValidator->buildSchemas();

        // replace the schemas locations with the retrieved local path
        /** @var Schema $schema */
        foreach ($schemas as $schema) {
            $location = $schema->getLocation();
            $localPath = $retriever->buildPath($location);
            if (! file_exists($localPath)) {
                $retriever->retrieve($location);
            }
            // this call will change the value, not insert a new entry
            $schemas->insert(new Schema($schema->getNamespace(), $localPath));
        }

        // validate using the modified schemas
        $schemaValidator->validateWithSchemas($schemas);
    }
}
