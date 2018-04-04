<?php

namespace VerumConsilium\Browsershot\Tests;

use VerumConsilium\Browsershot\Facades\Screenshot;
use Illuminate\Foundation\Testing\TestResponse;

class ScreenshotFacadeTest extends TestCase
{
    /** @test */
    public function it_generates_a_screenshot_from_view_and_returns_it_as_a_download()
    {
        $response = Screenshot::loadView('test')
                                ->download();

        $response = new TestResponse($response);

        $response->assertSuccessful();
        $response->assertHeader('Content-Disposition', 'attachment');
    }
}
