<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class DownloadPhotoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
    */

    public $userId;
    public $link;
    public $imageName;

    public function __construct($userId, $link)
    {
        //
        $this->userId = $userId;
        $this->link = $link;
        $this->imageName = Str::uuid()->toString();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {   
        // basePath is required as queue can not access relative path, 
        // queue runs in another thread and behaves as another user
        $basePath = base_path() . "/public/image/" . $this->userId;
        if (!file_exists($basePath)) {
            mkdir($basePath, 0777, true);
        }
        
        $content =  file_get_contents($this->link);
        //encode the image for better path resolving, image intervention is being used.
        $image = Image::make($content)->encode('jpg');
        $name = $basePath.'/'.$this->imageName. ".jpg";
        File::put($name, $image);
        
    }
}
