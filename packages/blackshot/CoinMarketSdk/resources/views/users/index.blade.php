@extends('blackshot::layouts.app')

@section('content')
    <div class="d-flex align-items-center justify-content-between px-2">
        <div>
            <h1>
                <i class="fas fa-users text-secondary" aria-hidden="true"></i>
                <strong>Users</strong>
            </h1>
            <p class="text-secondary">
                Total of {{ $users->total() }} {{ \Illuminate\Support\Str::plural('user', $users->total()) }}
                on {{ $users->lastPage() }} {{ \Illuminate\Support\Str::plural('page', $users->lastPage()) }}
            </p>
        </div>

        <a href="{{ route('users.edit') }}" class="btn btn-outline-success">
            <i class="fas fa-user-plus"></i>
            Add user
        </a>
    </div>

    <div class="mb-4">
        <form action="{{ route('users.home') }}" method="POST" id="filter_form">
            @csrf
            <div class="rounded p-3 border bg-light">
                <div class="d-flex flex-column flex-md-row">
                    {{--  --}}
                    <div class="col-12 col-md-3">
                        <label class="mb-2" for="positions">
                            <strong>User email</strong>
                        </label>

                        <div class="input-group">
                            <input
                                id="positions"
                                name="filter[email]"
                                type="text"
                                value="{{ $filter['email'] ?? null }}"
                                onchange="return document.getElementById('filter_form').submit()"
                                class="form-control d-inline-block">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary mt-2">
                    <i class="fas fa-filter"></i>
                    Filter
                </button>
            </div>
        </form>
    </div>

    <table class="table">
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>
                    <strong>{{ $user->name }}</strong>
                    <span class="badge bg-light text-{{ $user->isAdmin() ? 'success' : 'secondary' }}">
                        {{ $user->role }}
                    </span>
                    <br>
                    {{ $user->email }}
                </td>

                <td>
                    <strong>{{ \Carbon\Carbon::createFromTimeString($user->created_at)->diffForHumans() }}</strong><br>
                    {{ \Carbon\Carbon::createFromTimeString($user->created_at) }}
                </td>

                <td width="230" class="text-end">
                    <ul class="btn-group btn-group-sm">
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-light">
                            <i class="far fa-edit me-1"></i>Edit
                        </a>

                        <form action="{{ route('users.delete') }}" method="post" onsubmit="return confirm('Confirm the deletion of the user.')">
                            @csrf
                            <input type="hidden" name="id" value="{{ $user->id }}">
                            <button type="submit" class="btn btn-sm btn-light">
                                <i class="far fa-trash-alt me-1 text-danger"></i>Delete
                            </button>
                        </form>
                    </ul>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $users->links() }}
@endsection
