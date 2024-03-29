<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Event' => [
            'App\Listeners\EventListener',
        ],
		\SocialiteProviders\Manager\SocialiteWasCalled::class => [
			// add your listeners (aka providers) here
			'SocialiteProviders\StaffConnect\StaffConnectExtendSocialite@handle',
        ],
        
        // 'Illuminate\Mail\Events\MessageSending' => [
        //     'App\Listeners\LogSentMessage',
        // ],
        /*'Laravel\Passport\Events\AccessTokenCreated' => [
            'App\Listeners\RevokeOldTokens',
        ],
    
        'Laravel\Passport\Events\RefreshTokenCreated' => [
            'App\Listeners\PruneOldTokens',
        ],  */      
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
