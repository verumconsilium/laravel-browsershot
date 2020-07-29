<?php

namespace VerumConsilium\Browsershot;

use Illuminate\Support\Str;
use Spatie\Browsershot\Browsershot;
use Spatie\Image\Manipulations;
use Spatie\TemporaryDirectory\TemporaryDirectory;
use VerumConsilium\Browsershot\Traits\ContentLoadable;
use VerumConsilium\Browsershot\Traits\Responsable;
use VerumConsilium\Browsershot\Traits\Storable;

/**
 * @mixin Browsershot
 * @mixin Manipulations
 */
abstract class Wrapper
{
    use Responsable;
    use ContentLoadable;
    use Storable;

    /**
     * Browsershot base class to generate PDFs.
     *
     * @var \Spatie\Browsershot\Browsershot
     */
    protected $browsershot;

    /**
     * Path where the temporary pdf will be stored.
     *
     * @var string
     */
    protected $tempFile;

    /**
     * Directory where the temporary pdf will be stored.
     *
     * @var string
     */
    protected $tempDir;

    public function __construct(string $url = 'http://github.com/verumconsilium/laravel-browsershot')
    {
        $browsershot = new Browsershot($url);
        $browsershot->setNodeBinary(config('browsershot.nodeBinary'))
                    ->setNpmBinary(config('browsershot.npmBinary'))
                    ->setProxyServer(config('browsershot.proxyServer'));

        // @codeCoverageIgnoreStart
        if (!empty(config('browsershot.chromePath'))) {
            $browsershot->setChromePath(config('browsershot.chromePath'));
        }

        if (config('browsershot.noSandbox')) {
            $browsershot->noSandbox();
        }

        $this->tempDir = config('browsershot.tempDir', '');

        foreach (config('browsershot.additionalOptions') as $key => $value) {
            $browsershot->setOption($key, $value);
        }
        // @codeCoverageIgnoreEnd

        $this->browsershot = $browsershot;

        $this->loadUrl($url);
    }

    /**
     * Extension file of the generated output.
     *
     * @return string
     */
    abstract protected function getFileExtension(): string;

    /**
     * Mime Type of the generated output.
     *
     * @return string
     */
    abstract protected function getMimeType(): string;

    /**
     * Access underlying browsershot instance.
     *
     * @return Browsershot
     */
    public function browsershot(): Browsershot
    {
        return $this->browsershot;
    }

    /**
     * Gets the temp file path.
     *
     * @return string
     */
    public function getTempFilePath(): string
    {
        $this->generateTempFile();

        return $this->tempFile;
    }

    /**
     * Reads the output from the generated temp file.
     *
     * @return string|null
     */
    protected function getTempFileContents(): ?string
    {
        $this->generateTempFile();

        return file_get_contents($this->tempFile);
    }

    /**
     * Generates temp file.
     *
     * @return Wrapper
     */
    protected function generateTempFile(): self
    {
        $fileName = 'BrowsershotOutput'.time().Str::random(5).'.'.$this->getFileExtension();
        $tempFileName = (new TemporaryDirectory($this->tempDir))
            ->create()
            ->path($fileName);

        $this->tempFile = $tempFileName;

        $this->browsershot()->save($this->tempFile);

        return $this;
    }

    /**
     * Delegates the call of methods to underlying Browsershot.
     *
     * @param string $name
     * @param array  $arguments
     *
     * @return \VerumConsilium\Browsershot\Wrapper
     */
    public function __call($name, $arguments): self
    {
        try {
            $this->browsershot()->$name(...$arguments);

            return $this;
        } catch (\Error $e) {
            throw new \BadMethodCallException('Method '.static::class.'::'.$name.'() does not exists');
        }
    }

    /**
     * Unlink temp files if any.
     */
    public function __destruct()
    {
        if ($this->tempFile) {
            @unlink($this->tempFile);
        }
    }
}
