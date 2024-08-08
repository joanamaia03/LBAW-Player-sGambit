<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show()
    {
        $user = User::find(Auth::user()->user_id);
        return view('pages.search',['user'=> $user]);
    }

    public function searchAuctions(Request $request)
    {
        $searchText = $request->get('searchAuctions');
        $category = $request->get('categories');

        $auctions = array();
        if(isset($searchText) && isset($category)) {
                $auctions = Auction::whereRaw('tsvectors @@ plainto_tsquery(\'english\', ?)', [$searchText])
                ->orderByRaw('ts_rank(tsvectors, plainto_tsquery(\'english\', ?)) DESC', [$searchText])->where('type', $category)->get();
        } elseif (isset($searchText)) {
            $auctions = Auction::whereRaw('tsvectors @@ plainto_tsquery(\'english\', ?)', [$searchText])
                ->orderByRaw('ts_rank(tsvectors, plainto_tsquery(\'english\', ?)) DESC', [$searchText])->get();
        } elseif (isset($category)) {
            $auctions = Auction::where('type', $category)->get();
        }
        return view('pages.searchResults', ['auctions' => $auctions, 'query' => $request->searchAuctions, 'categories' => $request->categories, 'status' => null]);
    }
    
    public function searchUsers(Request $request)
    {
        $user = User::find(Auth::user()->user_id);
        $searchText = $request->get('searchUsers');

        $users = array();
        if(isset($searchText)){
            if($user->is_admin){
                $users = User::where([
                    ['username', 'like' , '%'.$searchText.'%']
                ])->where('user_id', '!=', $user->user_id)->where('is_deleted','false')->get();
            }
        }
        return view('pages.searchUsers', ['users' => $users, 'query' => $request->searchUsers]);
        
    }

    public function filterData(Request $request)
    {
        $searchText = $request->get('searchAuctions');
        $category = $request->get('categories');
        $status = $request->get('status');

        $auctions = array();
        if(!isset($status)) {
            return $this->searchAuctions($request);
        }
        if(isset($searchText) && isset($category)) {
            if($status == 'Occurring') {
                $auctions = Auction::whereRaw('tsvectors @@ plainto_tsquery(\'english\', ?)', [$searchText])
                ->orderByRaw('ts_rank(tsvectors, plainto_tsquery(\'english\', ?)) DESC', [$searchText])->where('type', $category)->where('state','Occurring')->get();
            } elseif ($status == 'Ended') {
                $auctions = Auction::whereRaw('tsvectors @@ plainto_tsquery(\'english\', ?)', [$searchText])
                ->orderByRaw('ts_rank(tsvectors, plainto_tsquery(\'english\', ?)) DESC', [$searchText])->where('type', $category)->where('state','!=','Occurring')->get();
            }
        } elseif (isset($searchText)) {
            if($status == 'Occurring') {
                $auctions = Auction::whereRaw('tsvectors @@ plainto_tsquery(\'english\', ?)', [$searchText])
                    ->orderByRaw('ts_rank(tsvectors, plainto_tsquery(\'english\', ?)) DESC', [$searchText])->where('state','Occurring')->get();
            } elseif($status == 'Ended') {
                $auctions = Auction::whereRaw('tsvectors @@ plainto_tsquery(\'english\', ?)', [$searchText])
                    ->orderByRaw('ts_rank(tsvectors, plainto_tsquery(\'english\', ?)) DESC', [$searchText])->where('state','!=','Occurring')->get();
            }
        } elseif (isset($category)) {
            if($status == 'Occurring') {
                $auctions = Auction::where('type', $category)->where('state','Occurring')->get();
            } elseif($status == 'Ended') {
                $auctions = Auction::where('type', $category)->where('state','!=','Occurring')->get();
            }
        }
        return view('pages.searchResults', ['auctions' => $auctions, 'query' => $request->searchAuctions, 'categories' => $request->categories, 'status' =>$request->status]);
    }
}
