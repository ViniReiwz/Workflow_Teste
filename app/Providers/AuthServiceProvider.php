<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Uspdev\Workflow\Workflow;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $workflow = Workflow::loadDefinition('emprestimo_livro_simples');        
        
        Gate::define('bibliotecario', function ($user) use ($workflow) {
            return $workflow->verifyRole('bibliotecario', $user);
        });

        Gate::define('usuario', function ($user) use ($workflow) {
            return $workflow->verifyRole('usuario', $user);
        });
    }
}
