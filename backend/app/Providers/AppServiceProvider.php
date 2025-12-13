<?php

namespace App\Providers;

use Dedoc\Scramble\Scramble;
use App\Services\Whatsapp\WapiService;
use Illuminate\Support\ServiceProvider;
use App\Services\Whatsapp\FonnteService;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\SecurityScheme;
use App\Services\Whatsapp\WhatsappNotificationInterface;

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
                'wapi' => new WapiService(),
                'fonnte' => new FonnteService(),
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
