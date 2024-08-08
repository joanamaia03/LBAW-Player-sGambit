@extends('layouts.app')

@section('title', 'Main')

@section('content')

<section id="page">
    <article class="auction">
        <form class="new_auction">
            <center>
                <br></br>
                <h1><a class="text-danger" href="{{  route('allAuctions' , ['section' => 'popular'])}}" style="text-decoration:none">Most Popular Auctions</a></h1>
                    <div id="carouselExampleControls1" class="carousel carousel-dark slide" data-interval="false">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <div class="card-wrapper">
                                    @for ($i = 0; $i < 5; $i++)
                                        @include('partials.details', ['auction' => $auctions1[$i]])
                                    @endfor
                                </div>
                            </div>
                            <div class="carousel-item">
                                <div class="card-wrapper">
                                    @for ($i = 5; $i < 10; $i++)
                                        @include('partials.details', ['auction' => $auctions1[$i]])
                                    @endfor
                                </div>
                            </div>
                        </div>
                        <button class="carousel-control-prev" style="width: 30px; height:200px;" data-bs-target="#carouselExampleControls1" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>
                        <button class="carousel-control-next" style="width: 30px; height:200px;" data-bs-target="#carouselExampleControls1" data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>
                    </div>
                <br></br>
                <h1><a class="text-danger" href="{{ route('allAuctions', ['section' => 'recent']) }}" style="text-decoration:none">Recent Auctions</a></h1>
                    <div id="carouselExampleControls2" class="carousel carousel-dark slide" data-interval="false">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <div class="card-wrapper">
                                    @for ($i = 0; $i < 5; $i++)
                                        @include('partials.details', ['auction' => $auctions2[$i]])
                                    @endfor
                                </div>
                            </div>
                            <div class="carousel-item">
                                <div class="card-wrapper">
                                    @for ($i = 5; $i < 10; $i++)
                                        @include('partials.details', ['auction' => $auctions2[$i]])
                                    @endfor
                                </div>
                            </div>
                        </div>
                        <button class="carousel-control-prev" style="width: 30px; height:200px;" data-bs-target="#carouselExampleControls2" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>
                        <button class="carousel-control-next" style="width: 30px; height:200px;" data-bs-target="#carouselExampleControls2" data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>
                    </div>
                <br></br>    
                <h1><a class="text-danger" href="{{ route('allAuctions', ['section' => 'nCompleted']) }}" style="text-decoration:none">Nearly Completed Auctions</a></h1>
                    <div id="carouselExampleControls3" class="carousel carousel-dark slide" data-interval="false">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <div class="card-wrapper">
                                    @for ($i = 0; $i < 5; $i++)
                                        @include('partials.details', ['auction' => $auctions3[$i]])
                                    @endfor
                                </div>
                            </div>
                            <div class="carousel-item">
                                <div class="card-wrapper">
                                    @for ($i = 5; $i < 10; $i++)
                                        @include('partials.details', ['auction' => $auctions3[$i]])
                                    @endfor
                                </div>
                            </div>
                        </div>
                           <button class="carousel-control-prev" style="width: 30px; height:200px;" data-bs-target="#carouselExampleControls3" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>
                        <button class="carousel-control-next" style="width: 30px; height:200px;" data-bs-target="#carouselExampleControls3" data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>
                    </div>
            </center>
        </form>
    </article>
</section>


@endsection