<?php

namespace CfdiUtils\XmlResolver;

use CfdiUtils\CadenaOrigen\CfdiDefaultLocations;
use CfdiUtils\Certificado\CerRetriever;
use XmlResourceRetriever\Downloader\DownloaderInterface;
use XmlResourceRetriever\Downloader\PhpDownloader;
use XmlResourceRetriever\RetrieverInterface;
use XmlResourceRetriever\XsdRetriever;
use XmlResourceRetriever\XsltRetriever;

/**
 * XmlResolver - Class to download xml resources from internet to local paths
 */
class XmlResolver
{
    /** @var string */
    private $localPath = '';

    /** @var DownloaderInterface */
    private $downloader;

    const TYPE_XSD = 'XSD';

    const TYPE_XSLT = 'XSLT';

    const TYPE_CER = 'CER';

    /**
     * XmlResolver constructor.
     * @see setLocalPath
     * @see setDownloaderInterface
     * @param string|null $localPath values: '' => no resolve, null => use default path, anything else is the path
     * @param DownloaderInterface|null $downloader
     */
    public function __construct(string $localPath = null, DownloaderInterface $downloader = null)
    {
        $this->setLocalPath($localPath);
        $this->setDownloader($downloader);
    }

    public static function defaultLocalPath(): string
    {
        // drop 3 dirs: src/CfdiUtils/XmlResolver
        return dirname(__DIR__, 3) . '/build/resources/';
    }

    /**
     * Set the localPath to the specified value.
     * If $localPath is null then the value of defaultLocalPath is used.
     *
     * @param string|null $localPath values: '' => no resolve, null => default path, anything else is the path
     */
    public function setLocalPath(string $localPath = null)
    {
        if (null === $localPath) {
            $localPath = $this->defaultLocalPath();
        }
        $this->localPath = $localPath;
    }

    /**
     * Return the configured localpath.
     * An empty string means that it is not configured and method resolve will return the same url as received
     * @see resolve
     * @return string
     */
    public function getLocalPath(): string
    {
        return $this->localPath;
    }

    /**
     * Return when a local path has been set.
     *
     * @return bool
     */
    public function hasLocalPath(): bool
    {
        return ('' !== $this->localPath);
    }

    /**
     * Set the downloader object.
     * If send a NULL value the object return by defaultDownloader will be set.
     *
     * @param DownloaderInterface|null $downloader
     */
    public function setDownloader(DownloaderInterface $downloader = null)
    {
        if (null === $downloader) {
            $downloader = $this->defaultDownloader();
        }
        $this->downloader = $downloader;
    }

    public static function defaultDownloader(): DownloaderInterface
    {
        return new PhpDownloader();
    }

    public function getDownloader(): DownloaderInterface
    {
        return $this->downloader;
    }

    /**
     * Resolve a resource to a local path.
     * If it does not have a localPath then it will return the exact same resource
     *
     * @param string $resource The url
     * @param string $type Allows XSD, XSLT and CER
     * @return string
     */
    public function resolve(string $resource, string $type = ''): string
    {
        if (! $this->hasLocalPath()) {
            return $resource;
        }
        if ('' === $type) {
            $type = $this->obtainTypeFromUrl($resource);
        } else {
            $type = strtoupper($type);
        }
        $retriever = $this->newRetriever($type);
        if (null === $retriever) {
            throw new \RuntimeException("Unable to handle the resource (Type: $type) $resource");
        }
        $local = $retriever->buildPath($resource);
        if (! file_exists($local)) {
            $retriever->retrieve($resource);
        }
        return $local;
    }

    public function obtainTypeFromUrl(string $url): string
    {
        if ($this->isResourceExtension($url, 'xsd')) {
            return static::TYPE_XSD;
        }
        if ($this->isResourceExtension($url, 'xslt')) {
            return static::TYPE_XSLT;
        }
        if ($this->isResourceExtension($url, 'cer')) {
            return static::TYPE_CER;
        }
        return '';
    }

    private function isResourceExtension(string $resource, string $extension): bool
    {
        $extension = '.' . $extension;
        $length = strlen($resource);
        $extLength = strlen($extension);
        if ($extLength > $length) {
            return false;
        }
        return (0 === substr_compare(strtolower($resource), $extension, $length - $extLength, $extLength));
    }

    /**
     * Create a new retriever depending on the type parameter, only allow TYPE_XSLT and TYPE_XSD
     * @param string $type
     * @return RetrieverInterface|null
     */
    public function newRetriever(string $type)
    {
        if (! $this->hasLocalPath()) {
            throw new \LogicException('Cannot create a retriever if no local path was found');
        }
        if (static::TYPE_XSLT === $type) {
            return $this->newXsltRetriever();
        }
        if (static::TYPE_XSD === $type) {
            return $this->newXsdRetriever();
        }
        if (static::TYPE_CER === $type) {
            return $this->newCerRetriever();
        }
        return null;
    }

    public function newXsltRetriever(): XsltRetriever
    {
        return new XsltRetriever($this->getLocalPath(), $this->getDownloader());
    }

    public function newXsdRetriever(): XsdRetriever
    {
        return new XsdRetriever($this->getLocalPath(), $this->getDownloader());
    }

    public function newCerRetriever(): CerRetriever
    {
        return new CerRetriever($this->getLocalPath(), $this->getDownloader());
    }

    public function resolveCadenaOrigenLocation(string $version)
    {
        return $this->resolve(CfdiDefaultLocations::location($version), self::TYPE_XSLT);
    }
}
