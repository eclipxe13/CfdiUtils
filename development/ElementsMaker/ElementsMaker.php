<?php

declare(strict_types=1);

namespace CfdiUtils\Development\ElementsMaker;

final class ElementsMaker
{
    private Specifications $specs;

    private string $outputDir;

    /** @var array<string, string> */
    private array $templates = [];

    public function __construct(Specifications $specs, string $outputDir)
    {
        $this->specs = $specs;
        $this->outputDir = $outputDir;
    }

    public static function make(string $specFile, string $outputDir): self
    {
        return new self(Specifications::makeFromFile($specFile), $outputDir);
    }

    public function write(): void
    {
        $this->createElement($this->specs->getStructure(), $this->specs->getDictionary(), true);
    }

    public function createElement(Structure $structure, Dictionary $dictionary, bool $isRoot = false): void
    {
        $prefix = $dictionary->get('#prefix#');
        $dictionary = $dictionary->with('#element-name#', $structure->getName());
        $sectionsContent = [];
        $orderElements = $structure->getChildrenNames($prefix . ':');

        if (count($orderElements) > 1) {
            $sectionsContent[] = $this->template(
                'get-children-order',
                new Dictionary(['#elements#' => $this->elementsToString($orderElements)])
            );
        }

        if ($isRoot) {
            $sectionsContent[] = $this->template('get-fixed-attributes', $dictionary);
        }

        /** @var Structure $child */
        foreach ($structure as $child) {
            $childTemplate = ($child->isMultiple()) ? 'child-multiple' : 'child-single';
            $sectionsContent[] = $this->template($childTemplate, new Dictionary(['#child-name#' => $child->getName()]));
            $this->createElement($child, $dictionary);
        }

        $contents = $this->template('element', $dictionary->with('#sections#', implode('', $sectionsContent)));
        $outputFile = $this->buildOutputFile($structure->getName());
        file_put_contents($outputFile, $contents);
    }

    private function template(string $templateName, Dictionary $dictionary): string
    {
        if (! isset($this->templates[$templateName])) {
            $fileName = __DIR__ . '/templates/' . $templateName . '.template';
            $this->templates[$templateName] = file_get_contents($fileName) ?: '';
        }

        return $dictionary->interpolate($this->templates[$templateName]);
    }

    private function buildOutputFile(string $elementName): string
    {
        return $this->outputDir . DIRECTORY_SEPARATOR . $elementName . '.php';
    }

    /** @param string[] $array */
    private function elementsToString(array $array): string
    {
        $parts = [];
        foreach ($array as $value) {
            $parts[] = var_export($value, true);
        }
        return "[\n" . implode(",\n", $parts) . ']';
    }
}
