@extends('layouts.main')

@section('container')
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item">Edit User</a></li>
        </ol>
    </nav>
    @if (session()->has('success'))
    <div class="alert alert-success col-lg-8" role="alert">
        {{ session('success') }}
    </div>
    @endif

    @if (session()->has('gagal'))
        <div class="alert alert-danger col-lg-8" role="alert">
            {{ session('gagal') }}
        </div>
    @endif
    {{-- toash --}}
    <h3 class="my-3">Edit User</h3 class="my-3">

    <div class="col-lg-8">
        <form method="POST" action="{{ route('dashboard.update', $user['username']) }}" enctype="multipart/form-data">
            @method('put')
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label ">Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                    required autofocus value="{{ old('name', $user['name']) }}">
                @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="username" class="form-label ">Username</label>
                <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username"
                    required autofocus value="{{ old('username', $user['username']) }}">
                @error('username')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="email" class="form-label ">Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                    required autofocus value="{{ old('email', $user['email']) }}">
                @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="password" class="form-label ">Password</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password"
                    required autofocus value="{{ old('password', $user['password']) }}">
                @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            @if (session()->get('login')['role'] === 'admin')
            <div class="mb-3">
                <label for="Role" class="form-label ">Role</label>
                <select name="role" id="" class="form-select">
                        @if(old('role', $user['role']) == 'admin')
                            <option value="admin" selected>Admin</option>
                            <option value="user">User</option>
                        @else
                            <option value="user" selected>User</option>
                            <option value="admin">Admin</option>
                        @endif
                </select>
            </div>
            @endif
            <button class="btn btn-primary" type="submit">Submit</button>
        </form>
    </div>
@endsection