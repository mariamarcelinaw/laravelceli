<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SysUser;

class RegisterController extends Controller
{
    public function index()
    {
        return view("auth.register");
    }

    public function processRegister(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:4|confirmed',
            'gender' => "required"
        ]);
        $user = new SysUser();
        $user->email = $request->email;
        $user->user_jab_id = 2; // mahasiswa
        $user->password = bcrypt($request->password);
        $user->gender = $request->gender;
        if ($user->save()) {
            return redirect()->route('login')->with("success", "Register Success");
        } else {
            return redirect()->back()->withInput()->withErrors("Something Error !");
        }
    }
}
