<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Image;

class ImageUploaderApi extends Controller
{
    public function __construct()
    {
        $this->middleware('client');
        $this->middleware('active');
    }

    public function upload(Request $r)
    {
        $image = Image::uploadImage($r,$this->getCurrentClient($r)->user_id);

        $response["status"] = 200;
        $response["data"]   = $image;
        return response()->json($response);
    }
}
