<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Services\ActivityLogger;
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

    $logger = app(ActivityLogger::class);

    Event::listen('eloquent.created: *', function ($eventName, array $data) use ($logger) {
        $model = $data[0] ?? null;
        if ($model instanceof Model) {
            $logger->logModelEvent('created', $model);
        }
    });

    Event::listen('eloquent.updated: *', function ($eventName, array $data) use ($logger) {
        $model = $data[0] ?? null;
        if ($model instanceof Model) {
            $logger->logModelEvent('updated', $model);
        }
    });

    Event::listen('eloquent.deleted: *', function ($eventName, array $data) use ($logger) {
        $model = $data[0] ?? null;
        if ($model instanceof Model) {
            $logger->logModelEvent('deleted', $model);
        }
    });

    Event::listen('eloquent.restored: *', function ($eventName, array $data) use ($logger) {
        $model = $data[0] ?? null;
        if ($model instanceof Model) {
            $logger->logModelEvent('restored', $model);
        }
    });

    Event::listen(Login::class, function (Login $event) use ($logger) {
        $logger->log('login', $event->user, [
            'guard' => $event->guard,
        ], 'User logged in');
    });

    Event::listen(Logout::class, function (Logout $event) use ($logger) {
        $logger->log('logout', $event->user, [
            'guard' => $event->guard,
        ], 'User logged out');
    });
  }
}
