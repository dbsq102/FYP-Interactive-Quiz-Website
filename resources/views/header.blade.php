<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Quiz Website</title>

    <!-- Script -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.css') }}" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.jquery.min.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <!-- Font awesome style -->
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet" />
    
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/main.css') }}">
</head>
<body>
    <div id='container'>
        <div id='menu'>
            <p1 class="nav2"><img src="{{asset('/images/logo.png')}}" style="width:80px"></p1>
            <a class="nav" href="{{ route('home') }}">Home</a>
            <a class="nav" href="{{ route('managequiz') }}">Quizzes</a>
            <a class="nav" href="{{ route('reports-view', 0) }}">Reports</a>
            <a class="nav" href="{{ route('groups-view', 0)}}">Groups</a>
            <!--If user is a student-->
            @if (Auth::user()->role == 0)
            <div class="nav-right">
                <p2>{{Auth::user()->username}}</p2>
                <p2>Student Page</p2>
            <!--If user is an educator-->
            @else                 
            <div class="nav-right">
                <p2>{{Auth::user()->username}}</p2>
                <p2>Educator Page</p2>
            @endif
                <a class="logout" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                {{ __('Logout') }}
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>
    </div>
    @if(Session::has('message'))
        <p class="alert alert-info">{{Session::get('message')}}</p>
    @endif