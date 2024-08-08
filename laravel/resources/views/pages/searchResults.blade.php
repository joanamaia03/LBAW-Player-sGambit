@extends('layouts.app')

@section('content')

@if (count($auctions) !== 0)
    <div>
        <form class="filter" method="get" action="{{ route('filter') }}">
            <center>
                <div class="margin perc" >
                    <select name="status" id="categories" value="{{ $status }}" onchange="this.form.submit()" style="font-size:15px;">
                        <option value="none" selected disabled hidden> Select a filter</option>
                        <option value="">No filter</option>
                        <option value="Occurring">Occurring</option>
                        <option value="Ended">Ended</option>
                    </select>
                </div>
            </center>
            <input name="searchAuctions" type="search" value="{{ $query }}" hidden>
            @if(empty($categories))
                <input name="categories" type="text" value="" hidden>
            @else
                <input name="categories" type="text" value="{{ $categories }}" hidden>
            @endif
        </form>
    </div>
    <center>
        <p class="m-0">Results for search
        @if (isset($query) && isset($categories))
            "{{ $query }}" with type "{{ $categories }}"
        @elseif (isset($query))
            "{{ $query }}"
        @elseif (isset($categories))
            "{{ $categories }}"
        @endif
        @isset($status)
            </p><p class="m-0">Filtered by: {{ $status }}</p>
        @endisset
    </center>
    @foreach ($auctions as $auction)
        @include('partials.oneAuction', ['auction' => $auction])
    @endforeach

@else
    <center>
        <br>
        <p class="m-0">No auctions found for search
        @if (isset($query) && isset($categories))
            "{{ $query }}" with type "{{ $categories }}"
        @elseif (isset($query))
            "{{ $query }}"
        @elseif (isset($categories))
            "{{ $categories }}"
        @endif
        @isset($status)
            </p><p class="m-0">Filtered by: {{ $status }}</p>
        @endisset
        <div>
            <a class="button" style="margin-top:1%;" href="{{ url()->previous() }}"> Go back </a>
        </div>
    </center>
@endif

@endsection