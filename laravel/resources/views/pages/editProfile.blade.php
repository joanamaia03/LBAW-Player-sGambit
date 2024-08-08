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
        <div>
            <div class="profile" style="padding: 10px;">
                <img src="{{ $user->getProfileImage() }}" style="width: 300px; height: 337px; object-fit: cover;">
                <br></br>
                <form method="POST" action="/file/upload" enctype="multipart/form-data">
                    @csrf
                    <input name="file" type="file" required>
                    <input name="id" type="number" value="{{ $user->user_id }}" hidden>
                    <input name="type" type="text" value="profile" hidden>
                    <button class="upload" type="submit">Upload</button>
                </form>
                <p> Warning: When you click upload, the image will automatically be saved and your old photo will be discarded!</p>
            </div>
        </div><br><br>
        <form method="POST" action="{{ route('editProfile', ['id' => Auth::user()->user_id]) }}">
            {{ csrf_field() }}
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
              <label style="font-size:20px;" class="col-sm-4">Email</label>
              <div class="col-sm-8">
                  <input class="form-control" placeholder="New email" required name="email" type="text"
                      value="{{$user->email}}" style="font-size:15px;">
              </div>
          </div>
          <div class="form-group">
              <label style="font-size:20px;" class="col-sm-4">Current Password</label>
              <div class="col-sm-8">
                <input class="form-control" placeholder="Password" pattern=".{5,}" name="password" type="password" title="5 characters minimum" required style="font-size:15px;">
              </div>
          </div>
          <div class="form-group">
              <label style="font-size:20px;" class="col-sm-4">New Password</label>
              <div class="col-sm-4">
                <input class="form-control" placeholder="New password" pattern=".{5,}" name="new_password" type="password" title="5 characters minimum" style="font-size:15px;">
              </div>
              <div class="col-sm-4">
                <input class="form-control" placeholder="Confirm new password" pattern=".{5,}" name="confirm_new_password" type="password" title="5 characters minimum" style="font-size:15px;">
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