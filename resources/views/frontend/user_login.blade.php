@extends('frontend.layouts.app')
<title>JigiJog | Login</title>
@section('content')
    <section class="gry-bg py-5">
        <div class="profile">
            <div class="container">
                <div class="row">
                    <div class="col-xl-10 offset-xl-1">
                        <div class="card">
                            <div class="text-center px-35 pt-5">
                                <h3 class="heading heading-4 strong-500">
                                    {{__('Login to your account.')}}
                                </h3>
                            </div>
                            <div class="px-5 py-3 py-lg-5">
                                <div class="row align-items-center">
                                    <div class="col-12 col-lg">
                                        <form class="pad-hor" method="POST" role="form" action="{{ route('login') }}">
                                    @csrf
									<!--
                                    <div class="form-group">
                                        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" 
                                        name="email" value="{{ old('email') }}" required autofocus placeholder="Email">
                                    </div>
									-->
									<div class="form-group">
                                        <input id="email" type="text" class="form-control" name="email" value="{{ old('email') }}" required autofocus placeholder="Email or Phone">
                                        
                                    </div>
                                    <div class="form-group">
                                        <input id="password" type="password" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="password" required placeholder="Password">
                                        
                                        @if ($errors->has('email'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ __('Incorrect Email or Password') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="checkbox pad-btm text-left">
                                                <input id="demo-form-checkbox" class="magic-checkbox" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                                <label for="demo-form-checkbox">
                                                    {{ __('Remember Me') }}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="checkbox pad-btm text-right">
                                                <a href="{{ route('password.request') }}" class="btn-link">{{__('Forgot password')}} ?</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col text-center">
                                            <button type="submit" class="btn btn-styled btn-base-1 btn-md w-100">{{ __('Login') }}</button>
                                        </div>
                                    </div>
                                </form>
                                    </div>
                                    <div class="col-lg-1 text-center align-self-stretch">
                                        <div class="border-right h-100 mx-auto" style="width:1px;"></div>
                                    </div>
                                    <div class="col-12 col-lg">
                                        @if(\App\BusinessSetting::where('type', 'google_login')->first()->value == 1)
                                            <a href="{{ route('social.login', ['provider' => 'google']) }}" class="btn btn-styled btn-block btn-google btn-icon--2 btn-icon-left px-4 my-4">
                                                <i class="icon fa fa-google"></i> {{__('Login with Google')}}
                                            </a>
                                        @endif
                                        @if (\App\BusinessSetting::where('type', 'facebook_login')->first()->value == 1)
                                            <a href="{{ route('social.login', ['provider' => 'facebook']) }}" class="btn btn-styled btn-block btn-facebook btn-icon--2 btn-icon-left px-4 my-4">
                                                <i class="icon fa fa-facebook"></i> {{__('Login with Facebook')}}
                                            </a>
                                        @endif
                                        @if (\App\BusinessSetting::where('type', 'twitter_login')->first()->value == 1)
                                        <a href="{{ route('social.login', ['provider' => 'twitter']) }}" class="btn btn-styled btn-block btn-twitter btn-icon--2 btn-icon-left px-4 my-4">
                                            <i class="icon fa fa-twitter"></i> {{__('Login with Twitter')}}
                                        </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="text-center px-35 pb-3">
                                <p class="text-md">
                                    {{__('Need an account?')}} <a href="{{ route('user.registration') }}" class="strong-600">{{__('Register Now')}}</a>
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- <div class="bg-white p-4 mx-auto mt-4">
                        <div class="">
                            <table class="table table-responsive table-bordered mb-0">
                                <tbody>
                                    <tr>
                                        <td>{{__('Seller Account')}}</td>
                                        <td><button class="btn btn-info" onclick="autoFillSeller()">Copy credentials</button></td>
                                    </tr>
                                    <tr>
                                        <td>{{__('Customer Account')}}</td>
                                        <td><button class="btn btn-info" onclick="autoFillCustomer()">Copy credentials</button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div> --}}

                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script type="text/javascript">
        function autoFillSeller(){
            $('#email').val('seller@example.com');
            $('#password').val('123456');
        }
        function autoFillCustomer(){
            $('#email').val('customer@example.com');
            $('#password').val('123456');
        }
    </script>
@endsection
