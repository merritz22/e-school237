<?php

namespace App\Listeners;

use App\Mail\NewUserRegistered;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;

class SendNewUserNotification
{
    public function handle(Registered $event): void
    {
        Mail::to('admin@e-school237.com')
            ->queue(new NewUserRegistered($event->user));
    }
}