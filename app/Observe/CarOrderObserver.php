<?php

namespace App\Observe;

use App\Strategy\NotificationStrategy;

class CarOrderObserver implements Observer
{
    private NotificationStrategy $notificationStrategy;

    public function __construct(NotificationStrategy $strategy)
    {
        $this->notificationStrategy = $strategy;
    }

    public function update(string $event, $data): void
    {
        if ($event === 'carPurchased') {
            $this->notificationStrategy->sendNotification($data);
        }
    }
}
