<?php

namespace CfdiUtils\Cleaner\BeforeLoad;

class ChangeXmlnsSchemaLocation implements BeforeLoadCleanerInterface
{
    public function clean(string $content): string
    {
        return str_replace(' xmlns:schemaLocation="', ' xsi:schemaLocation="', $content);
    }
}
