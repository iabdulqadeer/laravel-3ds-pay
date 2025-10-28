<?php

namespace Iabdulqadeer\ThreeDSPay\Providers;

use Illuminate\Support\ServiceProvider;
use Iabdulqadeer\ThreeDSPay\Contracts\PaymentStorage;
use Iabdulqadeer\ThreeDSPay\Storage\NullPaymentStorage;
use Iabdulqadeer\ThreeDSPay\ThreeDSPay;

class ThreeDSPayServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom($this->pkgPath('config/three-ds-pay.php'), 'three-ds-pay');
        $this->app->bind(PaymentStorage::class, fn () => new NullPaymentStorage());
        $this->app->singleton('three-ds-pay', fn () => new ThreeDSPay());
    }

    public function boot(): void
    {
        $this->loadRoutesFrom($this->pkgPath('routes/web.php'));
        $this->loadViewsFrom($this->pkgPath('resources/views'), 'threeds');

        if ($this->app->runningInConsole()) {
            $configSource = $this->pkgPath('config/three-ds-pay.php');
            if (is_file($configSource)) {
                $this->publishes([
                    $configSource => config_path('three-ds-pay.php'),
                ], 'threeds-config');
            }

            $viewsSource = $this->pkgPath('resources/views');
            if (is_dir($viewsSource)) {
                $this->publishes([
                    $viewsSource => resource_path('views/vendor/threeds'),
                ], 'threeds-views');
            }
        }
    }

    private function pkgPath(string $relative): string
    {
        return \dirname(__DIR__, 2) . DIRECTORY_SEPARATOR
             . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $relative);
    }
}
