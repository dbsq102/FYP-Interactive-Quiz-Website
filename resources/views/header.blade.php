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
    <!-- Font awesome style -->
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet" />
    
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/main.css') }}">
</head>
<body>
    <div id='container'>
        <div id='menu'>
            <!--If user is a student-->
            @if (Auth::user()->role == 0)
                <p1></p1>
                <a class="nav" href="{{ route('home') }}">Home</a>
                <a class="nav" href="{{ route('managequiz') }}">Manage Quizzes</a>
                <a class="nav" href="<?php echo url('reports') ?>">Reports</a>
                <a class="nav" href="<?php echo url('groups') ?>">Groups</a>
                <div class="nav-right">
                    <p2>{{Auth::user()->username}}</p2>
                    <p2>Student Page</p2>
                    <a class="logout" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            <!--If user is an educator-->
            @else                 
                <p1></p1>
                <a class="nav" href="{{ route('home') }}">Home</a>
                <a class="nav" href="{{ route('managequiz') }}">Manage Quizzes</a>
                <a class="nav" href="<?php echo url('reports') ?>">Reports</a>
                <a class="nav" href="<?php echo url('groups') ?>">Groups</a>
                <div class="nav-right">
                    <p2>{{Auth::user()->username}}</p2>
                    <p2>Educator Page</p2>
                    <a class="logout" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            @endif
        </div>
    </div>