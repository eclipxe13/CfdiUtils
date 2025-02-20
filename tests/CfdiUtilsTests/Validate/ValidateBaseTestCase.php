<?php

namespace CfdiUtilsTests\Validate;

use CfdiUtils\CadenaOrigen\DOMBuilder;
use CfdiUtils\Certificado\Certificado;
use CfdiUtils\Cfdi;
use CfdiUtils\Nodes\Node;
use CfdiUtils\Nodes\NodeInterface;
use CfdiUtils\Validate\Assert;
use CfdiUtils\Validate\Asserts;
use CfdiUtils\Validate\Contracts\ValidatorInterface;
use CfdiUtils\Validate\Hydrater;
use CfdiUtils\Validate\Status;
use CfdiUtilsTests\TestCase;

abstract class ValidateBaseTestCase extends TestCase
{
    protected ValidatorInterface $validator;

    protected NodeInterface $comprobante;

    protected Asserts $asserts;

    protected Hydrater $hydrater;

    protected function setUp(): void
    {
        parent::setUp();
        $this->comprobante = new Node('root');
        $this->asserts = new Asserts();
        $this->hydrater = new Hydrater();
        $this->hydrater->setXmlResolver($this->newResolver());
        $this->hydrater->setXsltBuilder(new DOMBuilder());
    }

    protected function setUpCertificado(
        array $comprobanteAttributes = [],
        array $emisorAttributes = [],
        string $certificateFile = ''
    ): void {
        $certificateFile = $certificateFile ?: $this->utilAsset('certs/EKU9003173C9.cer');
        $certificado = new Certificado($certificateFile);
        $this->comprobante->addAttributes(array_merge([
            'Certificado' => $certificado->getPemContentsOneLine(),
            'NoCertificado' => $certificado->getSerial(),
        ], $comprobanteAttributes));

        $emisor = $this->comprobante->searchNode('cfdi:Emisor');
        if (null === $emisor) {
            $emisor = new Node('cfdi:Emisor');
            $this->comprobante->addChild($emisor);
        }
        $emisor->addAttributes(array_merge([
            'Nombre' => $certificado->getName(),
            'Rfc' => $certificado->getRfc(),
        ], $emisorAttributes));
    }

    protected function runValidate()
    {
        $this->validator->validate($this->comprobante, $this->asserts);
    }

    public function assertExplanationContainedInCode(string $expected, string $code): void
    {
        if (! $this->asserts->exists($code)) {
            $this->fail("Did not receive actual status for code '$code', it may not exists");
        }
        $actualAssert = $this->asserts->get($code);
        $this->assertStringContainsString($expected, $actualAssert->getExplanation());
    }

    protected function getAssertByCodeOrFail(string $code): Assert
    {
        if (! $this->asserts->exists($code)) {
            $this->fail("Did not receive actual status for code '$code', it may not exists");
        }
        return $this->asserts->get($code);
    }

    public function assertStatusEqualsCode(Status $expected, string $code): void
    {
        $actualAssert = $this->getAssertByCodeOrFail($code);
        $this->assertStatusEqualsAssert($expected, $actualAssert);
    }

    public function assertStatusEqualsAssert(Status $expected, Assert $assert): void
    {
        $actual = $assert->getStatus();
        $this->assertTrue(
            $expected->equalsTo($actual),
            "Status $actual for code {$assert->getCode()} does not match with status $expected"
        );
    }

    public function assertStatusEqualsStatus(Status $expected, Status $current): void
    {
        $this->assertEquals($expected, $current, "Status $current does not match with status $expected");
    }

    public function assertContainsCode(string $code): void
    {
        $this->assertTrue($this->asserts->exists($code));
    }

    public function assertNotContainsCode(string $code): void
    {
        $this->assertFalse($this->asserts->exists($code));
    }

    protected function setupCfdiFile(string $cfdifile)
    {
        // setup hydrate and re-hydrate the validator
        $content = strval(file_get_contents($this->utilAsset($cfdifile)));
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
