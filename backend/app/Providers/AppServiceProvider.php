<?php

namespace App\Providers;

use App\Services\Whatsapp\FonnteService;
use App\Services\Whatsapp\WapiService;
use App\Services\Whatsapp\WhatsappNotificationInterface;
use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\SecurityScheme;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(WhatsappNotificationInterface::class, function ($app) {
            $whatsappDriver = config('whatsapp.gateway');

            return match ($whatsappDriver) {
                'wapi' => new WapiService,
                'fonnte' => new FonnteService,
            };
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Scramble::configure()
            ->withDocumentTransformers(function (OpenApi $openApi) {
                $openApi->secure(
                    SecurityScheme::http('bearer')
                );
            });
    }
}
