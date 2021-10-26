<?php

namespace CfdiUtils\TimbreFiscalDigital;

use CfdiUtils\CadenaOrigen\DOMBuilder;
use CfdiUtils\CadenaOrigen\XsltBuilderInterface;
use CfdiUtils\CadenaOrigen\XsltBuilderPropertyInterface;
use CfdiUtils\CadenaOrigen\XsltBuilderPropertyTrait;
use CfdiUtils\XmlResolver\XmlResolver;
use CfdiUtils\XmlResolver\XmlResolverPropertyInterface;
use CfdiUtils\XmlResolver\XmlResolverPropertyTrait;

class TfdCadenaDeOrigen implements XmlResolverPropertyInterface, XsltBuilderPropertyInterface
{
    use XmlResolverPropertyTrait;
    use XsltBuilderPropertyTrait;

    const TFD_10 = 'http://www.sat.gob.mx/sitio_internet/timbrefiscaldigital/cadenaoriginal_TFD_1_0.xslt';

    const TFD_11 = 'http://www.sat.gob.mx/sitio_internet/cfd/TimbreFiscalDigital/cadenaoriginal_TFD_1_1.xslt';

    public function __construct(XmlResolver $xmlResolver = null, XsltBuilderInterface $xsltBuilder = null)
    {
        $this->setXmlResolver($xmlResolver ? : new XmlResolver());
        $this->setXsltBuilder($xsltBuilder ? : new DOMBuilder());
    }

    public function build(string $tdfXmlString, string $version = ''): string
    {
        // this will throw an exception if no resolver is set
        $resolver = $this->getXmlResolver();

        // obtain version if it was not set
        if ('' === $version) {
            $version = (new TfdVersion())->getFromXmlString($tdfXmlString);
        }

        // get remote location of the xslt
        $defaultXslt = $this->xsltLocation($version);

        // get local xslt
        $localXsd = $resolver->resolve($defaultXslt);

        // return transformation
        return $this->getXsltBuilder()->build($tdfXmlString, $localXsd);
    }

    public static function xsltLocation(string $version): string
    {
        if ('1.1' === $version) {
            return static::TFD_11;
        }
        if ('1.0' === $version) {
            return static::TFD_10;
        }
        throw new \UnexpectedValueException("Cannot get the xslt location for version '$version'");
    }
}
