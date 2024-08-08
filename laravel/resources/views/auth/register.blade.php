@extends('layouts.app')

@section('content')

<form method="POST" action="{{ route('register') }}">
    {{ csrf_field() }}

    <label style="font-size:18px;" for="username">Username</label>
    <input id="username" type="text" name="username" value="{{ old('username') }}" required autofocus style="font-size:15px;">
    @if ($errors->has('username'))
      <span class="error">
          {{ $errors->first('username') }}
      </span>
    @endif

    <label style="font-size:18px;" for="email">E-Mail Address</label>
    <input id="email" type="email" name="email" value="{{ old('email') }}" required style="font-size:15px;">
    @if ($errors->has('email'))
      <span class="error">
          {{ $errors->first('email') }}
      </span>
    @endif

    <label style="font-size:18px;" for="password">Password</label>
    <input id="password" type="password" name="password" required style="font-size:15px;">
    @if ($errors->has('password'))
      <span class="error">
          {{ $errors->first('password') }}
      </span>
    @endif

    <label style="font-size:18px;" for="password-confirm">Confirm Password</label>
    <input id="password-confirm" type="password" name="password_confirmation" required style="font-size:15px;">

    @if ($errors->has('age'))
      <span class="error">
          {{ $errors->first('age') }}
      </span>
    @endif
    <label>
        <input type="checkbox" name="age" {{ old('age') ? 'checked' : '' }} required> I am over 18 years old
    </label>
    <br></br>
    <button type="submit" class="button">
      Register
    </button>
</form>
@endsection