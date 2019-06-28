<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!$userData=Auth::user()){
            $client = Controller::getCurrentClient($request);
            $userId=$client->user_id;
            $userData=User::findOrFail($userId);   
        }
        if ($userData &&  $userData->admin == 1) {
           return $next($request);
        }
        abort(403);
    }
}
