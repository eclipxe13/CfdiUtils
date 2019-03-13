<?php
namespace CfdiUtils\OpenSSL;

use CfdiUtils\Utils\Internal\TemporaryFile;

class OpenSSL
{
    /** @var Caller */
    private $caller;

    public function __construct(string $opensslCommand = '')
    {
        $this->caller = new Caller($opensslCommand);
    }

    public function getOpenSSLCommand(): string
    {
        return $this->caller->getExecutable();
    }

    public function readPemFile(string $pemFile): PemContainer
    {
        $this->checkInputFile($pemFile);
        return $this->readPemContents(strval(file_get_contents($pemFile)));
    }

    public function readPemContents(string $contents): PemContainer
    {
        $extractor = new PemExtractor($contents);
        $pemContainer = $extractor->pemContainer();
        return $pemContainer;
    }

    public function derCerConvertPhp(string $derContent): string
    {
        return '-----BEGIN CERTIFICATE-----' . PHP_EOL
            . chunk_split(base64_encode($derContent), 64, PHP_EOL)
            . '-----END CERTIFICATE-----';
    }

    public function derCerConvert(string $derInFile, string $pemOutFile)
    {
        $this->checkInputFile($derInFile);
        $this->checkOutputFile($pemOutFile);
        $this->caller->call(
            'x509 -inform DER -in ? -outform PEM -out ?',
            [$derInFile, $pemOutFile]
        );
    }

    public function derCerConvertOut(string $derInFile): string
    {
        $pemOutFile = TemporaryFile::create();
        try {
            $this->derCerConvert($derInFile, $pemOutFile);
            return rtrim($pemOutFile->retriveContents(), PHP_EOL);
        } finally {
            $pemOutFile->remove();
        }
    }

    public function derCerConvertInOut(string $derContents): string
    {
        $derInFile = TemporaryFile::create();
        $derInFile->storeContents($derContents);
        try {
            return $this->derCerConvertOut($derInFile);
        } finally {
            $derInFile->remove();
        }
    }

    public function derKeyConvert(string $derInFile, string $inPassPhrase, string $pemOutFile)
    {
        $this->checkInputFile($derInFile);
        $this->checkOutputFile($pemOutFile);

        $this->caller->call(
            'pkcs8 -inform DER -in ? -passin env:PASSIN -out ?',
            [$derInFile, $pemOutFile],
            ['PASSIN' => $inPassPhrase]
        );
    }

    public function derKeyConvertOut(string $derInFile, string $inPassPhrase): string
    {
        $pemOutFile = TemporaryFile::create();
        try {
            $this->derKeyConvert($derInFile, $inPassPhrase, $pemOutFile);
            return rtrim($pemOutFile->retriveContents(), PHP_EOL);
        } finally {
            $pemOutFile->remove();
        }
    }

    public function derKeyProtect(string $derInFile, string $inPassPhrase, string $pemOutFile, string $outPassPhrase)
    {
        $tempfile = TemporaryFile::create();
        try {
            $this->derKeyConvert($derInFile, $inPassPhrase, $tempfile->getPath());
            $this->pemKeyProtect($tempfile->getPath(), '', $pemOutFile, $outPassPhrase);
        } finally {
            $tempfile->remove();
        }
    }

    public function derKeyProtectOut(string $pemInFile, string $inPassPhrase, string $outPassPhrase): string
    {
        $pemOutFile = TemporaryFile::create();
        try {
            $this->derKeyProtect($pemInFile, $inPassPhrase, $pemOutFile, $outPassPhrase);
            return rtrim($pemOutFile->retriveContents(), PHP_EOL);
        } finally {
            $pemOutFile->remove();
        }
    }

    public function pemKeyProtect(string $pemInFile, string $inPassPhrase, string $pemOutFile, string $outPassPhrase)
    {
        if ('' === $outPassPhrase) {
            $this->pemKeyUnprotect($pemInFile, $inPassPhrase, $pemOutFile);
            return;
        }

        $this->checkInputFile($pemInFile);
        $this->checkOutputFile($pemOutFile);

        $this->caller->call(
            'rsa -in ? -passin env:PASSIN -des3 -out ? -passout env:PASSOUT',
            [$pemInFile, $pemOutFile],
            ['PASSIN' => $inPassPhrase, 'PASSOUT' => $outPassPhrase]
        );
    }

    public function pemKeyProtectOut(string $pemInFile, string $inPassPhrase, string $outPassPhrase): string
    {
        $pemOutFile = TemporaryFile::create();
        try {
            $this->pemKeyProtect($pemInFile, $inPassPhrase, $pemOutFile, $outPassPhrase);
            return rtrim($pemOutFile->retriveContents(), PHP_EOL);
        } finally {
            $pemOutFile->remove();
        }
    }

    public function pemKeyProtectInOut(string $pemContents, string $inPassPhrase, string $outPassPhrase): string
    {
        $pemInFile = TemporaryFile::create();
        $pemInFile->storeContents($pemContents);
        try {
            return $this->pemKeyProtectOut($pemInFile, $inPassPhrase, $outPassPhrase);
        } finally {
            $pemInFile->remove();
        }
    }

    public function pemKeyUnprotect(string $pemInFile, string $inPassPhrase, string $pemOutFile)
    {
        $this->checkInputFile($pemInFile);
        $this->checkOutputFile($pemOutFile);

        $this->caller->call(
            'rsa -in ? -passin env:PASSIN -out ?',
            [$pemInFile, $pemOutFile],
            ['PASSIN' => $inPassPhrase]
        );
    }

    public function pemKeyUnprotectOut(string $pemInFile, string $inPassPhrase): string
    {
        $pemOutFile = TemporaryFile::create();
        try {
            $this->pemKeyUnprotect($pemInFile, $inPassPhrase, $pemOutFile);
            return rtrim($pemOutFile->retriveContents(), PHP_EOL);
        } finally {
            $pemOutFile->remove();
        }
    }

    public function pemKeyUnprotectInOut(string $pemContents, string $inPassPhrase): string
    {
        $pemInFile = TemporaryFile::create();
        $pemInFile->storeContents($pemContents);
        try {
            return $this->pemKeyUnprotectOut($pemInFile, $inPassPhrase);
        } finally {
            $pemInFile->remove();
        }
    }
    protected function checkInputFile(string $path)
    {
        // file must exists, not a directory and must contain a non-zero size
        if ('' === $path) {
            throw new OpenSSLException('File argument is empty');
        }
        if (! file_exists($path)) {
            throw new OpenSSLException("File $path does not exists");
        }
        if (is_dir($path)) {
            throw new OpenSSLException("File $path is a directory");
        }
        if (filesize($path) === 0) {
            throw new OpenSSLException("File $path is empty");
        }
    }

    protected function checkOutputFile(string $path)
    {
        // file should not exists or exists but contain a zero size
        if ('' === $path) {
            throw new OpenSSLException('File argument is empty');
        }
        if (! file_exists($path)) {
            if (! is_dir(dirname($path))) {
                throw new OpenSSLException("Directory of $path does not exists");
            }
            return;
        }
        if (is_dir($path)) {
            throw new OpenSSLException("File $path is a directory");
        }
        if (filesize($path) > 0) {
            throw new OpenSSLException("File $path is not empty");
        }
    }
}
