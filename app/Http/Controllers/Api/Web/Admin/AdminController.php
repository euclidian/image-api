<?php

namespace App\Http\Controllers\Api\Web\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Laravel\Passport\ClientRepository;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
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
        $this->middleware('admin');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function listuser(){
        $data = User::select("users.id","users.name","users.email","users.admin","users.active","users.created_at","oauth_clients.id as secretid","oauth_clients.secret")
        ->orderBy("name")
        ->leftJoin('oauth_clients', 'users.id', '=', 'oauth_clients.user_id')
        ->paginate(10);

        return response()->json($data);
    }

    public function toAdmin($id){
        $data = User::findOrFail($id);
        $data->update([
            "admin" => !$data->admin
        ]);
        return response()->json($data);
    }

    public function toActive($id){
        $data = User::findOrFail($id);
        $data->update([
            "active" => !$data->active
        ]);
        return response()->json($data);
    }

    public function generateToken($id){
        DB::table("oauth_clients")->where("password_client",0)->where("user_id",$id)->delete();

        $user=User::findOrFail($id);

        $CR = new ClientRepository;
        $CR->create($id,$user->name,"localhost");
        return response()->json($user);
    }
}
