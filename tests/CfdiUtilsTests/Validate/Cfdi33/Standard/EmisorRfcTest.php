<?php

namespace CfdiUtilsTests\Validate\Cfdi33\Standard;

use CfdiUtils\Nodes\Node;
use CfdiUtils\Utils\Rfc;
use CfdiUtils\Validate\Cfdi33\Standard\EmisorRfc;
use CfdiUtils\Validate\Status;
use CfdiUtilsTests\Validate\Validate33TestCase;

final class EmisorRfcTest extends Validate33TestCase
{
    /** @var EmisorRfc */
    protected $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new EmisorRfc();
    }

    public function providerValidCases(): array
    {
        return [
            'person' => ['COSC8001137NA'],
            'moral' => ['DIM8701081LA'],
        ];
    }

    /**
     * @dataProvider providerValidCases
     */
    public function testValidCases(string $rfc): void
    {
        $this->comprobante->addChild(new Node('cfdi:Emisor', [
            'Rfc' => $rfc,
        ]));
        $this->runValidate();
        $this->assertFalse($this->asserts->hasErrors());
        $this->assertStatusEqualsCode(Status::ok(), 'EMISORRFC01');
    }

    public function providerInvalidCases(): array
    {
        return [
            'none' => [null],
            'empty' => [''],
            'wrong' => ['COSC8099137NA'],
            'generic' => [Rfc::RFC_GENERIC],
            'foreign' => [Rfc::RFC_FOREIGN],
        ];
    }

    /**
     * @dataProvider providerInvalidCases
     */
    public function testInvalidCases(?string $rfc): void
    {
        $this->comprobante->addChild(new Node('cfdi:Emisor', [
            'Rfc' => $rfc,
        ]));
        $this->runValidate();
        $this->assertStatusEqualsCode(Status::error(), 'EMISORRFC01');
    }
}
