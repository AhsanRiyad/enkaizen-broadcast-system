<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Jobs\DownloadPhotoJob;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
          return User::find(auth()->id())->images;
        } catch (\Throwable $th) {
            abort('404');  
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        dispatch(new DownloadPhotoJob( auth()->id(), $request->path));
        return response('your file is being processed' , 200);
    }

}
