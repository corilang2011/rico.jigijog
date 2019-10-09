<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    //
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('user_list.index', compact('users'));
    }
    public function verify($id)
    {
        $usersDetails = User::findOrFail(decrypt($id));

        if($usersDetails != null){
            User::where('id', decrypt($id))->update(['email_verified_at' => "".date('Y-m-d H:i:s')]);

            flash(__('User has been verified successfully'))->success();
            return back();
        }

        flash(__('Error'))->error();
        return back();
    }
}
