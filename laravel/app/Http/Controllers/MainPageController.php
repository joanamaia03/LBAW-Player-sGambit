<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Auction;

class MainPageController extends Controller
{
    public function show()
    {
        
        $mostPopular = Auction::where('state','!=','Ended- Waiting Exchange')->where('state','!=','Closed')->where('state','!=','Waiting Payment')->orderBy('price','desc')->take(10)->get();

        $mostRecent = Auction::where('state','!=','Ended- Waiting Exchange')->where('state','!=','Closed')->where('state','!=','Waiting Payment')->orderBy('end_date','desc')->take(10)->get();

        $nearlyCompleted = Auction::where('state','!=','Ended- Waiting Exchange')->where('state','!=','Closed')->where('state','!=','Waiting Payment')->orderBy('end_date','asc')->take(10)->get();

        return view('pages.mainPage', ['auctions1' => $mostPopular, 'auctions2' => $mostRecent, 'auctions3' => $nearlyCompleted]);
    }
}
