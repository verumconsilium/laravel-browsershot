<?php

namespace VerumConsilium\Browsershot\Tests;

use VerumConsilium\Browsershot\Facades\PDF;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Support\Facades\Storage;

class FacadePDFTest extends TestCase
{
    /** @test */
    public function it_access_the_facade_to_generate_a_pdf_from_a_view_and_return_it_inline()
    {
        $response = PDF::loadView('test')
                       ->inline();

        $response = new TestResponse($response);

        $response->assertSuccessful();
    }

    /** @test */
    public function it_generates_different_content_from_the_facades_once_it_has_been_resolved_from_the_container()
    {
        Storage::fake();

        $googlePdf = PDF::loadUrl('https://google.com')
                        ->store('pdf/');
        $githubPdf = PDF::loadUrl('https://github.com')
                        ->store('pdf/');

        $googleContent = Storage::get($googlePdf);
        $githubContent = Storage::get($githubPdf);

        $this->assertNotEquals($googleContent, $githubContent);
    }
}
