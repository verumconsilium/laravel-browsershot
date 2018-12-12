<?php

namespace VerumConsilium\Browsershot\Traits;

use VerumConsilium\Browsershot\Wrapper;
use Spatie\Browsershot\Browsershot;

trait ContentLoadable
{
    /**
     * Renders and loads a given view browsershot
     *
     * @param string $view
     * @param array|null $data
     * @param array|null $mergeData
     * @return \VerumConsilium\Browsershot\Wrapper
     * @throws \Throwable
     */
    public function loadView(string $view, ?array $data = [], ?array $mergeData = []): Wrapper
    {
        $html = view($view, $data, $mergeData)->render();

        $this->browsershot()->setHtml($html);

        return $this;
    }

    /**
    * Sets a valid html string
    *
    * @param string $html
    * @return Wrapper
    */
    public function loadHtml(string $html): Wrapper
    {
        $this->browsershot()->setHtml($html);

        return $this;
    }

    /**
     * Loads the given url to browsershot
     *
     * @param string $url
     * @return Wrapper
     */
    public function loadUrl(string $url): Wrapper
    {
        if (!$this->validUrl($url)) {
            throw new \InvalidArgumentException('The url: ' . $url . ' is not valid');
        }

        $this->browsershot()->setUrl($url);

        return $this;
    }

    /**
     * Checks whether the given url is valid or not
     *
     * @param string $url
     * @return boolean
     */
    protected function validUrl(string $url): bool
    {
        return (bool) filter_var($url, FILTER_VALIDATE_URL);
    }

    abstract protected function browsershot(): Browsershot;
}
