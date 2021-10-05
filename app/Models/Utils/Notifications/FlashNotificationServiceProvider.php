<?php

namespace App\Models\Utils\Notifications;

use Illuminate\Support\ServiceProvider;

class FlashNotificationServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bindShared('flash', function () {
            return $this->app->make(FlashNotifier::class);
        });
    }

    public function boot()
    {
        parent::boot();

        if ($this->app['session']->has('flash_notification')) {
            $this->app['view']->share('flash_notification', $this->app['session']->get('flash_notification'));
        }
    }
}
