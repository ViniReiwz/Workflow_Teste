<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Uspdev\UspTheme\Events\UspThemeParseKey;

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
        // Adiciona o item "Livros" no menu se a chave uspdev-workflow estiver disponível
        Event::listen(function (UspThemeParseKey $event) {
            if (isset($event->item['key']) && $event->item['key'] == 'admin-book-view') {
                $event->item = [
                    'text' => '<span class="text-danger">Livros</span>',
                    'url' => route('livros.index'),
                    'title' => 'Workflows',
                    'can' => config('app.adminGate'), // controla permissão via Gate
                ];
            }
            return $event->item;
        });
    }
}
