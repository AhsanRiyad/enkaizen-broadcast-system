<?php

namespace App\Providers;

use App\Models\User;
use App\Events\DownloadPhotoEvent;
use Illuminate\Support\Facades\Queue;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Support\ServiceProvider;
use Illuminate\Queue\Events\JobProcessed;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Queue::failing(function (JobFailed $event) {
            // $event->connectionName
            // $event->job
            // $event->exception
            if ($event->job->resolveName()  == 'App\Jobs\DownloadPhotoJob') {
                $userId = unserialize($event->job->payload()['data']['command'])->userId;
                //broadcast
                Event(new DownloadPhotoEvent('failed' , $userId));
            }
        });

        Queue::after(function (JobProcessed $event) {
            //check the name and dispatch the broadcast event
            if( $event->job->resolveName()  == 'App\Jobs\DownloadPhotoJob' ){
                $imageName = unserialize($event->job->payload()['data']['command'])->imageName;
                $userId = unserialize($event->job->payload()['data']['command'])->userId;
                //save in the database
                User::find($userId)->images()->create(['path' => $imageName]);
                //broadcast
                Event(new DownloadPhotoEvent($imageName , $userId));
            }
        });
    }
}
