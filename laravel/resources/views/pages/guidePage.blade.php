@extends('layouts.app')

@section('title', 'Main')

@section('content')

<div class="static" style="margin:10px;">
    <h1>Player's Gambit Guide</h1>
    <p>Welcome to Player's Gambit, where the thrill of auctions meets the excitement of gaming! Whether you're a seasoned bidder or a newcomer to the auction scene, this guide will walk you through the steps to navigate our platform and make the most out of your gaming experience.</p>
    <br></br>
    <h2>Auction Guide</h2>
    <ol>
        <h3>
            <li> Searching for Auctions</li>
        </h3>
        <ul>
            <li>Use the search bar to search for one or more auctions</li>
            <li>You can filter by category using the Category Select</li>
            <li>If you want to see the most popular, most recent and nearly completed auctions, you can click on the respective sections on the main page</li>
        </ul>
        <h3>
            <li>Creating an Auction</li>
        </h3>
        <ul>
            <li>Any authenticated user can create an auction</li>
            <li>Click on the button in your profile page to navigate to the Auction Creation page.</li>
            <li>Provide a product photo, the auction's name, the location of where the auction is originally from, the category, a description and specify the minimum value at which you are willing to sell it.</li>
            <li>Note the five percent tax on the estimated price, payable before posting the auction.</li>
        </ul>
        <h3>
            <li>Editing an Auction</li>
        </h3>
        <ul>
            <li>You can only edit your own auction when you are authenticated and if it's not Ended- Waiting Exchange or Closed</li>
            <li>Click on the auction you want to edit and then on the Edit Auction button to navigate to the Edit Auction Page</li>
            <li>You can change the auction's name, the location, the category and the description but the minimum price is fixed</li>
            <li>Note that you can still see the information that you are not able to edit</li>
        </ul>
    </ol>
    <h2>Bid Guide</h2>
    <ol>
        <h3>
            <li>Placing a Bid</li>
        </h3>
        <ul>
            <li>You can only place a bid if you are not the creater of the auction and if they aren't Ended- Wainting Exchange or Closed</li>
            <li>Click on the auction you want to bid on</li>
            <li>Then you just have to place your bid and save it through the Place Bid button</li>
        </ul>
        <h3>
            <li>Outbid Notifications</li>
        </h3>
        <ul>
            <li>Receive real-time notifications on the website and via email whenever another user outbids you.</li>
            <li>Stay informed and place higher bids if you choose to.</li>
        </ul>
    </ol>
</div>
    
@endsection