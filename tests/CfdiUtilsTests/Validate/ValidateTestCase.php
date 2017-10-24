<?php
namespace CfdiUtilsTests\Validate;

use CfdiUtils\Cfdi;
use CfdiUtils\Elements\Cfdi33\Comprobante;
use CfdiUtils\Validate\Assert;
use CfdiUtils\Validate\Asserts;
use CfdiUtils\Validate\Contracts\ValidatorInterface;
use CfdiUtils\Validate\Hydrater;
use CfdiUtils\Validate\Status;
use CfdiUtils\XmlResolver\XmlResolver;
use CfdiUtilsTests\TestCase;

abstract class ValidateTestCase extends TestCase
{
    /** @var ValidatorInterface */
    protected $validator;

    /** @var Comprobante */
    protected $comprobante;

    /** @var Asserts|Assert[] */
    protected $asserts;

    /** @var Hydrater */
    protected $hydrater;

    protected function setUp()
    {
        parent::setUp();
        $this->comprobante = new Comprobante();
        $this->asserts = new Asserts();
        $this->hydrater = new Hydrater();
        $this->hydrater->setXmlResolver(new XmlResolver());
    }

    protected function runValidate()
    {
        $this->validator->validate($this->comprobante, $this->asserts);
    }

    public function assertStatusEqualsCode(Status $expected, string $code)
    {
        if (! $this->asserts->exists($code)) {
            $this->fail("Did not receive actual status for code '$code', it may not exists");
            return;
        }
        $actualAssert = $this->asserts->get($code);
        $actual = $actualAssert->getStatus();
        $this->assertTrue(
            $expected->equalsTo($actual),
            "Status $actual for code $code does not match with status $expected"
        );
    }

    protected function setupCfdiFile($cfdifile)
    {
        // setup hydrate and re-hydrate the validator
        $content = file_get_contents($this->utilAsset($cfdifile));
        $this->hydrater->setXmlString($content);
        $this->hydrater->hydrate($this->validator);
        // setup comprobante
        $cfdi = Cfdi::newFromString($content);
        $this->comprobante = $cfdi->getNode();
    }

    /**
     * @deprecated Use only when developing test, remove after
     */
    protected function printrAsserts()
    {
        foreach ($this->asserts as $assert) {
            vprintf("\n%-10s %-8s %s => %s", [
                $assert->getCode(),
                $assert->getStatus(),
                $assert->getTitle(),
                $assert->getExplanation(),
            ]);
        }
    }
}
