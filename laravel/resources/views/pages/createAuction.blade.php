@extends('layouts.app')

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
        <form method="POST" action="{{ route('createdAuction') }}">
            {{ csrf_field() }}
            <br>
            <div class="form-group">
                <label style="font-size:20px;" class="col-sm-4">Auction Name</label>
                <div class="col-sm-8">
                    <input class="form-control" placeholder="The name of the auction" required name="name" type="text" style="font-size:15px;">
                </div>
            </div>
            <br>
            <div class="form-group">
                <label style="font-size:20px;" class="col-sm-4">Location</label>
                <div class="col-sm-8">
                    <input class="form-control" placeholder="Where is this auction originally from?" required name="location" type="text" style="font-size:15px;">
                </div>
            </div>
            <br>
            <label style="font-size:20px;" class="col-sm-4">Product's Category</label>
            <div class="margin perc">  
                <select style="font-size:15px;" name="type" id="categories">
                    <option value="Board Games">Board Games</option>
                    <option value="Card Games">Card Games</option>
                    <option value="Video Games">Video Games</option>
                </select>
            </div>
            <br>
            <div class="form-group">
                <label style="font-size:20px;" class="col-sm-4">Description</label>
                <div class="col-sm-8">
                    <textarea class="form-control" placeholder="Describe your auction's product" required name="description"
                        type="text" rows="10" style="height: 10em; resize:none; font-size:15px;"></textarea>
                </div>
            </div>
            <br>
            <label style="font-size:20px;" class="col-sm-4">Minimum Price</label>
            <div class="col-sm-8">
                <input class="form-control" placeholder="What's the minimun price you would sell it for?" required
                    name="min_price" type="text" style="font-size:15px;">
                <br></br>
                <p>If the auction closes and the minimum price has not be reached, the product will not sell. When creating
                    an auction, the website will aply a 5% tax over the written mininum price, which you will have to pay
                    for the auction to open for the public.</p>
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