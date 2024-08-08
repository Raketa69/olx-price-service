<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Subscription;

class MailService
{
    public function sendEmail(Subscription $subscription): void
    {
        $subject = "The price was changed!";
        $message = "The price on offer $subscription->link was changed from $subscription->price to $subscription->new_price";
        $emailTo = $subscription->email;

        mail($emailTo, $subject, $message);
    }
}