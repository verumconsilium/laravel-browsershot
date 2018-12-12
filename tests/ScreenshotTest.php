<?php

namespace VerumConsilium\Browsershot\Tests;

use Illuminate\Support\Facades\Storage;
use VerumConsilium\Browsershot\Screenshot;
use Illuminate\Foundation\Testing\TestResponse;

class ScreenshotTest extends TestCase
{
    /** @test */
    public function it_generates_a_screenshot_from_html_and_saves_it_to_disk()
    {
        $image = new Screenshot;

        Storage::fake();

        $path = $image->loadHtml('<h1>Testing</h1>')
                      ->store('images/');

        Storage::assertExists($path);
    }

    /** @test */
    public function it_generates_a_screenshot_from_a_url_and_returns_it_inline_as_jpg()
    {
        $image = new Screenshot('https://verumconsilium.com');

        $response = $image->useJPG()
                          ->inline();

        $response = new TestResponse($response);

        $response->assertSuccessful();
        $response->assertHeader('Content-Disposition', 'inline');
        $response->assertHeader('Content-Type', 'image/jpeg');
    }

    /** @test */
    public function it_stores_a_jpg_screenshot_with_custom_name()
    {
        $screenshot = new Screenshot();
        $url = 'https://verumconsilium.com';

        Storage::fake();

        $path = $screenshot->loadUrl($url)
                            ->windowSize(1024, 850)
                            ->useJPG()
                            ->storeAs('public/', 'screenshot-filename.jpg');

        Storage::assertExists($path);
    }

    /** @test */
    public function it_calls_methods_on_image_manipulation_class()
    {
        $screenshot = new Screenshot();
        $url = 'https://verumconsilium.com';

        Storage::fake();

        $path = $screenshot->loadUrl($url)
                           ->fit('contain', 12, 32)
                           ->storeAs('public/', 'screenshot-filename.jpg');

        Storage::assertExists($path);
    }
}
