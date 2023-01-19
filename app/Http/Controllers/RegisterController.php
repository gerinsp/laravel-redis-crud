<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;

class RegisterController extends Controller
{

    public function index()
    {
        return view('auth.register', [
            'title' => 'Register',
            'active' => ''
        ]);
    }

    public function store(Request $request)
    {
        $redis = Redis::connection();

        $keys = $redis->keys('user:*');
        $keys = str_replace('laravel_database_', '', $keys);

        $validatedData = $request->validate([
            'name' => 'required|min:3|max:100',
            'username' => 'required|min:3|max:25',
            'email' => 'required|email',
            'password' => 'required|min:5',
            'role' => 'required'
        ]);

        if($redis->exists('user:' . $validatedData['username'])) {
            return redirect('/dashboard/create')->with('gagal', 'Username is not available.');
        }
        
        foreach($keys as $key) {
            if($redis->hget($key, 'email') === $validatedData['email']) {
                return redirect('/dashboard/create')->with('gagal', 'Email is not available.');
            }
        }

        $validatedData['password'] = Hash::make($validatedData['password']);
        $validatedData['username'] = strtolower($validatedData['username']);

        $redis->hmset('user:' . $validatedData['username'], $validatedData);

        return redirect('/login')->with('success', 'Registration successfull! Please Login');
    }
}
