@extends('blackshot::layouts.app')

@section('content')
    <div class="d-flex align-items-center justify-content-between px-2 mb-4">
        <div>
            <h1>
                <i class="fas fa-users text-secondary" aria-hidden="true"></i>
                <strong>Edit</strong>
            </h1>
        </div>
    </div>

    <form action="{{ route('users.store') }}" method="post">
        @csrf

        @if(isset($user))
            <input type="hidden" name="id" value="{{ $user->id }}">
        @endif

        <table class="table">
            <colgroup>
                <col span="1" style="width: 30%; text-align: right;" />
                <col span="1" />
            </colgroup>
            <tbody>
                <tr>
                    <td>
                        <label for="name">Name</label>
                        <span class="text-danger">*</span>
                    </td>
                    <td>
                        <input type="text" id="name" name="name" class="form-control" value="{{ old('name', isset($user) ? $user->name : null) }}" required>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="email">Email</label>
                        <span class="text-danger">*</span>
                    </td>
                    <td>
                        <input type="email"
                               id="email"
                               name="email"
                               class="form-control"
                               value="{{ old('email', isset($user) ? $user->email : null) }}" required>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="password">Password</label>
                        @if(!isset($user))
                            <span class="text-danger">*</span>
                        @else
                            <br>
                            <span class="text-secondary">
                                Fill in if you want to change your password.
                            </span>
                        @endif
                    </td>
                    <td>
                        <input type="password"
                               id="password"
                               name="password"
                               autocomplete="off"
                               class="form-control" @if(!isset($user))required @endif>
                    </td>
                </tr>

                @if (\Illuminate\Support\Facades\Auth::user()->isAdmin())
                <tr>
                    <td>
                        <label for="role">Role</label>
                        <span class="text-danger">*</span>
                    </td>
                    <td>
                        <select name="role" id="role">
                            <option value="{{ \App\Models\User::ROLE_USER }}" @if ((isset($user) && old('role', $user->role) === \App\Models\User::ROLE_USER) || old('role') === \App\Models\User::ROLE_USER) selected @endif>User</option>
                            <option value="{{ \App\Models\User::ROLE_ADMIN }}" @if ((isset($user) && old('role', $user->role) === \App\Models\User::ROLE_ADMIN) || old('role') === \App\Models\User::ROLE_ADMIN) selected @endif>Administrator</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>
                        <label for="expired_at">Expired at</label>
                    </td>
                    <td>
                        <input type="date"
                               id="expired_at"
                               name="expired_at"
                               value="{{ old('expired_at', $user ? (new DateTimeImmutable($user->expired_at))->format('Y-m-d') : null) }}"
                               class="form-control">
                    </td>
                </tr>
                @endif

                @if(isset($user) && $user->favorites->count())
                    <tr>
                        <td colspan="2" class="table-light">
                            <strong>User favorites</strong>
                        </td>
                    </tr>

                    @foreach($user->favorites->load('info') as $coin)
                        <tr>
                            <td colspan="2">
                                @if (isset($coin->info->logo))
                                <img src="{{ $coin->info->logo }}" alt="" height="24" class="me-1">
                                @endif
                                {{ $coin->name }}
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>

        <p>
            <button type="submit" class="btn btn-outline-success">
                <i class="far fa-save"></i>
                Save
            </button>
        </p>
    </form>
@endsection
