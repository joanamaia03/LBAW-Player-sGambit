@php
    use App\Models\User;
    use App\Models\ProductImages;
    use App\Models\Ban;
@endphp

<article class="auction" data-id="{{ $auction->auction_id }}">
    <br></br>
    <center>
        <div class="col">
            <div class="card" style="width:560px; height: 200px; margin-right:5px; margin-bottom:20px;">
                <div class="row">
                    <div class="col">
                        <img src="{{ $auction->getAuctionImage() }}" alt="auction image" class="img" style="height:198px; width:300px; object-fit: cover;" />
                    </div>
                    <div class="col" style="font-size:15px;">
                        <div class="info">
                            <p><a href="/auction/{{ $auction->auction_id }}" class="fw-bold" style="text-decoration:none">{{ $auction->name }} </a></p>
                            <p><strong>Creator:</strong> 
                                @if(User::find($auction->user_id)->is_deleted)
                                    [Deleted]
                                @elseif(Ban::firstWhere('user_id', User::find($auction->user_id)->user_id) != null)
                                    [Deactivated]
                                @else
                                    {{ User::find($auction->user_id)->username }}
                                @endif
                            </p>
                            <p><strong>End Date:</strong> {{$auction->end_date}}</p>
                            <p><strong>Current Bid:</strong> {{$auction->price}}</p>
                            <p><strong>Category:</strong> {{$auction->type}}</p>
                        </div>    
                    </div>
                </div>
            </div>
        </div>
    </center>
</article>
