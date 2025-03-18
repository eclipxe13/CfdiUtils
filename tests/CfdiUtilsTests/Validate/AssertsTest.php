<?php

namespace CfdiUtilsTests\Validate;

use CfdiUtils\Validate\Assert;
use CfdiUtils\Validate\Asserts;
use CfdiUtils\Validate\Status;
use PHPUnit\Framework\TestCase;

final class AssertsTest extends TestCase
{
    public function testConstructor(): void
    {
        $asserts = new Asserts();
        $this->assertInstanceOf(\Countable::class, $asserts);
        $this->assertInstanceOf(\Traversable::class, $asserts);
        $this->assertCount(0, $asserts);
        $this->assertSame(false, $asserts->hasErrors());
    }

    public function testMustStop(): void
    {
        $asserts = new Asserts();
        // initialized on false
        $this->assertSame(false, $asserts->mustStop());
        // set to true return previous status false
        $this->assertSame(false, $asserts->mustStop(true));
        // current status is true
        $this->assertSame(true, $asserts->mustStop());
        // set true return current status true
        $this->assertSame(true, $asserts->mustStop(true));
        // set false return current status true
        $this->assertSame(true, $asserts->mustStop(false));
        // check again current status false
        $this->assertSame(false, $asserts->mustStop());
    }

    public function testAddError(): void
    {
        $asserts = new Asserts();
        $first = new Assert('TEST', 'test', Status::error());

        $asserts->add($first);

        $this->assertCount(1, $asserts);
        $this->assertSame(true, $asserts->hasErrors());
        $this->assertSame(true, $asserts->hasStatus(Status::error()));
        $this->assertSame($first, $asserts->getFirstStatus(Status::error()));

        $second = new Assert('TEST', 'test', Status::ok());

        // this will set the new object in the index TEST without change the previous status
        $asserts->add($second);
        $this->assertCount(1, $asserts);
        $this->assertSame('test', $first->getTitle());
        $this->assertEquals(Status::error(), $first->getStatus());
        $this->assertNull($asserts->getFirstStatus(Status::error()));
        $this->assertSame($second, $asserts->getFirstStatus(Status::ok()));

        // this will not remove anything since this object will not be found
        $asserts->remove($first);
        $this->assertCount(1, $asserts);

        // but now it will remove it since the same object is in the collection
        $asserts->remove($second);
        $this->assertCount(0, $asserts);
    }

    public function testPutAndPutStatus(): void
    {
        $asserts = new Asserts();

        // test insert by put
        $first = $asserts->put('X01');
        $this->assertCount(1, $asserts);

        // test insert by put
        $second = $asserts->put('X02');
        $this->assertCount(2, $asserts);

        // test insert by put on an existing key
        $retrievedOnOverride = $asserts->put('X01', 'title', Status::warn(), 'explanation');
        $this->assertCount(2, $asserts);
        $this->assertSame($first, $retrievedOnOverride);
        $this->assertEquals('title', $first->getTitle());
        $this->assertEquals('explanation', $first->getExplanation());
        $this->assertEquals(Status::warn(), $first->getStatus());
        $this->assertSame($first, $asserts->get('X01'));

        // test put status on an existing key
        $asserts->putStatus('X02', Status::ok(), 'baz baz baz');
        $this->assertEquals('baz baz baz', $second->getExplanation());
        $this->assertEquals(Status::ok(), $second->getStatus());
        $this->assertSame($second, $asserts->get('X02'));

        // test put status on a non existing key
        $third = $asserts->putStatus('X03', Status::error(), 'third element');
        $this->assertCount(3, $asserts);
        $this->assertEquals('', $third->getTitle());
        $this->assertEquals('third element', $third->getExplanation());
        $this->assertEquals(Status::error(), $third->getStatus());
        $this->assertSame($third, $asserts->get('X03'));
    }

    public function testGetWithNotExistentStatus(): void
    {
        $asserts = new Asserts();
        $this->expectException(\RuntimeException::class);
        $asserts->get('X02');
    }

    public function testGetByStatus(): void
    {
        $oks = [
            'OK01' => Status::ok(),
            'OK02' => Status::ok(),
        ];
        $errors = [
            'ERROR01' => Status::error(),
            'ERROR02' => Status::error(),
        ];
        $warnings = [
            'WARN01' => Status::warn(),
            'WARN02' => Status::warn(),
        ];
        $nones = [
            'NONE01' => Status::none(),
            'NONE02' => Status::none(),
        ];
        $assertsContents = $oks + $errors + $warnings + $nones;
        $asserts = new Asserts();
        foreach ($assertsContents as $code => $status) {
            $asserts->putStatus($code, $status);
        }

        $this->assertCount(8, $asserts);
        $this->assertEquals(array_keys($oks), array_keys($asserts->byStatus(Status::ok())));
        $this->assertEquals(array_keys($errors), array_keys($asserts->byStatus(Status::error())));
        $this->assertEquals(array_keys($warnings), array_keys($asserts->byStatus(Status::warn())));
        $this->assertEquals(array_keys($nones), array_keys($asserts->byStatus(Status::none())));
        $this->assertEquals(array_keys($oks), array_keys($asserts->oks()));
        $this->assertEquals(array_keys($errors), array_keys($asserts->errors()));
        $this->assertEquals(array_keys($warnings), array_keys($asserts->warnings()));
        $this->assertEquals(array_keys($nones), array_keys($asserts->nones()));
    }

    public function testRemoveByCode(): void
    {
        $asserts = new Asserts();
        $asserts->putStatus('XXX');

        $this->assertCount(1, $asserts);

        $asserts->removeByCode('FOO');
        $this->assertCount(1, $asserts);

        $asserts->removeByCode('XXX');
        $this->assertCount(0, $asserts);
    }

    public function testRemoveAll(): void
    {
        $asserts = new Asserts();
        foreach (range(1, 5) as $i) {
            $asserts->putStatus(strval($i));
        }
        $this->assertCount(5, $asserts);
        $asserts->removeAll();
        $this->assertCount(0, $asserts);
    }

    public function testImport(): void
    {
        $source = new Asserts();
        $source->mustStop(true);
        foreach (range(1, 5) as $i) {
            $source->putStatus(strval($i));
        }

        $destination = new Asserts();
        $destination->import($source);
        $this->assertCount(5, $destination);

        // when importing the assert objects are cloned (not the same but equal)
        $firstSource = $source->get('1');
        $firstDestination = $destination->get('1');
        $this->assertNotSame($firstSource, $firstDestination);
        $this->assertEquals($firstSource, $firstDestination);
        $this->assertSame($source->mustStop(), $destination->mustStop());

        // import again but with muststop as false
        $source->mustStop(false);
        $destination->import($source);
        $this->assertCount(5, $destination);
        $this->assertSame($source->mustStop(), $destination->mustStop());
    }

    public function testTraversable(): void
    {
        $asserts = new Asserts();
        $first = $asserts->putStatus('first');
        $second = $asserts->putStatus('second');
        $third = $asserts->putStatus('third');

        $currentAsserts = [];
        foreach ($asserts as $assert) {
            $currentAsserts[] = $assert;
        }

        $this->assertSame($first, $currentAsserts[0]);
        $this->assertSame($second, $currentAsserts[1]);
        $this->assertSame($third, $currentAsserts[2]);
    }
}
