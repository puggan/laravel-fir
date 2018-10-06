<?php

    namespace App\Providers;

    use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

    class AuthServiceProvider extends ServiceProvider
    {
        public function __construct(\Illuminate\Foundation\Application $app)
        {
            /**
             * The policy mappings for the application.
             *
             * @var array
             */
            $this->policies = [
                'App\Model' => 'App\Policies\ModelPolicy',
            ];

            parent::__construct($app);
        }

        /**
         * Register any authentication / authorization services.
         *
         * @return void
         */
        public function boot() : void
        {
            $this->registerPolicies();

            //
        }
    }
