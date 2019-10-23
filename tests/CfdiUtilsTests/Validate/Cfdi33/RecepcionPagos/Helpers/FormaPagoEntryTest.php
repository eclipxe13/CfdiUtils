<?php

namespace CfdiUtilsTests\Validate\Cfdi33\RecepcionPagos\Helpers;

use CfdiUtils\Validate\Cfdi33\RecepcionPagos\Helpers\FormaPagoEntry;
use PHPUnit\Framework\TestCase;

class FormaPagoEntryTest extends TestCase
{
    /**
     * @param string $key
     * @param string $description
     * @param bool $allowSenderRfc
     * @param bool $allowSenderAccount
     * @param string $senderAccountPattern
     * @param bool $allowReceiverRfc
     * @param bool $allowReceiverAccount
     * @param string $receiverAccountPattern
     * @param bool $allowPaymentSignature
     * @testWith ["foo", "bar", false, false, "", false, false, "", false]
     *           ["foo", "bar", true, false, "", false, false, "", false]
     *           ["foo", "bar", false, true, "", false, false, "", false]
     *           ["foo", "bar", false, true, "/[0-9]+/", false, false, "", false]
     *           ["foo", "bar", false, false, "/[0-9]+/", false, false, "", false]
     *           ["foo", "bar", false, false, "", true, false, "", false]
     *           ["foo", "bar", false, false, "", false, true, "", false]
     *           ["foo", "bar", false, false, "", false, true, "/[0-9]+/", false]
     *           ["foo", "bar", false, false, "", false, false, "/[0-9]+/", false]
     *           ["foo", "bar", false, false, "", false, false, "", true]
     */
    public function testConstructValidObject(
        string $key,
        string $description,
        bool $allowSenderRfc,
        bool $allowSenderAccount,
        string $senderAccountPattern,
        bool $allowReceiverRfc,
        bool $allowReceiverAccount,
        string $receiverAccountPattern,
        bool $allowPaymentSignature
    ) {
        $paymentType = new FormaPagoEntry(
            $key,
            $description,
            $allowSenderRfc,
            $allowSenderAccount,
            $senderAccountPattern,
            $allowReceiverRfc,
            $allowReceiverAccount,
            $receiverAccountPattern,
            $allowPaymentSignature
        );
        $expectedSenderAccountPattern = '/^$/';
        if ($allowSenderAccount && '' !== $senderAccountPattern) {
            $expectedSenderAccountPattern = $senderAccountPattern;
        }
        $expectedReceiverAccountPattern = '/^$/';
        if ($allowReceiverAccount && '' !== $receiverAccountPattern) {
            $expectedReceiverAccountPattern = $receiverAccountPattern;
        }
        $this->assertSame($key, $paymentType->key());
        $this->assertSame($description, $paymentType->description());
        $this->assertSame($allowSenderRfc, $paymentType->allowSenderRfc());
        $this->assertSame($allowSenderAccount, $paymentType->allowSenderAccount());
        $this->assertSame($expectedSenderAccountPattern, $paymentType->senderAccountPattern());
        $this->assertSame($allowReceiverRfc, $paymentType->allowReceiverRfc());
        $this->assertSame($allowReceiverAccount, $paymentType->allowReceiverAccount());
        $this->assertSame($expectedReceiverAccountPattern, $paymentType->receiverAccountPattern());
        $this->assertSame($allowPaymentSignature, $paymentType->allowPaymentSignature());
    }

    public function testConstructWithoutKey()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage(' key ');

        new FormaPagoEntry('', 'bar', false, false, '', false, false, '', false);
    }

    public function testConstructWithoutDescription()
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage(' description ');

        new FormaPagoEntry('foo', '', false, false, '', false, false, '', false);
    }
}
