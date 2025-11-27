<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
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
        \App\Models\Person::observe(\App\Observers\PersonObserver::class);
        \App\Models\Incidences::observe(\App\Observers\IncidencesObserver::class);
        \App\Models\Communication::observe(\App\Observers\CommunicationObserver::class);
        \App\Models\Stall::observe(\App\Observers\StallObserver::class);
        \App\Models\Extension::observe(\App\Observers\ExtensionObserver::class);
        \App\Models\ChecklistAnswer::observe(\App\Observers\ChecklistAnswerObserver::class);
        \App\Models\Checklist::observe(\App\Observers\ChecklistObserver::class);
        \App\Models\Invoice::observe(\App\Observers\InvoiceObserver::class);
    }
}
