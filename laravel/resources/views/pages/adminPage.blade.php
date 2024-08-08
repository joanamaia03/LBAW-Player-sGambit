@extends('layouts.app')

@section('content')

@php
    use App\Models\User;
@endphp

<body>
    <main class="d-flex flex-column align-items-center">
        <form class="search bar linha nom" method="get" action="{{ url('/api/user/search') }}">
            <div class="margin percent">
                <input name="searchUsers" type="search" placeholder="Search Users" style="font-size:15px;">
            </div>
            <button class="button" type="submit">Search</button>
        </form>
        <br>
        <br>
        <center>
            <table class="table mx-auto">
                <thead>
                    <tr>
                        <th scope="col" style="padding-right: 50px;">Id</th>
                        <th scope="col" style="padding-right: 250px;">Name</th>
                        <th scope="col" style="padding-right: 100px;">Username</th>
                        <th scope="col" style="padding-right: 250px;">Email</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @each('partials.oneUser', $users, 'user')
                </tbody>
            </table>
            {{ $users->links('pagination::bootstrap-5')}}
        </center>
    </main>
</body>

@endsection