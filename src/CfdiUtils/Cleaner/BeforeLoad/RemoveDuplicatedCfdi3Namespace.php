<?php

namespace CfdiUtils\Cleaner\BeforeLoad;

class RemoveDuplicatedCfdi3Namespace implements BeforeLoadCleanerInterface
{
    public function clean(string $content): string
    {
        if (
            str_contains($content, ' xmlns="http://www.sat.gob.mx/cfd/3"')
            && str_contains($content, ' xmlns:cfdi="http://www.sat.gob.mx/cfd/3"')
        ) {
            $content = str_replace(' xmlns="http://www.sat.gob.mx/cfd/3"', '', $content);
        }
        return $content;
    }
}
