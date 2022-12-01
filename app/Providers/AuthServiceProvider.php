<?php

namespace App\Providers;

use App\Models\Exam\Exam;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
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
        $this->registerPolicies();

        Gate::define('exam.examiner', function (User $user, Exam $exam) {
            return $exam->examiners->contains($user->id);
        });

        Gate::define('exam.invigilator', function (User $user, Exam $exam) {
            return $exam->invigilators->contains($user->id);
        });

        Gate::define('exam.candidate', function (User $user, Exam $exam) {
            return $exam->candidates->contains($user->id);
        });
    }
}
