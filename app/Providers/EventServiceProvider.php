<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
        $APPLICATION_MODE = appMode();
        if ($APPLICATION_MODE == "demo") {
            $request_v = (request());
            $request_path = $request_v->path();
            if (isset($request_v['_method']) && $request_v['_method'] == "DELETE") {
                abort(405);
            }

            $url = request()->segment(1);
            $restricted_urls = ['setting_update', 'white-label-update', 'update-password', 'tax_update', 'mail-settings-update'];
            if (in_array($url, $restricted_urls)) {
                abort(405, 'Demo mode is not allowed to change this action.');
            }
        }
    }
}
