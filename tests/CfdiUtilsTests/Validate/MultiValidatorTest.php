<?php
namespace CfdiUtilsTests\Validate;

use CfdiUtils\Nodes\Node;
use CfdiUtils\Validate\Asserts;
use CfdiUtils\Validate\Hydrater;
use CfdiUtils\Validate\MultiValidator;
use CfdiUtils\Validate\Status;
use CfdiUtilsTests\TestCase;
use CfdiUtilsTests\Validate\FakeObjects\ImplementationRequireXmlResolverInterface;
use CfdiUtilsTests\Validate\FakeObjects\ImplementationRequireXmlStringInterface;
use CfdiUtilsTests\Validate\FakeObjects\ImplementationValidatorInterface;

class MultiValidatorTest extends TestCase
{
    public function testConstruct()
    {
        $validator = new MultiValidator('3.2');
        $this->assertInstanceOf(\Countable::class, $validator);
        $this->assertInstanceOf(\Traversable::class, $validator);
        $this->assertSame('3.2', $validator->getVersion());
        $this->assertTrue($validator->canValidateCfdiVersion('3.2'));
        $this->assertFalse($validator->canValidateCfdiVersion('3.3'));
        $this->assertCount(0, $validator);
    }

    public function testValidate()
    {
        $validator = new MultiValidator('3.3');
        $first = new ImplementationValidatorInterface();
        $first->assertsToImport = new Asserts();
        $first->assertsToImport->putStatus('FIRST', Status::ok());

        $last = new ImplementationValidatorInterface();
        $last->assertsToImport = new Asserts();
        $last->assertsToImport->putStatus('LAST', Status::error());

        $validator->addMulti(...[
            $first,
            $last,
        ]);

        $asserts = new Asserts();
        $validator->validate(new Node('sample'), $asserts);

        // test that all children has been executed
        /** @var ImplementationValidatorInterface $child */
        foreach ($validator as $child) {
            $this->assertTrue($child->enterValidateMethod);
        }

        $this->assertCount(2, $asserts);
        $this->assertTrue($asserts->exists('FIRST'));
        $this->assertTrue($asserts->exists('LAST'));
        $this->assertFalse($asserts->mustStop());
    }

    public function testValidateChangesMustStopFlag()
    {
        $first = new ImplementationValidatorInterface();
        $first->onValidateSetMustStop = true;
        $last = new ImplementationValidatorInterface();
        $asserts = new Asserts();

        $multiValidator = new MultiValidator('3.3');
        $multiValidator->add($first);
        $multiValidator->add($last);
        $multiValidator->validate(new Node('sample'), $asserts);

        // test that only first element was executed
        $this->assertTrue($asserts->mustStop());
        $this->assertFalse($last->enterValidateMethod);
    }

    public function testValidateSkipsOtherVersions()
    {
        $first = new ImplementationValidatorInterface();
        $last = new ImplementationValidatorInterface();
        $last->version = '3.2';
        $asserts = new Asserts();

        $multiValidator = new MultiValidator('3.3');
        $multiValidator->add($first);
        $multiValidator->add($last);
        $multiValidator->validate(new Node('sample'), $asserts);

        // test that only first element was executed
        $this->assertFalse($last->enterValidateMethod);
    }

    public function testHydrate()
    {
        $hydrater = new Hydrater();
        $xmlString = '<root />';
        $xmlResolver = $this->newResolver();
        $hydrater->setXmlString($xmlString);
        $hydrater->setXmlResolver($xmlResolver);

        $requireXmlResolver = new ImplementationRequireXmlResolverInterface();
        $requireXmlString = new ImplementationRequireXmlStringInterface();
        $multiValidator = new MultiValidator('3.3');
        $multiValidator->addMulti($requireXmlResolver, $requireXmlString);

        $multiValidator->hydrate($hydrater);

        $this->assertSame($requireXmlResolver->getXmlResolver(), $xmlResolver);
        $this->assertSame($requireXmlString->getXmlString(), $xmlString);
    }

    /*
     * Collection tests
     */

    public function testAddAddMulti()
    {
        $validator = new MultiValidator('3.3');

        $first = new ImplementationValidatorInterface();
        $validator->add($first);
        $this->assertCount(1, $validator);

        $validator->addMulti(...[
            new ImplementationValidatorInterface(),
            new ImplementationValidatorInterface(),
            new ImplementationValidatorInterface(),
        ]);
        $this->assertCount(4, $validator);
    }

    public function testExists()
    {
        $child = new ImplementationValidatorInterface();
        $validator = new MultiValidator('3.3');
        $validator->add($child);
        $this->assertTrue($validator->exists($child));
        $this->assertFalse($validator->exists(new ImplementationValidatorInterface()));
    }

    public function testRemove()
    {
        $child = new ImplementationValidatorInterface();
        $validator = new MultiValidator('3.3');
        $validator->add($child);
        $this->assertTrue($validator->exists($child));

        $validator->remove($child);
        $this->assertFalse($validator->exists($child));
        $this->assertCount(0, $validator);
    }

    public function testRemoveAll()
    {
        $validator = new MultiValidator('3.3');
        $validator->addMulti(...[
            new ImplementationValidatorInterface(),
            new ImplementationValidatorInterface(),
            new ImplementationValidatorInterface(),
        ]);
        $this->assertCount(3, $validator);

        $validator->removeAll();
        $this->assertCount(0, $validator);
    }

    public function testTraversable()
    {
        $validator = new MultiValidator('3.3');
        $first = new ImplementationValidatorInterface();
        $second = new ImplementationValidatorInterface();
        $third = new ImplementationValidatorInterface();
        $validator->addMulti(...[
            $first,
            $second,
            $third,
        ]);

        $current = [];
        foreach ($validator as $item) {
            $current[] = $item;
        }

        $expected = [$first, $second, $third];
        foreach ($expected as $index => $value) {
            $this->assertSame($value, $current[$index]);
        }
    }
}
