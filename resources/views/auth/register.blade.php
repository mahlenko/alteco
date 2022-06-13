@extends('blackshot::layouts.auth')

@section('content')
    <div class="auth-container">
        <div class="login-container">
            <h1>Регистрация</h1>

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

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="form-control-row">
                    <label for="name">{{ __('Имя') }}</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>
                    @error('email')
                    <p class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </p>
                    @enderror
                </div>

                <div class="form-control-row">
                    <label for="email">{{ __('E-Mail') }}</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email">
                    @error('email')
                    <p class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </p>
                    @enderror
                </div>

                <div class="form-control-row">
                    <label for="password">{{ __('Пароль') }}</label>
                    <input id="password" type="password" name="password" value="{{ old('password') }}" required>
                    @error('password')
                    <p class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </p>
                    @enderror
                </div>

                <div class="form-control-row">
                    <label for="password_confirmation">{{ __('Повторите пароль') }}</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" value="{{ old('password_confirmation') }}" required>
                    @error('password_confirmation')
                    <p class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </p>
                    @enderror
                </div>

                <div>
                    <input class="form-check-input" type="checkbox" required name="agreement" id="agreement" {{ old('agreement') ? 'checked' : '' }}>
                    <label class="form-check-label" for="agreement">
                        Я ознакомлен с <a href="/files/user-agreement.pdf" target="_blank">пользовательским соглашением</a>
                        и даю свое согласие на обработку персональных данных.
                    </label>
                </div>

                <button type="submit" class="btn btn1" style="width: 100%; margin-top: 1rem;">
                    {{ __('Зарегистрироваться') }}
                </button>

                <p style="text-align: center; margin-top: 1rem; margin-bottom: 1rem;">
                    <a href="{{ route('login')  }}">У меня есть аккаунт</a>
                </p>
            </form>
        </div>
    </div>
@endsection
