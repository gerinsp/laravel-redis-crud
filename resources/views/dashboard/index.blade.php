@extends('layouts.main')

@section('container')
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
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

    <div class="row justify-content-center">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    Daftar Users
                    @if (session()->get('login')['role'] === 'admin')
                    <a href="{{ route('dashboard.create') }}" class="btn btn-sm btn-primary">Create</a>
                    @endif
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Role</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    {{-- <th scope="row">
                                        {{ ($users->currentpage() - 1) * $users->perpage() + $loop->index + 1 }}</th> --}}
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $user['name'] }}</td>
                                    <td>{{ $user['email'] }}</td>
                                    <td>{{ $user['role'] }}</td>
                                    <td>
                                        <a href="/dashboard/{{ $user['username'] }}"
                                            class="badge btn-info text-decoration-none">Lihat</a>
                                        <a href="/dashboard/{{ $user['username'] }}/edit"
                                            class="badge btn-warning text-decoration-none">Edit</span></a>
                                        @if (session()->get('login')['role'] === 'admin')
                                            <form action="/dashboard/{{ $user['username'] }}" method="post" class="d-inline">
                                                @method('delete')
                                                @csrf
                                                <button class="badge btn-danger border-0"
                                                    onclick="return confirm('Are you sure?')">Hapus</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                    {{-- {{ $projects->links() }} --}}
                </div>
            </div>
        </div>
    </div>
@endsection