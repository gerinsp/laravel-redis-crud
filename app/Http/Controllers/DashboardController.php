<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $redis = Redis::connection();

        // get all users keys
        $keys = $redis->keys('user:*');
        $keys = str_replace('laravel_database_', '', $keys);
        $users = [];
        foreach($keys as $key) {
            $user = $redis->hgetall($key);
            $users[] = $user;
        }

        return view('dashboard.index',[
            'title' => 'Dashboard',
            'active' => 'dashboard',
            'users' => $users
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.create',[
            'title' => 'Dashboard',
            'active' => 'dashboard'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

        return redirect('/dashboard')->with('success', 'new user has been added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($username)
    {
        $redis = Redis::connection();

        // get all users keys
        $user = $redis->hgetall('user:' . $username);

        return view('dashboard.show',[
            'title' => 'Dashboard',
            'active' => 'dashboard',
            'user' => $user
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($username)
    {
        $redis = Redis::connection();

        // get all users keys
        $user = $redis->hgetall('user:' . $username);
    
        return view('dashboard.edit', [
            'title' => 'Dashboard',
            'active' => 'dashboard',
            'user' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $username)
    {
        $redis = Redis::connection();

        $keys = $redis->keys('user:*');
        $keys = str_replace('laravel_database_', '', $keys);

        $uname = $redis->hget('user:'. $username, 'username');
        $email = $redis->hget('user:'. $username, 'email');

        $validatedData = $request->validate([
            'name' => 'required|min:3|max:100',
            'username' => 'required|min:3|max:25',
            'email' => 'required|email',
            'password' => 'required|min:5',
            'role' => 'required'
        ]);

        if($redis->exists('user:' . $validatedData['username']) && $uname != $validatedData['username']) {
            return redirect('/dashboard/' . $username . '/edit')->with('gagal', 'Username is not available.');
        }
        
        foreach($keys as $key) {
            if($redis->hget($key, 'email') === $validatedData['email'] && $email != $validatedData['email']) {
                return redirect('/dashboard/' . $username . '/edit')->with('gagal', 'Email is not available.');
            }
        }

        $validatedData['password'] = Hash::make($validatedData['password']);
        $validatedData['username'] = strtolower($validatedData['username']);

        $redis->hmset('user:' . $username, $validatedData);

        return redirect('/dashboard')->with('success', 'User has been updated.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($username)
    {
        $redis = Redis::connection();

        $redis->del('user:' . $username);

        return redirect('/dashboard')->with('success', 'User has been deleted.');
    }
}
