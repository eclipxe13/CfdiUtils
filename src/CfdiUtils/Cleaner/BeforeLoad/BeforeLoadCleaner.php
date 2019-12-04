<?php

namespace CfdiUtils\Cleaner\BeforeLoad;

class BeforeLoadCleaner implements BeforeLoadCleanerInterface
{
    /** @var BeforeLoadCleanerInterface[] */
    private $cleaners;

    public function __construct(BeforeLoadCleanerInterface ...$cleaners)
    {
        if ([] === $cleaners) {
            $cleaners = $this->defaultCleaners();
        }
        $this->cleaners = $cleaners;
    }

    /** @return BeforeLoadCleanerInterface[] */
    public static function defaultCleaners(): array
    {
        return [
            new ChangeXmlnsSchemaLocation(),
            new RemoveDuplicatedCfdi3Namespace(),
        ];
    }

    /** @return BeforeLoadCleanerInterface[] */
    public function members(): array
    {
        return $this->cleaners;
    }

    public function clean(string $content): string
    {
        foreach ($this->cleaners as $cleaner) {
            $content = $cleaner->clean($content);
        }
        return $content;
    }
}
