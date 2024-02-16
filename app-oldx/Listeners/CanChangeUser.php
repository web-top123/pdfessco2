<?php

namespace App\Listeners;

use App\Events\UserBeforeDelete;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Auth;
class CanChangeUser
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserBeforeDelete  $event
     * @return void
     */
    public function handle(UserBeforeDelete $event)
    {
        dd($event);
        if ($event->user->hasRole('admin')) {
            $admin = \Auth::user()->hasRole('superadmin');
        }
    }
}
