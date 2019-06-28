<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Traits\LogsActivity;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image as Gambar;

class Image extends Model
{
    protected $guarded = [];
    use LogsActivity;
    protected static $logAttributes = ['*'];

    public static function uploadImage($r, $userId)
    {
        $r->validate([
            'image' => 'required|mimes:jpeg,pdf,png,jpg'
        ]);


        $judul   = $r->file('image')->getClientOriginalName();

        $thefile = Storage::putFile('images/user_id_' . $userId, $r->file('image'));
        $tipemime = $r->file("image")->getClientMimeType();
        if (strpos($tipemime, "image") === 0) {
            //Resize image here
            $img = Gambar::make($r->file("image"))->resize(32, 32)->encode();
            Storage::put('images/user_id_' . $userId . '/thumbnails/' . basename($thefile), $img->__toString());

            $img = Gambar::make($r->file("image"))->resize(64, 64)->encode();
            Storage::put('images/user_id_' . $userId . '/64x64/' . basename($thefile), $img->__toString());
        }

        $data = Image::create([
            "user_id"  => $userId,
            "title"    => $judul,
            "filename" => basename($thefile)
        ]);
        $data->url = parse_url(Storage::url('images/user_id_'.$userId."/".basename($thefile)))["path"];

        return $data;
    }

    public static function downloadImage($id, $userId)
    {
        $data = Image::findOrFail($id);
        if(!Storage::has('images/user_id_' . $userId . "/" . $data->filename)){
            abort(404,"File Tidak ditemukan");
        }
        return Storage::download('images/user_id_' . $userId . "/" . $data->filename);
    }

    public static function listImage($userId)
    {
        $data = Image::where("user_id", $userId)->orderBy("created_at", "DESC")->get();

        return $data;
    }

    public static function deleteImage($id)
    {
        $data = Image::findOrFail($id);
        $data->delete();

        return $data;
    }

    public function getThumbnailBase64($filename, $userId)
    {
        if (Storage::has('images/user_id_' . $userId . '/thumbnails/' . $filename)) {
            return base64_encode(Storage::get('images/user_id_' . $userId . '/thumbnails/' . $filename));
        } else {
            return null;
        }
    }

    public function get64x64Base64($filename, $userId)
    {
        if (Storage::has('images/user_id_' . $userId . '/64x64/' . $filename)) {
            return base64_encode(Storage::get('images/user_id_' . $userId . '/64x64/' . $filename));
        } else {
            return null;
        }
    }

    public function getImageBase64($filename, $userId)
    {
        if (Storage::has('images/user_id_' . $userId . '/' . $filename)) {
            return base64_encode(Storage::get('images/user_id_' . $userId . '/' . $filename));
        } else {
            return null;
        }
    }

    public function detailImage($id, $userId)
    {
        $data = Image::findOrFail($id);

        $data->truebase64      = $this->getImageBase64($data->filename, $userId);
        $data->thumbnailbase64 = $this->getThumbnailBase64($data->filename, $userId);
        $data->img64x64base64  = $this->get64x64Base64($data->filename, $userId);

        return $data;
    }
}
