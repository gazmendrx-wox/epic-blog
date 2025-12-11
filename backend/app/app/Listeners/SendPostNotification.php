<?php

namespace App\Listeners;

use App\Events\PostApproved;
use App\Jobs\SendPostNotificationJob;

class SendPostNotification
{
    /**
     * Handle the event.
     */
    public function handle(PostApproved $event): void
    {
        // Dispatch the job to send notifications to subscribers
        SendPostNotificationJob::dispatch($event->post);
    }
}
