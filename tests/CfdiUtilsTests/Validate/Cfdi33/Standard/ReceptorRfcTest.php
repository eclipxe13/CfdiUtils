<?php

namespace CfdiUtilsTests\Validate\Cfdi33\Standard;

use CfdiUtils\Nodes\Node;
use CfdiUtils\Utils\Rfc;
use CfdiUtils\Validate\Cfdi33\Standard\ReceptorRfc;
use CfdiUtils\Validate\Status;
use CfdiUtilsTests\Validate\ValidateTestCase;

final class ReceptorRfcTest extends ValidateTestCase
{
    /** @var ReceptorRfc */
    protected $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new ReceptorRfc();
    }

    public function providerValidCases(): array
    {
        return [
            'generic' => [Rfc::RFC_GENERIC],
            'foreign' => [Rfc::RFC_FOREIGN],
            'person' => ['COSC8001137NA'],
            'moral' => ['DIM8701081LA'],
        ];
    }

    /**
     * @param string $rfc
     * @dataProvider providerValidCases
     */
    public function testValidCases(string $rfc)
    {
        $this->comprobante->addChild(new Node('cfdi:Receptor', [
            'Rfc' => $rfc,
        ]));
        $this->runValidate();
        $this->assertFalse($this->asserts->hasErrors());
        $this->assertStatusEqualsCode(Status::ok(), 'RECRFC01');
    }

    public function providerInvalidCases(): array
    {
        return [
            'none' => [null],
            'empty' => [''],
            'wrong' => ['COSC8099137NA'],
        ];
    }

    /**
     * @param string|null $rfc
     * @dataProvider providerInvalidCases
     */
    public function testInvalidCases(?string $rfc)
    {
        $this->comprobante->addChild(new Node('cfdi:Receptor', [
            'Rfc' => $rfc,
        ]));
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'RECRFC01');
    }
}
