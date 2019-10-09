@extends('layouts.blank')

@section('content')
    <div class="cls-content-sm panel">
        <div class="PageContainer">        <div class="Section">		  <div class="PageHeader Edge">{{ __('Verify Your Email Address') }}</div>		  			@if (session('resent'))			<div class="alert alert-success" role="alert">				{{ __('A fresh verification link has been sent to your email address.') }}			</div>			@endif		  		  <p>		  If you don't see the email, check your spam or junk folder.		  </p>		  <div class="Section-footer">			{{ __('Before proceeding, please check your email for a verification link.') }}            {{ __('If you did not receive the email') }}, <a href="{{ route('verification.resend') }}" class="btn-link text-bold text-main">{{ __('Click here to request another') }}</a>.		  </div>		</div>	  </div>	  		<div class="row">			<div class="col text-center">				<a class="w3-right w3-btn" href="{{ url('/') }}" style="">Return Home ❯</a>			</div>		</div>
    </div>
@endsection
