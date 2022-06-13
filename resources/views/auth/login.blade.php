@extends('blackshot::layouts.auth')

@section('content')
<div class="auth-container">
    <div class="login-container">
        <h1>Войти</h1>

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

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-control-row">
                <label for="email">{{ __('E-Mail') }}</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                @error('email')
                <p class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </p>
                @enderror
            </div>

            <div class="form-control-row">
                <label for="password">{{ __('Пароль') }}</label>
                <input id="password" type="password" name="password" required autocomplete="current-password">
                @error('password')
                <p class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </p>
                @enderror
            </div>

            <div class="forgot">
                <div>
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">
                        {{ __('Запомнить меня') }}
                    </label>
                </div>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}">
                        {{ __('Забыли пароль?') }}
                    </a>
                @endif
            </div>

            <div class="form-control-row row">
                <button type="submit" class="btn btn1">
                    {{ __('Войти') }}
                </button>

                @if (Route::has('register'))
                    <a class="btn btn-light" href="{{ route('register') }}">
                        {{ __('Зарегистрироваться') }}
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection
