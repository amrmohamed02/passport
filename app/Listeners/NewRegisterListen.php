<?php

namespace App\Listeners;

use App\Mail\RegisterEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class NewRegisterListen implements ShouldQueue
{

    /**
     * Handle the event.
     * @param object $event
     * @return void
     */
    public function handle($event)
    {
        Mail::to($event->user->email)->send(new RegisterEmail($event->user));
    }
}
