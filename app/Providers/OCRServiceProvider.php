<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class OCRServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('ocr', function ($app) {
            return new TesseractOCR();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
