<?php

declare(strict_types=1);

namespace CfdiUtils\Development\ElementsMaker;

final class Specifications
{
    public function __construct(private Structure $structure, private Dictionary $dictionary)
    {
    }

    public static function makeFromFile(string $specFile): self
    {
        $specFileReader = SpecificationsReader::fromFile($specFile);

        $structure = Structure::makeFromStdClass(
            $specFileReader->keyAsString('root-element'),
            $specFileReader->keyAsStdClass('structure')
        );

        $dictionary = new Dictionary([
            '#php-namespace#' => $specFileReader->keyAsString('php-namespace'),
            '#prefix#' => $specFileReader->keyAsString('prefix'),
            '#xml-namespace#' => $specFileReader->keyAsString('xml-namespace'),
            '#xml-schemalocation#' => $specFileReader->keyAsString('xml-schemalocation'),
            '#version-attribute#' => $specFileReader->keyAsString('version-attribute'),
            '#version-value#' => $specFileReader->keyAsString('version-value'),
        ]);

        return new self($structure, $dictionary);
    }

    public function getStructure(): Structure
    {
        return $this->structure;
    }

    public function getDictionary(): Dictionary
    {
        return $this->dictionary;
    }
}
