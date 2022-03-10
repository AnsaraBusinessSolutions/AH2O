<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
<script
  src="https://code.jquery.com/jquery-3.6.0.min.js"
  integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
  crossorigin="anonymous"></script>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <script>
        $(document).ready(function(){
            $.ajaxSetup({
            headers:{
                "Authorization" : "Bearer "+localStorage.getItem("token"),
                "_token":$("meta[name='csrf-token']").attr("content")
            }
            });
            $(".user-setting-load").off("click").on("click",function(){
                let user_setting_page = $(this).data("page");
                if(user_setting_page){
                  user_setting_page = "/api/"+user_setting_page;
                }
                $.ajax({
                    url : user_setting_page,
                    type:"GET",
                    data:{},
                    success:function(response){
                      $(".processing").hide();
                      $(".display_content_area").html(response.html);
                      $(".display_content_area").show();

                    },
                    error:function(xhr,text,error){
                      $(".processing").hide();
                      $(".display_content_area").html();
                      $(".display_content_area").show();
                    }

                });';'
            });

        });//''
        </script>


    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/sidebar.css') }}" rel="stylesheet">
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
</head>
<body>


    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <!--<li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>-->
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item user-setting-load" data-page='themes' href="#"
                                       >
                                        {{ __('Themes') }}
                                    </a>
                                    <a class="dropdown-item user-setting-load" data-page='settings' href="#"
                                       >
                                        {{ __('Settings') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
<div id="wrapper">

@if(Auth::guard()->check())
       @if(Auth::user()->role==1) 
<nav class="navbar navbar-expand-sm bg-dark navbar-dark navbar-sidebar">
  <!-- Brand -->
  <!--
  <a class="navbar-brand" href="#">Logo</a>
-->
  <!-- Links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link nav-item-menu Dashboard" href="#" >Dashboard</a>
    </li>
    
    <li class="nav-item">
      <a class="nav-link nav-item-menu" href="#" id="navbardropmanagement">
          Management    
      </a>
      <!--
      <div class="dropdown-menu" aria-labelledby="navbardropmanagement">
        <a class="dropdown-item" href="#">Domain</a>
        <a class="dropdown-item" href="#">Source</a>
        <a class="dropdown-item" href="#">User</a>
        <a class="dropdown-item" href="#">DB</a>
        <a class="dropdown-item" href="#">Client</a>
        <a class="dropdown-item" href="#">FileSystem</a>
        <a class="dropdown-item" href="#">VersionControl</a>
      </div>
      -->
    </li>
    <li class="nav-item dropdown" >
      <a class="nav-link dropdown-toggle" href="#" id="componentdropmanagement" data-toggle="dropdown">Components</a>
      <div class="dropdown-menu" aria-labelledby="componentdropmanagement">
          <a class="dropdown-item" href="#">Buttons</a>
          <a class="dropdown-item" href="#">Input fields</a>
          <a class="dropdown-item" href="#">Dropdown</a>
          <a class="dropdown-item" href="#">Anchor</a>
      </div>
    </li>
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle pages" href="#" id="pagedropmanagement" data-toggle="dropdown">Pages</a>
      <div class="dropdown-menu" aria-labelledby="pagedropmanagement">
          <a class="dropdown-item" href="#">Error pages</a>
      </div>
    </li>
    <li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle pages" href="#" id="formdropmanagement" data-toggle="dropdown">Forms</a>
      <div class="dropdown-menu" aria-labelledby="formdropmanagement">
          <a class="dropdown-item" href="#">Login Form</a>
          <a class="dropdown-item" href="#">Register Form</a>
          <a class="dropdown-item" href="#">Subscription Form</a>
      </div>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#">Reports</a>
    </li>

    <!-- Dropdown -->
    

  </ul>
</nav>
	@else
	&nbsp;
	@endIf
@endIf
</div>
        <main class="py-4">
            @yield('content')
            @yield('footer-scripts')
        </main>
    </div>
<input type="hidden" name="access_token" id="access_token" value="">
</body>
</html>
