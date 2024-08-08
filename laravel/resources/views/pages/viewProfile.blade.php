@extends('layouts.app')

@section('name', $user->name)

@section('content') 

@php
  use App\Models\Ban;
@endphp

<!--<body>
  <main>
    <div class="centro cinco_rem">
    <img src="{{ $user->getProfileImage() }}" alt="profile pic" style="width: 300px; height: 337px; object-fit: cover;">
      <p>{{$user->username}}</p>
      <p>{{$user->name}}</p>
      @if (Ban::firstWhere('user_id', $user->user_id) == null)
      <a class="button" href="{{ route('ban', ['id' => $user->user_id]) }}"> Deactivate </a><br>
      @else
      <a class="button" href="{{ route('unban', ['id' => $user->user_id]) }}"> Reactivate </a><br>
      @endif
      <a class="button" href="{{ route('showAdminEdit', ['id' => $user->user_id]) }}"> Edit </a><br>
      @if (!$user->is_admin)
      <a class="button" href="{{ route('upgrade', ['id' => $user->user_id]) }}"> Up </a><br>
      @else
      <a class="button" href="{{ route('downgrade', ['id' => $user->user_id]) }}"> Down </a><br>
      @endif
    </div>
    <div class="caixa">
          <div class="column">
            <h3>My Auctions</h3>
            <a class="button" href="{{ route('history', ['user_id'=>Auth::user()->user_id, 'choice'=>'MyAuctions']) }}">More</a>
          </div>
          <div class="column">
            <h3>Bid History</h3>
            <a class="button" href="{{ route('history', ['user_id'=>Auth::user()->user_id, 'choice'=>'BidHistory']) }}">More</a>
          </div>
    </div>
</body>-->

<body>
  <main>
    <div class="col">
      <div class="row">
          <div class="col" style="margin-top:200px; margin-left:180px; font-size:30px;">
            <a class="butao" href="{{ route('showAdminEdit', ['id' => $user->user_id]) }}"> Edit Profile </a><br>
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
                              @if (Ban::firstWhere('user_id', $user->user_id) == null)
                                <a class="button" href="{{ route('ban', ['id' => $user->user_id]) }}"> Deactivate </a><br>
                              @else
                                <a class="button" href="{{ route('unban', ['id' => $user->user_id]) }}"> Reactivate </a><br>
                              @endif
                                
                              @if (!$user->is_admin)
                                <a class="button" href="{{ route('upgrade', ['id' => $user->user_id]) }}"> Promote </a><br>
                              @else
                                <a class="button" href="{{ route('downgrade', ['id' => $user->user_id]) }}"> Demote</a><br>
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
