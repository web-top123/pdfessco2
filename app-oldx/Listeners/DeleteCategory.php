<?php

namespace App\Listeners;

use App\Events\CategoryBeforeDelete;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DeleteCategory
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
     * @param  CategoryBeforeDelete  $event
     * @return void
     */
    public function handle(CategoryBeforeDelete $event)
    {
        
          dd($event->category->files);
         //$event->category->files->each(function ($file) { $file->delete(); });
    }
}