<?php

namespace VerumConsilium\Browsershot;

use Spatie\Browsershot\Browsershot;
use VerumConsilium\Browsershot\Traits\Responsable;
use VerumConsilium\Browsershot\Traits\ContentLoadable;
use VerumConsilium\Browsershot\Traits\Storable;

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

    public function __construct(?string $url = 'http://github.com/verumconsilium/laravel-browsershot')
    {
        if (!$this->validUrl($url)) {
            throw new \InvalidArgumentException("{$url} is not a valid url");
        }

        $browsershot = new Browsershot($url);
        $browsershot->setNodeBinary(config('browsershot.nodeBinary'))
                    ->setNpmBinary(config('browsershot.npmBinary'))
                    ->setProxyServer(config('browsershot.proxyServer'));

        if (!empty(config('browsershot.chromePath'))) {
            $browsershot->setChromePath(config('browsershot.chromePath'));
        }

        if (config('browsershot.noSandbox')) {
            $browsershot->noSandbox();
        }

        foreach (config('browsershot.additionalOptions') as $key => $value) {
            $browsershot->setOption($key, $value);
        }

        $this->browsershot = $browsershot;
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
        if (isset($this->tempFile) && !empty($this->tempFile)) {
            return $this;
        }

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
        if (method_exists($this->browsershot(), $name) && is_callable([$this->browsershot(), $name])) {
            $this->browsershot()->$name(...$arguments);

            return $this;
        }

        throw new \BadMethodCallException('Method ' . static::class . '::' . $name . '() does not exists');
    }

    /**
     * Unlink temp files if any
     *
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
