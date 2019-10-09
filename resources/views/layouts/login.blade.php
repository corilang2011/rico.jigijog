<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link name="favicon" type="image/x-icon" href="{{asset('img/favicon.png')}}" rel="shortcut icon" />

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!--Bootstrap Stylesheet [ REQUIRED ]-->
    <link href="{{ asset('css/bootstrap.min.css')}}" rel="stylesheet">

    <!--Font Awesome [ OPTIONAL ]-->
    <link href="{{ asset('plugins/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">

    <!--active-shop Stylesheet [ REQUIRED ]-->
    <link href="{{ asset('css/active-shop.min.css')}}" rel="stylesheet">

    <!--active-shop Premium Icon [ DEMONSTRATION ]-->
    <link href="{{ asset('css/demo/active-shop-demo-icons.min.css')}}" rel="stylesheet">

    <!--Demo [ DEMONSTRATION ]-->
    <link href="{{ asset('css/demo/active-shop-demo.min.css') }}" rel="stylesheet">

    <!--Theme [ DEMONSTRATION ]-->
    <link href="{{ asset('css/themes/type-c/theme-navy.min.css') }}" rel="stylesheet">

    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">	
	<link href="{{ asset('css/reset.css') }}" rel="stylesheet">

</head>

<body class="ResponsiveLayout ">

@php 
$current_url = url()->current();
$base_url = url('/');

if($current_url==$base_url . '/password/email'){
	
    $slug_title = 'Check your email to reset your password';
	
}elseif($current_url==$base_url . '/password/reset'){
	
    $slug_title = 'Password Reset';
	
}elseif($current_url== $base_url . '/email/verify'){
	
    $slug_title = 'Verify Your Email Address';
	
}elseif($current_url== $base_url . '/login'){
	
    $slug_title = 'Admin Login';
	
}else{
	
    $slug_title = '';
}  
@endphp 

    <!--<div class="TopNav">-->
    <!--  <div class="TopNav--container u-cf">-->
        
        
    <!--    <div class="Icon--logo u-pullLeft">-->
    <!--      <a href="{{ url('/') }}"><img alt="jigijog" src="{{asset('frontend/images/logo/jigifooter.png') }}"></a>-->
    <!--    </div>-->
    <!--    <div class="TopNav-title u-pullLeft"><i class="fa fa-fw fa-chevron-right"></i> {{ $slug_title }} </div>-->
          
    <!--  </div>-->
    <!--</div>-->
    @php
        $generalsetting = \App\GeneralSetting::first();
    @endphp
    <div id="container" class="blank-index">
        <div class="cls-content" style="margin-top: 100px;">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <div class="panel">
                            <div class="panel-body pad-no">
                                @yield('content')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--JAVASCRIPT-->
    <!--=================================================-->

    <!--jQuery [ REQUIRED ]-->
    <script src=" {{asset('js/jquery.min.js') }}"></script>


    <!--BootstrapJS [ RECOMMENDED ]-->
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>


    <!--active-shop [ RECOMMENDED ]-->
    <script src="{{ asset('js/active-shop.min.js') }}"></script>

    <!--Alerts [ SAMPLE ]-->
    <script src="{{asset('js/demo/ui-alerts.js') }}"></script>

    @yield('script')

</body>
</html>
