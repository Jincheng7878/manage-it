<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Scenario;
use App\Models\Result;
use App\Policies\ScenarioPolicy;
use App\Policies\ResultPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Scenario::class => ScenarioPolicy::class,
        Result::class => ResultPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
