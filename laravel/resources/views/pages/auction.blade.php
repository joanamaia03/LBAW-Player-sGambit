@extends('layouts.app')

@section('title', $auction->name)

@section('content')

@php
    use App\Models\Ban;
    use App\Models\User;
    use App\Models\ProductImages;
@endphp


<section id="Auction">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div id="auction-item">
        <div class="col">
            <div class="row">
                <div class="col">
                    <img src="{{ $auction->getAuctionImage() }}" class="img" style="height:400px; width:800px; object-fit: cover; margin-top:20px;"/>
                </div>
                <div class="col" style="font-size:20px; text-align:center; margin-top:30px;">
                    <div class="static">
                        <br></br>
                        <h1>{{ $auction->name }}</h1>
                        <p><strong>Creator:</strong>
                        @if (Ban::firstWhere('user_id', $creator->user_id) != null)
                            [Deactivated]
                        @elseif ($creator->is_deleted)
                            [Deleted]
                        @else
                            {{ $creator->username }}
                        @endif 
                        </p>
                        <p><strong>End Date:</strong> {{$auction->end_date}}</p>
                        <p><strong>Current Bid:</strong> {{$auction->price}}</p>
                        @if (!Auth::check())
                            <span style="color:#900;"> Log in or register to be able to bid! </span>
                        @elseif ($auction->state=='Ended- Waiting Exchange')
                            <span style="color:#900;"> This auction has ended!</span>
                        @elseif ($auction->state=='Closed')
                            <span style="color:#900;"> This auction is officially closed!
                        @elseif (Auth::user()->user_id !== $creator->user_id)
                            <div id="bid-form">
                                <center>
                                    <h2>Place Your Bid</h2>
                                    <form method="POST" action="{{ route('addBid', ['id' => $auction->auction_id]) }}">
                                        {{ csrf_field() }}
                                        <div class="bid" style="width:200px;">
                                            <input type="number" id="bid-amount" name="bid-amount" min="1" step="1" required> 
                                            <button type="submit">Place Bid</button>
                                        </div> 
                                    </form>
                                </center>
                            </div>
                        @endif
                        @if (Auth::check() && Auth::user()->user_id == $creator->user_id && $auction->state!=='Ended- Waiting Exchange' && $auction->state!=='Closed')
                            <a class="button" href="{{ route('auctionEdit', ['id' => $auction->auction_id]) }}" style="width:250px;">Edit auction</a><br></br>
                        @endif
                    </div>    
                </div> 
            </div>
            <div class="static" style="font-size:15px;">   
                <br></br>
                <p><strong>Category:</strong> {{ $auction->type }}</p>
                <p><strong>Location:</strong> {{ $auction->location }}</p>
                <p><strong>Description:</strong> {{ $auction->description }}</p>
            </div>
        </div>
    </div>
</section>
@endsection