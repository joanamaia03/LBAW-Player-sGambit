<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\ProductImages;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CreateAuctionController extends Controller
{
    public function showCreateAuction()
    {
        if(!Auth::check()){
            return redirect("/mainPage");
        }
        else {
            return view('pages.createAuction');
        }
    }

    public function createAuction(Request $request)
    {
        $request -> validate([
            'name' => 'required|max:255',
            'description' => 'required|max:500',
            'min_price' => 'required|numeric|min:0',
            'location' => 'required|max:255',
            'type' => 'required|in:"Video Games", "Board Games", "Card Games"',
        ]);

        $content = $request; 

        $auction = new Auction();
        
        $auction->name = $content->input('name');
        $auction->price = 0;
        $auction->description = $content->input('description');
        $auction->min_price = $content->input('min_price');
        $auction->location = $content->input('location');
        $auction->type = $content->input('type');
        $auction->end_date = date('Y-m-d', strtotime(date('Y-m-d'). ' + 15 days'));
        $auction->state = 'Occurring';
        $auction->user_id = Auth::user()->user_id;

        $auction->save();

        $product_images = new ProductImages();

        $product_images->auction_id = $auction->auction_id;
        $product_images->image = "default.png";
        $product_images->save();

        return redirect("/auction/$auction->auction_id");
    }
}
