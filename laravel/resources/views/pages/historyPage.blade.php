@extends('layouts.app')

@section('name', 'auctions')

@section('content') 

<body>
    <main>
        <br>
        <center>
            <div class="title">
                <h1>
                    @if ($choice == 'MyAuctions')
                        My Auctions
                    @elseif ($choice == 'BidHistory')
                        My Bids
                    @endif
                </h1>
            </div>
        </center>
        @if (count($history) == 0)
            <br>
            <center>
                <p class="m-0">
                    @if ($choice == 'MyAuctions')
                        You haven't created any auctions yet! Go to the guide at the bottom of the page to find out how to create one!
                    @elseif ($choice == 'BidHistory')
                        You haven't bid on any auctions yet! Go to the guide at the bottom of the page to find out how to bid in one!
                    @endif
                </p>
                <br>
                <div>
                    <a class="button" style="margin-top:1%;" href="{{ url()->previous() }}"> Go back </a>
                </div>
            </center>
        @else
            <section id="auctions" style="margin:20px;">
                @each('partials.oneAuction', $history, 'auction')
            </section>
        @endif
    </main>
</body> 

@endsection