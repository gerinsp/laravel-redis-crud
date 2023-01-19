<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;

class LoginController extends Controller
{   
    public function index()
    {
        return view('auth.login', [
            'title' => 'Login',
            'active' => 'login'
        ]);
    }

    public function authenticate(Request $request)
    {
        $redis = Redis::connection();

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $keys = $redis->keys('user:*');
        $keys = str_replace('laravel_database_', '', $keys);
        
        foreach($keys as $key) {
            $user = $redis->hgetall($key);
            if($user['email'] === $credentials['email'] && Hash::check($credentials['password'], $user['password'])) {
                
                session()->put('login', $user);
                session()->regenerate();
                return redirect()->intended('/dashboard')->with('success', 'Login successfull!.');
            }
        }
        return redirect()->back()->with('loginErorr', 'Invalid email or password');
    }

    public function logout(Request $request)
    {
        session()->forget('login');
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('login');

    }
}
