<?php
namespace CfdiUtils\TimbreFiscalDigital;

use CfdiUtils\CadenaOrigen\CadenaOrigenBuilder;
use CfdiUtils\XmlResolver\XmlResolver;
use CfdiUtils\XmlResolver\XmlResolverPropertyInterface;
use CfdiUtils\XmlResolver\XmlResolverPropertyTrait;

class TfdCadenaDeOrigen implements XmlResolverPropertyInterface
{
    use XmlResolverPropertyTrait;

    /** @var CadenaOrigenBuilder */
    private $builder;

    const TFD_10 = 'http://www.sat.gob.mx/sitio_internet/timbrefiscaldigital/cadenaoriginal_TFD_1_0.xslt';
    const TFD_11 = 'http://www.sat.gob.mx/sitio_internet/cfd/timbrefiscaldigital/cadenaoriginal_TFD_1_1.xslt';

    public function __construct(XmlResolver $xmlResolver = null)
    {
        $this->setXmlResolver($xmlResolver ? : new XmlResolver());
        $this->builder = new CadenaOrigenBuilder();
    }

    public function build(string $tdfXmlString, string $version = ''): string
    {
        // obtain version if it was not set
        if ('' === $version) {
            $version = TfdVersion::fromXmlString($tdfXmlString);
        }

        // get remote location of the xslt
        $defaultXslt = $this->xsltLocation($version);

        // get local xslt
        $localXsd = $this->getXmlResolver()->resolve($defaultXslt);

        // return transformation
        return $this->builder->build($tdfXmlString, $localXsd);
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
