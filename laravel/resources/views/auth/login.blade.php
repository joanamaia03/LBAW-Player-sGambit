@extends('layouts.app')

@section('content')

<form method="POST" action="{{ route('login') }}">
    {{ csrf_field() }}
        <label style="font-size:20px;" for="email">E-mail</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus style="font-size:15px;">
        @if ($errors->has('email'))
            <span class="error">
            {{ $errors->first('email') }}
            </span>
        @endif

        <label style="font-size:20px;" for="password" >Password</label>
        <input id="password" type="password" name="password" required style="font-size:15px;">
        @if ($errors->has('password'))
            <span class="error">
                {{ $errors->first('password') }}
            </span>
        @endif

        <label>
            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
        </label>
        <br></br>
        <button type="submit" class="button">
            Login
        </button>
        <br></br>
        <p>Don't have an account?</p>
        <a class="button button-outline" href="{{ route('register') }}">Register</a>
        @if (session('success'))
            <p class="success">
                {{ session('success') }}
            </p>
        @endif
</form>
@endsection