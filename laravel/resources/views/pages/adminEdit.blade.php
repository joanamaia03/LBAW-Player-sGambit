@extends('layouts.app')

@section('content')
<body>
    <main>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <img src="{{ $user->getProfileImage() }}" style="width: 300px; height: 337px; object-fit: cover; margin:15px;">
        <form method="POST" action="{{ route('adminEdit', ['id' => $user->user_id]) }}">
            {{ csrf_field() }}
            <br>
          <div class="form-group">
              <label style="font-size:20px;" class="col-sm-4">Name</label>
              <div class="col-sm-8">
                  <input class="form-control" placeholder="New name" required name="name" type="text"
                      value="{{$user->name}}" style="font-size:15px;">
              </div>
          </div>
          <div class="form-group">
              <label style="font-size:20px;" class="col-sm-4">Username</label>
              <div class="col-sm-8">
                  <input class="form-control" placeholder="New username" required name="username" type="text"
                      value="{{$user->username}}" style="font-size:15px;">
              </div>
          </div>
          <div class="form-group">
              <div class="col-sm-offset-4 col-sm-8">
                <button class="button" type="submit">Save</button>
              </div>
          </div>
        </form>
    </main>
</body>

@endsection