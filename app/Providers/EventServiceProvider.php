<?php

    namespace App\Providers;

    use Illuminate\Auth\Events\Registered;
    use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
    use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

    class EventServiceProvider extends ServiceProvider
    {
        public function __construct(\Illuminate\Foundation\Application $app)
        {
            $this->listen = [
                Registered::class => [
                    SendEmailVerificationNotification::class,
                ],
            ];
            parent::__construct($app);
        }
    }
