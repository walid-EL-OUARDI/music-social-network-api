<?php

namespace App\Listeners;

use App\Events\NewUserIsRegistered;
use App\Mail\WelcomeEmail;
use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendWelcomeMail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(NewUserIsRegistered $event): void
    {
        $user = User::where('email', $event->userEmail)->first();

        Mail::to($event->userEmail)->send(new WelcomeEmail($user));
    }
}
