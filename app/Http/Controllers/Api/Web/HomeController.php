<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Image;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('active');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function listImage()
    {
        $datas = Image::orderBy("created_at", "DESC")->where("user_id", Auth::user()->id)->get();
        $Image = new Image;
        foreach ($datas as $data) {
            $row = $data;
            $row->image64 = $Image->getThumbnailBase64($data->filename, Auth::user()->id);
            $row->cdn = parse_url(Storage::url('images/user_id_' . Auth::user()->id . "/" . $data->filename))["path"];
        }
        $response["data"]  = $datas;
        return response()->json($response);
    }

    public function getUser()
    {
        return Auth::user();
    }

    public function upload(Request $r)
    {
        return Image::uploadImage($r, Auth::user()->id);
    }

    public function delete($id)
    {
        $data = Image::deleteImage($id);
        return response()->json($data);
    }

    public function detail($id)
    {
        $Image = new Image();
        return $Image->detailImage($id, Auth::user()->id);
    }

    public function download($id)
    {
        return Image::downloadImage($id, Auth::user()->id);
    }
}
