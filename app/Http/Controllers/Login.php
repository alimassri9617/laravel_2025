<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Models\Client;
use App\Models\Driver;
use Illuminate\Support\Facades\Hash;
class Login extends Controller
{
    
          public function logout(Request $request)
    {
        // Perform logout logic here
        // For example, you can use Auth::logout() if you're using Laravel's authentication system
        return redirect()->route('home')->with('success', 'Logout successful');
    }

    public function login(Request $request)
    {
        // Validate request data
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6|max:20',
        ]);
        if(Client::where("email",$credentials["email"])->exists()){
            $user = Client::where("email",$credentials["email"])->first();
            if (Hash::check($credentials['password'], $user->password)) {
                // Authentication passed
                return redirect()->route('home')->with('success', 'Login successful');
            } else {
                return redirect()->back()->with('error', 'Invalid credentials');
            }
       
        }}
    }