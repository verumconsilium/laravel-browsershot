<?php 

namespace VerumConsilium\Browsershot;

use Illuminate\Support\ServiceProvider;

class BrowsershotServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     *
     * @var string
     */
    protected $configFile = __DIR__ . '/../config/browsershot.php';

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom($this->configFile, 'browsershot');
    }

    /**
     * Bootstrap's Package Services
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            $this->configFile => config_path('browsershot.php')
        ], 'config');

        $this->app->bind('browsershot.pdf', function ($app) {
            return new PDF;
        });

        $this->app->bind('browsershot.screenshot', function ($app) {
            return new Screenshot;
        });
    }

    /**
     * Services provided by this provider
     *
     * @return array
     */
    public function provides()
    {
        return ['browsershot.pdf', 'browsershot.screenshot'];
    }
}
