<?php

namespace VerumConsilium\Browsershot;

class PDF extends Wrapper
{
    /**
     * Extension file of the generated output
     *
     * @return string
     */
    protected function getFileExtension(): string
    {
        return 'pdf';
    }

    /**
     * Mime Type of the generated output
     *
     * @return string
     */
    protected function getMimeType(): string
    {
        return 'application/pdf';
    }
}
