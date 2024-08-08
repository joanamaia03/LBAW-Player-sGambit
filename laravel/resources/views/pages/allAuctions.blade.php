@extends('layouts.app')

@section('name', 'auctions')

@section('content')

<body>
    <main>
        <center>
            <br>
            <div class="title">
                <h1>
                    @if ($section == 'popular')
                        Popular Auctions
                    @elseif ($section == 'recent')
                        Recent Auctions
                    @elseif ($section == 'nCompleted')
                        Nearly Completed Auctions
                    @endif
                </h1>
            </div>
        </center>
        <section id="auctions" style="margin:20px;">
            @each('partials.oneAuction', $auctions, 'auction')
            {{ $auctions->links('pagination::bootstrap-5')}}
        </section>
    </main>
</body>

@endsection