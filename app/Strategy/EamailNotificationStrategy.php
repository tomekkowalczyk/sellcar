<?php

namespace App\Strategy;

class EamailNotificationStrategy implements NotificationStrategy
{
    public function sendNotification(array $data): void
    {

        dump('Sending email to manufacturer');
//        $to = 'manufacturer@example.com';
//        $subject = 'New Car Order';
//        $message = "New order has been placed:\n\n" .
//            "Car Type: " . $data['carType'] . "\n" .
//            "Car Version: " . $data['carVersion'] . "\n" .
//            "Elements: " . implode(', ', $data['elements']) . "\n" .
//            "Costs: " . $data['costs'];
//
//        mail($to, $subject, $message);
    }
}

