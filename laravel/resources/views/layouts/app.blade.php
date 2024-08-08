<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Player's Gambit</title>

        <!-- Styles -->
        <link rel="shortcut icon" href="{{ asset('images/logo.png') }}">
        <link href="{{ url('css/milligram.min.css') }}" rel="stylesheet">
        <link href="{{ url('css/app.css') }}" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <script type="text/javascript">
            // Fix for Firefox autofocus CSS bug
            // See: http://stackoverflow.com/questions/18943276/html-5-autofocus-messes-up-css-loading/18945951#18945951
        </script>
        <script type="text/javascript" src={{ url('js/app.js') }} defer></script>
    </head>
    <body>
        <main>
            <header>
              <div class="linha nom">
                <a href="{{ url('/') }}"><img src="{{ asset('images/logo.png') }}" width="87.63" height="100" class="navbar-brand"/></a>
                  <form class="search bar linha nom" method="get" action="{{url('/api/auction/search')}}">
                    <div class="margin percent">
                      <input name="searchAuctions" type="search" placeholder="Search Auctions">
                    </div>
                    <div class="margin perc">
                      <select name="categories" id="categories">
                          <option value="" disabled selected>Category</option>
                          <option value="Board Games">Board Games</option>
                          <option value="Card Games">Card Games</option>
                          <option value="Video Games">Video Games</option>        
                      </select> 
                    </div>
                    <button class="button" type="submit" >Search</button>   
                  </form> 

                  @if (Auth::check())
                    <div class="linha margin">
                      <a class="button" href="{{ url('/logout') }}"> Logout </a>
                      <a class="button" href="{{ route('profile', ['id' => Auth::user()->user_id]) }}"> Profile </a>
                    </div>
                  @else
                      <div class="linha margin">
                          <a class="button" href="{{ url('/login') }}"> Login </a>
                          <a class="button" href="{{ route('register') }}"> Register </a>
                      </div>
                  @endif
              </div>
            </header>
            <section id="content">
                @yield('content')
            </section>
        </main>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
      <footer>
      <br></br>
        <div class="dois_centro">
          <a class="button" href="{{ url('/aboutUs') }}">About us</a>
          <a class="button" href="{{ url('/FAQ') }}">FAQ</a>
        </div>
        <div class="dois_centro">
          <a class="button" href="{{ url('/guide') }}">Guide</a>
        </div>
        <div class="centro">
          <p id="heady">Player's Gambit</p>
          <p id="headyh">The perfect game has no fixed price</p>
        </div>
      </footer>
    </body>
</html>