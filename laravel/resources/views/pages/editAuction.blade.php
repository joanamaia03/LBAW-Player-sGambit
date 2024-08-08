@extends('layouts.app')

@section('title', $auction->name)

@section('content')

@php
    use App\Models\ProductImages;
@endphp

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
        <div class="auction">
            <img src="{{ $auction->getAuctionImage() }}" class="img" style="height:400px; width:800px; object-fit: cover;" />
            <form method="POST" action="/file/upload" enctype="multipart/form-data">
                @csrf
                <input name="file" type="file" required>
                <input name="id" type="number" value="{{ $auction->auction_id }}" hidden>
                <input name="type" type="text" value="auction" hidden>
                <button type="submit">Upload</button>
            </form>
                <p>Warning: When you click upload, the image will automatically be saved and your old photo will be discarded!</p>
        </div>
        <br><br>
        <form method="POST" action="{{ route('editAuction', ['id' =>$auction->auction_id]) }}">
            {{ csrf_field() }}
            <div class="form-group">
                <label class="col-sm-4">Name</label>
                <div class="col-sm-8">
                    <input class="form-control" placeholder="New name" required name="name" type="text"
                        value="{{$auction->name}}">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4">Location</label>
                <div class="col-sm-8">
                    <input class="form-control" placeholder="New location" required name="location" type="text"
                        value="{{$auction->location}}">
                </div>
            </div>

            <div class="margin perc">
                    <label class="col-sm-4">Product's Category</label>
                    <select name="type" id="categories">
                        <option value="Board Games" {{$auction->type == 'Board Games' ? 'selected' : ''}}>Board Games</option>
                        <option value="Card Games" {{$auction->type == 'Card Games' ? 'selected' : ''}}>Card Games</option>
                        <option value="Video Games" {{$auction->type == 'Video Games' ? 'selected' : ''}}>Video Games</option>
                    </select>
                </div>
        
            <div class="form-group">
                <label class="col-sm-4">Description</label>
                <div class="col-sm-8">
                    <input class="form-control" placeholder="New description" required name="description" type="text"
                        value="{{$auction->description}}">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-4">Min Price</label>
                <div class="col-sm-8">
                    <input class="form-control" required name="min_price" type="text"
                        value="{{$auction->min_price}}" disabled>
                </div>
            </div>
            <p>Status: {{ $auction->state }}</p>
            <p>Current Bid: {{ $auction->price }}</p>
            <p>End date: {{ $auction->end_date }}</p> 
            <div class="form-group">
                <div class="col-sm-offset-4 col-sm-8">
                <button class="button" type="submit">Save</button>
                </div>
            </div>
    </main>
</body>

@endsection