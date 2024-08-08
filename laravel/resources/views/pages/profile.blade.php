@extends('layouts.app')

@section('name', $user->name)

@section('content') 

<body>
  <main>
    <div class="col">
      <div class="row">
          <div class="col" style="margin-top:200px; margin-left:180px; font-size:30px;">
            <a class="butao" href="{{ route('profileEdit', ['id' => Auth::user()->user_id]) }}">Edit profile</a><br>
            <a class="butao" href="{{ route('showCreateAuction') }}">Create Auction</a><br>
            <a class="butao" href="{{ route('history', ['user_id'=>Auth::user()->user_id, 'choice'=>'BidHistory']) }}">Bid History</a>
            <br>
            <a class="butao" href="{{ route('history', ['user_id'=>Auth::user()->user_id, 'choice'=>'MyAuctions']) }}">My Auctions</a> 
          </div>
          <div class="col" style="margin-top:90px; margin-right:200px;">
            <div class="card" style="width:1000px; height: 447px; margin-right:5px; margin-bottom:20px;">
                  <div class="row">
                      <div class="col">
                        <img src="{{ $user->getProfileImage() }}" alt="profile pic" style="width: 500px; height: 447px; object-fit: cover;">
                      </div>
                      <div class="col" style="font-size:15px;">
                          <div class="info">
                            <center>
                              <p style="font-size: 30px;"><strong>Name: <br></strong> {{$user->username}}</p>
                              <p style="font-size: 30px;"> <strong>Username: <br></strong> {{$user->name}}</p>
                              <p style="font-size: 30px;"><strong> Email: <br> </strong>{{$user->email}}</p> 
                              <a class="button" onclick="return confirm('Are you sure you want to delete your account?\nThis action will be permanent.')" href="{{ route('deleteAccount', ['id' => Auth::user()->user_id]) }}">Delete Account</a><br>
                              @if ($user->is_admin)
                                  <a class="button" href="{{ route('admin') }}">Admin Page</a><br>
                              @endif
                            </center>
                          </div>    
                      </div>
                  </div>
            </div>
          </div>
      </div>
    </div>
  </main>
</body>
@endsection
