<?php

declare(strict_types=1);

namespace CfdiUtils\Internals;

/** @internal */
final class StringUncaseReplacer
{
    /**
     * @param array<string, array<string, true>> $replacements
     */
    private function __construct(private array $replacements = [])
    {
    }

    /**
     * @param array<string, array<string>> $replacements
     */
    public static function create(array $replacements): self
    {
        $replacer = new self();
        foreach ($replacements as $replacement => $needles) {
            $replacer->addReplacement($replacement, ...$needles);
        }
        return $replacer;
    }

    private function addReplacement(string $replacement, string ...$needle): void
    {
        $needle[] = $replacement; // also include the replacement itself
        foreach ($needle as $entry) {
            $entry = mb_strtolower($entry); // normalize url to compare
            $this->replacements[$replacement][$entry] = true;
        }
    }

    public function findReplacement(string $url): string
    {
        $url = mb_strtolower($url); // normalize url to compare
        foreach ($this->replacements as $replacement => $entries) {
            if (isset($entries[$url])) {
                return $replacement;
            }
        }
        return '';
    }
}
