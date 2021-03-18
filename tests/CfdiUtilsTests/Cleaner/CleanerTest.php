<?php

namespace CfdiUtilsTests\Cleaner;

use CfdiUtils\Cleaner\Cleaner;
use CfdiUtils\Cleaner\CleanerException;
use CfdiUtilsTests\TestCase;

final class CleanerTest extends TestCase
{
    public function testConstructorWithEmptyText()
    {
        $cleaner = new Cleaner('');

        $this->expectException(CleanerException::class);
        $cleaner->load('');
    }

    public function testConstructorWithNonCFDI()
    {
        $cleaner = new Cleaner('');
        $this->expectException(CleanerException::class);

        $cleaner->load('<node></node>');
    }

    public function testConstructorWithBadVersion()
    {
        $this->expectException(CleanerException::class);
        new Cleaner('<?xml version="1.0" encoding="UTF-8"?>
            <' . 'cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" version="3.15" />
        ');
    }

    public function testConstructorWithoutInvalidXml()
    {
        $this->expectException(CleanerException::class);

        new Cleaner('<' . 'node>');
    }

    public function testConstructorWithoutVersion()
    {
        $this->expectException(CleanerException::class);
        $this->expectExceptionMessage('version');

        new Cleaner('<?xml version="1.0" encoding="UTF-8"?>
            <' . 'cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" />
        ');
    }

    public function testConstructorWithMinimalCompatibilityVersion32()
    {
        $cleaner = new Cleaner('<?xml version="1.0" encoding="UTF-8"?>
            <' . 'cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" version="3.2" />
        ');
        $this->assertInstanceOf(Cleaner::class, $cleaner, 'Cleaner created with minimum compatibility');
    }

    public function testConstructorWithMinimalCompatibilityVersion33()
    {
        $cleaner = new Cleaner('<?xml version="1.0" encoding="UTF-8"?>
            <' . 'cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" Version="3.3" />
        ');
        $this->assertInstanceOf(Cleaner::class, $cleaner, 'Cleaner created with minimum compatibility');
    }

    public function testLoadWithDefaultBeforeLoadCleaner()
    {
        $withErrors = $this->utilAsset('cleaner/v32-dirty-errors.xml');
        $justDirty = $this->utilAsset('cleaner/v32-dirty.xml');
        $cleaner = new Cleaner(strval(file_get_contents($withErrors)));
        $this->assertXmlStringEqualsXmlFile(
            $justDirty,
            $cleaner->retrieveXml(),
            'Compare that the document was cleaned before load'
        );
    }

    public function testCleanOnDetail()
    {
        $basefile = $this->utilAsset('cleaner/v32-dirty.xml');
        $step1 = $this->utilAsset('cleaner/v32-no-addenda.xml');
        $step2 = $this->utilAsset('cleaner/v32-no-incomplete-schemalocations.xml');
        $step3 = $this->utilAsset('cleaner/v32-no-nonsat-nodes.xml');
        $step4 = $this->utilAsset('cleaner/v32-no-nonsat-schemalocations.xml');
        $step5 = $this->utilAsset('cleaner/v32-no-nonsat-xmlns.xml');
        $step6 = $this->utilAsset('cleaner/v32-schemalocations-replacements.xml');
        foreach ([$basefile, $step1, $step3, $step2, $step4, $step5, $step6] as $filename) {
            $this->assertFileExists($basefile, "The file $filename for testing does not exists");
        }
        $cleaner = new Cleaner(strval(file_get_contents($basefile)));
        $this->assertXmlStringEqualsXmlFile(
            $basefile,
            $cleaner->retrieveXml(),
            'Compare that the document was loaded without modifications'
        );

        $cleaner->removeAddenda();
        $this->assertXmlStringEqualsXmlFile(
            $step1,
            $cleaner->retrieveXml(),
            'Compare that addenda was removed'
        );

        $cleaner->removeIncompleteSchemaLocations();
        $this->assertXmlStringEqualsXmlFile(
            $step2,
            $cleaner->retrieveXml(),
            'Compare that incomplete schemaLocations were removed'
        );

        $cleaner->removeNonSatNSNodes();
        $this->assertXmlStringEqualsXmlFile(
            $step3,
            $cleaner->retrieveXml(),
            'Compare that non SAT nodes were removed'
        );

        $cleaner->removeNonSatNSschemaLocations();
        $this->assertXmlStringEqualsXmlFile(
            $step4,
            $cleaner->retrieveXml(),
            'Compare that non SAT schemaLocations were removed'
        );

        $cleaner->removeUnusedNamespaces();
        $this->assertXmlStringEqualsXmlFile(
            $step5,
            $cleaner->retrieveXml(),
            'Compare that xmlns definitions were removed'
        );

        $cleaner->fixKnownSchemaLocationsXsdUrls();
        $this->assertXmlStringEqualsXmlFile(
            $step6,
            $cleaner->retrieveXml(),
            'Compare that schemaLocations definitions were changed'
        );

        $this->assertXmlStringEqualsXmlFile(
            $step6,
            Cleaner::staticClean(strval(file_get_contents($basefile))),
            'Check static method for cleaning is giving the same results as detailed execution'
        );
    }

    public function testRetrieveDocumentReturnDifferentInstances()
    {
        $cleaner = new Cleaner('<?xml version="1.0" encoding="UTF-8"?>
            <' . 'cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" Version="3.3" />
        ');

        $domFirst = $cleaner->retrieveDocument();
        $domSecond = $cleaner->retrieveDocument();
        $this->assertNotSame($domFirst, $domSecond);
        $this->assertXmlStringEqualsXmlString($domFirst, $domSecond);
    }

    public function testRemoveNonSatNSschemaLocationsWithNotEvenSchemaLocationContents()
    {
        $xmlContent = '<?xml version="1.0" encoding="UTF-8"?>
            <' . 'cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" Version="3.3"
            xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
            xsi:schemaLocation="http://example.com/foo http://example.com/foo.xsd http://example.com/bar"
            />
        ';
        $cleaner = new Cleaner($xmlContent);

        $this->expectException(CleanerException::class);
        $this->expectExceptionMessage('must have even number of URIs');
        $cleaner->removeNonSatNSschemaLocations();
    }

    public function testRemoveNonSatNSschemaLocationsRemoveEmptySchemaLocation()
    {
        $xmlContent = '<?xml version="1.0" encoding="UTF-8"?>
            <' . 'cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" Version="3.3"
            xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
            xsi:schemaLocation="http://example.com/foo http://example.com/foo.xsd"
            />
        ';
        $xmlExpectedContent = '<?xml version="1.0" encoding="UTF-8"?>
            <' . 'cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" Version="3.3"
            xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
            />
        ';
        $cleaner = new Cleaner($xmlContent);

        $cleaner->removeNonSatNSschemaLocations();
        $this->assertXmlStringEqualsXmlString($xmlExpectedContent, $cleaner->retrieveXml());
    }

    public function testCollapseComprobanteComplemento()
    {
        $source = <<<'EOT'
<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" Version="3.3">
    <cfdi:Complemento><a/><b/></cfdi:Complemento>
    <cfdi:Complemento><c/><d/></cfdi:Complemento>
    <cfdi:Complemento><cfdi:Complemento info="malformed"/></cfdi:Complemento>
    <cfdi:Complemento><e/><f/></cfdi:Complemento>
</cfdi:Comprobante>
EOT;
        $expected = <<<'EOT'
<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" Version="3.3">
    <cfdi:Complemento>
        <a/><b/>
        <c/><d/>
        <cfdi:Complemento info="malformed"/>
        <e/><f/>
    </cfdi:Complemento>
</cfdi:Comprobante>
EOT;
        $cleaner = new Cleaner($source);
        $cleaner->collapseComprobanteComplemento();
        $this->assertXmlStringEqualsXmlString($expected, $cleaner->retrieveXml());
    }
}
