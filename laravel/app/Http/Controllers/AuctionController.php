<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Bid;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuctionController extends Controller
{
    public function showAuction(int $auction_id)
    {
        $auction = Auction::find($auction_id);
        $creator = User::find($auction->user_id);
        return view('pages.auction', ['auction' => $auction, 'creator' => $creator]);
    }

    public function showAllAuctions(string $section)
    {
        if ($section == "popular") {
            $allAuctions=Auction::where('state','!=','Ended- Waiting Exchange')->where('state','!=','Closed')->where('state','!=','Waiting Payment')->orderBy('price','desc')->paginate(10);
        } elseif ($section == "recent") {
            $allAuctions=Auction::where('state','!=','Ended- Waiting Exchange')->where('state','!=','Closed')->where('state','!=','Waiting Payment')->orderBy('end_date','desc')->paginate(10);
        } elseif ($section == "nCompleted") {
            $allAuctions=Auction::where('state','!=','Ended- Waiting Exchange')->where('state','!=','Closed')->where('state','!=','Waiting Payment')->orderBy('end_date','asc')->paginate(10);
        } else {
        $allAuctions=[];
        }
    
       return view('pages.allAuctions', ['auctions' => $allAuctions, 'section' => $section]);
    }

    public function showHistory(string $choice)
    {
        if(!Auth::check()){
            return redirect("/mainPage");
        }
        
        if ($choice=="MyAuctions"){
            $history=Auction::where('user_id', Auth::user()->user_id)->orderBy('end_date','desc')->get();

        }elseif ($choice=="BidHistory"){
            $bids = Bid::orderBy('timestamp','desc')->where('user_id',Auth::user()->user_id)->pluck('auction_id')->toArray();
            $history=array();
            foreach($bids as $bid){
                $history[]=Auction::find($bid);
            }
                       
        }else{
            return redirect("/mainPage");
        }

        return view('pages.historyPage', ['history' => $history, 'choice' => $choice]);
    }

    public function addBid(Request $request, $auction_id)
    {
        $content = $request;
        $auction = Auction::find($auction_id);
        $request->validate([
            'bid-amount'=>'required|numeric|min:'.$auction->price+1,
        ]);

        $bid = new Bid();

        $bid->auction_id = $auction_id;
        $bid->user_id = Auth::user()->user_id;
        $bid->value = $content->input('bid-amount');
        $auction->price = $content->input('bid-amount');
        $bid->timestamp = date('Y-m-d');
        
        $bid -> save();
        $auction-> save();

        return redirect("/auction/".$auction_id);
    }
   
    public function showEditAuction(int $auction_id)
    {
        $auction = Auction::find($auction_id);

        if (!$auction || $auction->user_id !== Auth::user()->user_id) {
            return redirect("/mainPage")->with('error', 'Auction not found');
        }

        return view('pages.editAuction', ['auction' => $auction]);
    }

    public function editAuction(Request $request, $auction_id)
    {
        $auction = Auction::find($auction_id);

        if (!$auction || $auction->user_id !== Auth::user()->user_id){
            return redirect("/mainPage")->with('error', 'Auction not found');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'type' => 'required|in:"Video Games", "Board Games", "Card Games"',
        ]);

        $auction->name = $request->name;
        $auction->description = $request->description;
        $auction->location = $request->location;
        $auction->type = $request->type;

        $auction->save();

        return redirect("/auction/$auction->auction_id");

    }
}
