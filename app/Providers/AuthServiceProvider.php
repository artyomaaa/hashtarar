<?php

namespace App\Providers;

use App\Models\Application;
use App\Models\JudgeDetails;
use App\Models\MediatorDetails;
use App\Policies\ApplicationPolicy;
use App\Policies\JudgeDetailsPolicy;
use App\Policies\MediatorDetailsPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Application::class => ApplicationPolicy::class,
        MediatorDetails::class => MediatorDetailsPolicy::class,
        JudgeDetails::class => JudgeDetailsPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
