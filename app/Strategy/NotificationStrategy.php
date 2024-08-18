<?php

namespace App\Strategy;

interface NotificationStrategy
{
    public function sendNotification(array $data): void;
}

