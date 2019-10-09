@extends('layouts.blank')

@section('content')
    <div class="cls-content-sm panel">
        		
		<div class="PageContainer">
        <div class="Section">
		  <div class="PageHeader Edge">{{ __('Check your email.') }}</div>

		  <p>
		  We've sent a new reset link to your email. Click the link in the email to reset your password.
		  </p>
		  <p>
		  If you don't see the email, check your spam or junk folder.
		  </p>
		  <div class="Section-footer">
			<a href="{{ url('/password/reset') }}">
			  I didn't receive the email.
			</a>
		  </div>
		</div>

	  </div>
  
		<div class="row">
			<div class="col text-center">
				<a class="w3-right w3-btn" href="{{ url('/') }}" style="">Return Home ❯</a>
			</div>
		</div>
    </div>
@endsection




 
