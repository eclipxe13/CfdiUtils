<?php
namespace CfdiUtilsTests\Validate;

use CfdiUtils\CadenaOrigen\DOMBuilder;
use CfdiUtils\Cfdi;
use CfdiUtils\Elements\Cfdi33\Comprobante;
use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\Validate\Assert;
use CfdiUtils\Validate\Asserts;
use CfdiUtils\Validate\Contracts\ValidatorInterface;
use CfdiUtils\Validate\Hydrater;
use CfdiUtils\Validate\Status;
use CfdiUtilsTests\TestCase;

abstract class ValidateTestCase extends TestCase
{
    /** @var ValidatorInterface */
    protected $validator;

    /** @var NodeInterface */
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
        $this->hydrater->setXmlResolver($this->newResolver());
        $this->hydrater->setXsltBuilder(new DOMBuilder());
    }

    /**
     * Use this function to allow code analisys tools to perform correctly
     * @return Comprobante
     */
    protected function getComprobante(): Comprobante
    {
        if ($this->comprobante instanceof Comprobante) {
            return $this->comprobante;
        }
        throw new \RuntimeException('The current comprobante node is not a ' . Comprobante::class);
    }

    protected function runValidate()
    {
        $this->validator->validate($this->comprobante, $this->asserts);
    }

    public function assertExplanationContainedInCode(string $expected, string $code)
    {
        if (! $this->asserts->exists($code)) {
            $this->fail("Did not receive actual status for code '$code', it may not exists");
            return;
        }
        $actualAssert = $this->asserts->get($code);
        $this->assertContains($expected, $actualAssert->getExplanation());
    }

    protected function getAssertByCodeOrFail(string $code): Assert
    {
        if (! $this->asserts->exists($code)) {
            $this->fail("Did not receive actual status for code '$code', it may not exists");
            throw new \LogicException("Code $code did not exists");
        }
        return $this->asserts->get($code);
    }

    public function assertStatusEqualsCode(Status $expected, string $code)
    {
        $actualAssert = $this->getAssertByCodeOrFail($code);
        $this->assertStatusEqualsAssert($expected, $actualAssert);
    }

    public function assertStatusEqualsAssert(Status $expected, Assert $assert)
    {
        $actual = $assert->getStatus();
        $this->assertTrue(
            $expected->equalsTo($actual),
            "Status $actual for code {$assert->getCode()} does not match with status $expected"
        );
    }

    public function assertStatusEqualsStatus(Status $expected, Status $current)
    {
        $this->assertEquals($expected, $current, "Status $current does not match with status $expected");
    }

    public function assertContainsCode(string $code)
    {
        $this->assertTrue($this->asserts->exists($code));
    }

    public function assertNotContainsCode(string $code)
    {
        $this->assertFalse($this->asserts->exists($code));
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
        echo PHP_EOL, 'Asserts count: ', $this->asserts->count();
        foreach ($this->asserts as $assert) {
            echo PHP_EOL, vsprintf('%-10s %-8s %s => %s', [
                $assert->getCode(),
                $assert->getStatus(),
                $assert->getTitle(),
                $assert->getExplanation(),
            ]);
        }
        echo PHP_EOL;
    }
}
