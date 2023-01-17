@extends('layouts.main')

@section('container')
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item">Profile User</a></li>
        </ol>
    </nav>

    {{-- toash --}}
    <div class="card">
        <div class="card-header">
            Profile User
        </div>
        <div class="card-body">
            <h5 class="card-title">{{ $user['name'] }}</h5>
            <p class="card-text">Username: {{ $user['username'] }}</p>
            <p class="card-text">Email: {{ $user['email'] }}</p>
            <p class="card-text">Role: {{ $user['role'] }}</p>
            <a href="/dashboard" class="btn btn-primary">Back</a>
        </div>
    </div>
@endsection