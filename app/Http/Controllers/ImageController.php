<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImageUploadRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ImageController extends Controller
{
    //
    public function upload(ImageUploadRequest $request) {
        $file = $request->file('image');
        // genenrate random string
        $name = Str::random(10);
        // returns the image url
       $url =  \Storage::putFileAs('images', $file, $name . '.' . $file->extension());

       return[
           // to show uploaded image in public folder
           'url' => env('APP_URL') . '/' .$url
       ];

    }
}
