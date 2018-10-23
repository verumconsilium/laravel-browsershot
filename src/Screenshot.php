<?php

namespace VerumConsilium\Browsershot;

class Screenshot extends Wrapper
{
    protected $fileExtension = 'png';

    /**
     * Extension file of the generated output
     *
     * @return string
     */
    protected function getFileExtension(): string
    {
        return $this->fileExtension;
    }

    /**
     * Mime Type of the generated output
     *
     * @return string
     */
    protected function getMimeType(): string
    {
        return 'image/' . $this->getFileExtension();
    }

    /**
     * Set the image to be generated to type JPG
     *
     * @return Screenshot
     */
    public function useJPG(): Screenshot
    {
        $this->fileExtension = 'jpeg';
        $this->setScreenshotType('jpeg', 100);

        return $this;
    }
}
