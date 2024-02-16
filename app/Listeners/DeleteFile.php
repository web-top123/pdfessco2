<?php

namespace App\Listeners;

use App\Events\FileBeforeDelete;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

class DeleteFile
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
     * @param  FileDeleting  $event
     * @return void
     */
    public function handle(FileBeforeDelete $event)
    {
        dd($event);
        Storage::delete($event->file->getAttributes()['path']);
    }
}