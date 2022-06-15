@extends('blackshot::layouts.auth')

@section('content')

    <div class="auth-container">
        <div class="login-container">
            <h1>Новый пароль</h1>

            @include('flash::message')
            @if ($errors->count())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-control-row">
                    <label for="email">{{ __('E-mail') }}</label>
                    <input id="email" type="email" name="email" value="{{ old('email', $email) }}" required autofocus>
                    @error('email')
                    <p class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </p>
                    @enderror
                </div>

                <div class="form-control-row">
                    <label for="password">{{ __('Новый пароль') }}</label>
                    <input id="password" type="password" name="password" value="">
                    @error('password')
                    <p class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </p>
                    @enderror
                </div>

                <div class="form-control-row">
                    <label for="password_confirmation">{{ __('Повторите пароль') }}</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" value="">
                    @error('password_confirmation')
                    <p class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </p>
                    @enderror
                </div>

                <button type="submit" class="btn btn1" style="width: 100%">
                    {{ __('Сохранить пароль') }}
                </button>
            </form>
        </div>
    </div>
@endsection
