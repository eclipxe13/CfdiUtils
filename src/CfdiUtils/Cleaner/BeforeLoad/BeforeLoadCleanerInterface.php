<?php

namespace CfdiUtils\Cleaner\BeforeLoad;

interface BeforeLoadCleanerInterface
{
    public function clean(string $content): string;
}
