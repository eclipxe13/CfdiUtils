<?php
namespace CfdiUtilsTests\Validate\FakeObjects;

use CfdiUtils\Nodes\Node;
use CfdiUtils\Validate\Asserts;
use CfdiUtils\Validate\Contracts\ValidatorInterface;

class ImplementationValidatorInterface implements ValidatorInterface
{
    /** @var string */
    public $version = '3.3';
    /** @var bool */
    public $onValidateSetMustStop = false;
    /** @var bool */
    public $enterValidateMethod = false;

    /** @var Asserts|null */
    public $assertsToImport = null;

    public function validate(Node $comprobante, Asserts $asserts)
    {
        if ($this->assertsToImport instanceof Asserts) {
            $asserts->import($this->assertsToImport);
        }
        $this->enterValidateMethod = true;
        $asserts->mustStop($this->onValidateSetMustStop);
    }

    public function canValidateCfdiVersion(string $version): bool
    {
        return $version === $this->version;
    }
}
