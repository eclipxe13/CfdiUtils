<?php
namespace CfdiUtils\Utils;

use DOMDocument;

class Xml
{
    public static function newDocument(): DOMDocument
    {
        $document = new DOMDocument();
        $document->formatOutput = true;
        $document->preserveWhiteSpace = false;
        return $document;
    }

    public static function newDocumentContent(string $content): DOMDocument
    {
        if ('' == $content) {
            throw new \UnexpectedValueException('Received xml string argument is empty');
        }
        $document = static::newDocument();
        // this error silenced call is intentional, no need to alter libxml_use_internal_errors
        if (false === @$document->loadXML($content)) {
            throw new \UnexpectedValueException('Cannot create a DOM Document from xml string');
        }
        return $document;
    }
}
