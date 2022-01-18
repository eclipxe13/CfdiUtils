<?php

declare(strict_types=1);

namespace CfdiUtils\Cleaner\Cleaners;

use CfdiUtils\Internals\StringUncaseReplacer;
use CfdiUtils\Utils\SchemaLocations;
use DOMAttr;

/**
 * Class SchemaLocationsXsdUrlsFixer
 *
 * This class is an abstraction of method Cleaner::fixKnownSchemaLocationsXsdUrls
 *
 * @internal
 */
final class SchemaLocationsXsdUrlsFixer
{
    /** @var StringUncaseReplacer */
    private $replacer;

    /**
     * Create a new instance based on a map using keys as replacement and values as an array of needles
     *
     * @param array<string, array<string>> $replacements
     */
    private function __construct(array $replacements)
    {
        $this->replacer = StringUncaseReplacer::create($replacements);
    }

    /**
     * Created a new instance based on known CFDI and TFD
     * It also includes the incorrect but allowed TFD 1.0 alternate urls
     *
     * @return self
     */
    public static function createWithKnownSatUrls(): self
    {
        return new self([
            'http://www.sat.gob.mx/sitio_internet/cfd/4/cfdv40.xsd' => [],
            'http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv33.xsd' => [],
            'http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv32.xsd' => [],
            'http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv3.xsd' => [],
            'http://www.sat.gob.mx/sitio_internet/cfd/2/cfdv22.xsd' => [],
            'http://www.sat.gob.mx/sitio_internet/cfd/2/cfdv2.xsd' => [],
            'http://www.sat.gob.mx/sitio_internet/cfd/1/cfdv1.xsd' => [],
            'http://www.sat.gob.mx/sitio_internet/cfd/TimbreFiscalDigital/TimbreFiscalDigitalv11.xsd' => [],
            'http://www.sat.gob.mx/sitio_internet/cfd/TimbreFiscalDigital/TimbreFiscalDigital.xsd' => [
                'http://www.sat.gob.mx/sitio_internet/TimbreFiscalDigital/TimbreFiscalDigital.xsd',
            ],
        ]);
    }

    public function fixSchemaLocationAttribute(DOMAttr $xsiSchemaLocation)
    {
        $schemas = SchemaLocations::fromString($xsiSchemaLocation->value, false);
        $this->fixSchemaLocations($schemas);
        $fixedValue = $schemas->asString();
        if ($xsiSchemaLocation->value !== $fixedValue) {
            $xsiSchemaLocation->value = $fixedValue;
        }
    }

    public function fixSchemaLocations(SchemaLocations $schemaLocations)
    {
        foreach ($schemaLocations as $ns => $url) {
            $url = $this->replacer->findReplacement($url) ?: $url;
            $schemaLocations->append($ns, $url);
        }
    }
}
