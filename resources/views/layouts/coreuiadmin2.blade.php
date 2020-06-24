<!DOCTYPE html>
<!--
* CoreUI - Free Bootstrap Admin Template
* @version v2.0.0
* @link https://coreui.io
* Copyright (c) 2018 creativeLabs Łukasz Holeczek
* Licensed under MIT (https://coreui.io/license)
-->

<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="CoreUI - Open Source Bootstrap Admin Template">
    <meta name="keyword" content="Bootstrap,Admin,Template,Open,Source,jQuery,CSS,HTML,RWD,Dashboard">
    <title>@yield('title')</title>
    <link rel="shortcut icon" href="/img/brand/favicon.png" type="image/png">
   <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
            html{               
              /* background: url(../img/brand/background.jpg) no-repeat center center fixed;                 -webkit-background-size: cover; */
              -moz-background-size: cover;
              -o-background-size: cover;
              background-size: cover;
            }

            body {
                /* color: #636b6f; */
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
                /* background-color:transparent !important; */
            }

            body::before
            {
              content: "";
              display: block;
              position: absolute;
              z-index: -1;
              width: 100%;
              height: 100%;
              background:#ffffff;
              opacity: .6;
            }
    </style>
    

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <!-- <link href="{{ asset('css/style.css') }}" rel="stylesheet"> -->
     <!-- @yield('customCSS') -->
  </head>
  <body  class="app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show" >
    <header class="app-header navbar">
      <button class="navbar-toggler sidebar-toggler d-lg-none mr-auto" type="button" data-toggle="sidebar-show">
        <span class="navbar-toggler-icon"></span>
      </button>
      <a class="navbar-brand" href="#">
        <img class="navbar-brand-full" src="/img/brand/logo.png" width="108" height="35" alt="CoreUI Logo">
        <img class="navbar-brand-minimized" src="/img/brand/sygnet.png" width="35" height="35" alt="CoreUI Logo">
      </a>
      <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button" data-toggle="sidebar-lg-show">
        <span class="navbar-toggler-icon"></span>
      </button>
      <ul class="nav navbar-nav d-md-down-none">
        <li class="nav-item px-3">
            @yield('bodyTitle')
        </li>
      </ul>
      <ul class="nav navbar-nav ml-auto">
       
        <li class="nav-item dropdown" style="margin-right:20px">
        <a class="dropdown-toggle" style="cursor: pointer;" data-toggle="dropdown" role="button" aria-expanded="false">
            {{ Auth::user()->name }} <span class="caret"></span>
          </a>
          <div class="dropdown-menu dropdown-menu-right">
            <div class="dropdown-header text-center">
              <strong>Account</strong>
            </div>
            <a class="dropdown-item" style="cursor:pointer" onclick="checkSetting()">
              <i class="fa fa-gear" ></i> SMS Setting</a>
            <a class="dropdown-item" style="cursor:pointer" onclick="checkEmailSetting()">
              <i class="fa fa-gear" ></i> Email Setting</a>
            <a class="dropdown-item" href="/register">
              <i class="fa fa-user"></i> Register User</a>
            <div class="divider"></div>
            <a  class="dropdown-item" href="{{ route('logout') }}"
                onclick="event.preventDefault();
                document.getElementById('logout-form').submit();">
                <i class="fa fa-lock"></i>  Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              {{ csrf_field() }}
            </form>
          </div>
        </li>
      </ul>
    </header>
    <div id="app" class="app-body">
      <div class="sidebar">
        <nav class="sidebar-nav">
          <ul class="nav">
            <li class="nav-item">
              <router-link class="nav-link" to="/" > 
                  <i class="nav-icon fa fa-dashboard"></i> Dashboard
              </router-link>
              <!-- <router-link :to="{name: 'createCompany'}" class="btn btn-success">Create new company</router-link> -->
            </li>
            <li class="nav-title">Datasets</li>
            <li class="nav-item nav-dropdown">
              <a class="nav-link nav-dropdown-toggle" href="#">
                <i class="nav-icon fa fa-users"></i> Students</a>
              <ul class="nav-dropdown-items">
                <li class="nav-item">
                  <a class="nav-link" href="/studentlist">
                  <i class="nav-icon fa fa-list-alt"></i> All Student</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="/listgrade">
                  <i class="nav-icon fa fa-list-alt"></i> Grade Level</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="/studentadd">
                  <i class="nav-icon fa fa-plus"></i> Add</a>
                </li>
              </ul>
            </li>
            <li class="nav-item">
                <router-link class="nav-link" :to="{name: 'companiesIndex'}" > 
                  <i class="nav-icon fa fa-dashboard"></i> Companies
              </router-link>
            </li>
            <li class="divider"></li>
            <li class="nav-title">Extras</li>
            <li class="nav-item">
              <a class="nav-link" href="/sms">
                <i class="nav-icon fa fa-envelope"></i> SMS</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" target="_blank" href="/poplog">
                <i class="nav-icon fa fa-camera"></i> Pop UP Image</a>
            </li>
           
          </ul>
        </nav>
        <button class="sidebar-minimizer brand-minimizer" type="button"></button>
      </div>
      <main class="main">
        <div class="container-fluid" style="padding:15px;">
            <div class="animated fadeIn">
                <!-- @yield('content') -->
                <div class="container">
                     <router-view name="dashboard"></router-view>
                    <router-view></router-view>
                </div>
            </div>
        </div>
      </main>
    </div>
    <footer class="app-footer">
      <div>
        <a href="/">Face Recognition</a>
        <span>&copy; 2018</span>
      </div>
  
    </footer>
     <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

  </body>
</html>