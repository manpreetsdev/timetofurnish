<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Login;
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

    // Keep a light record (no body) of every automatic email the site sends
    Event::listen(\Illuminate\Mail\Events\MessageSent::class, function ($event) {
        try {
            $message = $event->message;

            if ($message->getHeaders()->has(\App\Services\OrderEmailService::LOGGED_HEADER)) {
                return;
            }

            $subject = (string) $message->getSubject();
            $from = $message->getFrom()[0] ?? null;

            // Link the email to an order when its code appears in the subject or body
            $orderId = null;
            $haystack = $subject . ' ' . mb_substr(strip_tags((string) $message->getHtmlBody()), 0, 20000);
            if (preg_match_all('/\b[A-Z]{2,6}-\d{3,}-\d+-\d+\b|\b\d{8}-\d{6,}\b/', $haystack, $matches)) {
                $orderId = \App\Models\Order::whereIn('code', array_unique($matches[0]))->value('id');
            }

            foreach ($message->getTo() as $to) {
                \App\Models\OrderEmailLog::create([
                    'order_id' => $orderId,
                    'type' => 'system',
                    'to_email' => $to->getAddress(),
                    'from_email' => $from ? $from->getAddress() : null,
                    'from_name' => $from ? $from->getName() : null,
                    'subject' => mb_substr($subject, 0, 190),
                    'status' => 'sent',
                    'sent_by' => auth()->id(),
                ]);
            }
        } catch (\Throwable $e) {
            logger()->error('Email history logging failed: ' . $e->getMessage());
        }
    });

    Event::listen(Login::class, function (Login $event) use ($logger) {
        $logger->log('login', $event->user, [
            'guard' => $event->guard,
        ], 'User logged in');
    });
  }
}
