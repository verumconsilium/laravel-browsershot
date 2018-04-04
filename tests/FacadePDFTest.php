<?php

namespace VerumConsilium\Browsershot\Tests;

use VerumConsilium\Browsershot\Facades\PDF;
use Illuminate\Foundation\Testing\TestResponse;

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
}
