<?php

namespace VerumConsilium\Browsershot\Traits;

use Illuminate\Http\Response;

trait Responsable
{
    /**
     * Returns a response with the generated file as an attachment
     *
     * @param string|null $filename
     * @param array $additionalHeaders
     * @return Response
     */
    public function download(?string $filename = null, $additionalHeaders = []): Response
    {
        return $this->response('attachment', $filename, $additionalHeaders);
    }

    /**
     * Returns the generated file to be displayed inline in the browser
     *
     * @param null|string $filename
     * @param array       $additionalHeaders
     * @return \Illuminate\Http\Response
     */
    public function inline(?string $filename = null, $additionalHeaders = []): Response
    {
        return $this->response('inline', $filename, $additionalHeaders);
    }

    /**
     * Generates a response with the content set to the pdf
     *
     * @param string $contentDisposition
     * @param string|null $filename
     * @param array $additionalHeaders
     * @return Response
     */
    protected function response(string $contentDisposition, ?string $filename = null, $additionalHeaders = []): Response
    {
        $contents = $this->getTempFileContents();

        if (!is_null($filename)) {
            $contentDisposition .= '; filename="' . $filename . '"';
        }

        $headers = array_merge([
            'Content-Type' => $this->getMimeType(),
            'Content-Disposition' => $contentDisposition
        ], $additionalHeaders);

        return new Response($contents, 200, $headers);
    }

    abstract protected function getTempFileContents(): ?string;

    abstract protected function getMimeType(): string;
}
