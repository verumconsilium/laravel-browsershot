<?php

namespace VerumConsilium\Browsershot;

use Spatie\Browsershot\Browsershot;
use Spatie\Image\Manipulations;
use VerumConsilium\Browsershot\Traits\Responsable;
use VerumConsilium\Browsershot\Traits\ContentLoadable;
use VerumConsilium\Browsershot\Traits\Storable;

/**
 * @mixin Browsershot
 * @mixin Manipulations
 */
abstract class Wrapper
{
    use Responsable, ContentLoadable, Storable;

    /**
     * Browsershot base class to generate PDFs
     *
     * @var \Spatie\Browsershot\Browsershot
     */
    protected $browsershot;

    /**
    * Directory where the temporary pdf will be stored
    *
    * @var string
    */
    protected $tempFile;

    public function __construct(string $url = 'http://github.com/verumconsilium/laravel-browsershot')
    {
        $browsershot = new Browsershot($url);
        $browsershot->setNodeBinary(config('browsershot.nodeBinary'))
                    ->setNodeModulePath(config('browsershow.nodeModules'))
                    ->setNpmBinary(config('browsershot.npmBinary'))
                    ->setProxyServer(config('browsershot.proxyServer'));

        // @codeCoverageIgnoreStart
        if (!empty(config('browsershot.chromePath'))) {
            $browsershot->setChromePath(config('browsershot.chromePath'));
        }

        if (config('browsershot.noSandbox')) {
            $browsershot->noSandbox();
        }

        foreach (config('browsershot.additionalOptions') as $key => $value) {
            $browsershot->setOption($key, $value);
        }
        // @codeCoverageIgnoreEnd

        $this->browsershot = $browsershot;

        $this->loadUrl($url);
    }

    /**
     * Extension file of the generated output
     *
     * @return string
     */
    abstract protected function getFileExtension(): string;

    /**
     * Mime Type of the generated output
     *
     * @return string
     */
    abstract protected function getMimeType(): string;

    /**
     * Access underlying browsershot instance
     *
     * @return Browsershot
     */
    protected function browsershot(): Browsershot
    {
        return $this->browsershot;
    }

    /**
     * Gets the temp file path
     *
     * @return string
     */
    public function getTempFilePath(): string
    {
        $this->generateTempFile();

        return $this->tempFile;
    }

    /**
     * Reads the output from the generated temp file
     *
     * @return string|null
     */
    protected function getTempFileContents(): ?string
    {
        $this->generateTempFile();

        return file_get_contents($this->tempFile);
    }

    /**
     * Generates temp file
     *
     * @return Wrapper
     */
    protected function generateTempFile(): Wrapper
    {
        $tempFileName = tempnam(sys_get_temp_dir(), 'BrowsershotOutput');

        $this->tempFile = $tempFileName . '.' . $this->getFileExtension();

        $this->browsershot()->save($this->tempFile);

        return $this;
    }

    /**
     * Delegates the call of methods to underlying Browsershot
     *
     * @param string $name
     * @param array $arguments
     * @return \VerumConsilium\Browsershot\Wrapper
     */
    public function __call($name, $arguments): Wrapper
    {
        try {
            $this->browsershot()->$name(...$arguments);
            return $this;
        } catch (\Error $e) {
            throw new \BadMethodCallException('Method ' . static::class . '::' . $name . '() does not exists');
        }
    }

    /**
     * Unlink temp files if any
     *
     * @codeCoverageIgnore
     * @return array
     */
    public function __sleep()
    {
        if ($this->tempFile) {
            @unlink($this->tempFile);
        }

        return [];
    }
}
