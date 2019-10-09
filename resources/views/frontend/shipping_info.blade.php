@extends('frontend.layouts.app')

@section('content')

    <div id="page-content">
        <section class="slice-xs sct-color-2 border-bottom">
            <div class="container container-sm">
                <div class="row cols-delimited">
                    <div class="col-4">
                        <div class="icon-block icon-block--style-1-v5 text-center">
                            <div class="block-icon mb-0">
                                <i class="icon-hotel-restaurant-105"></i>
                            </div>
                            <div class="block-content d-none d-md-block">
                                <h3 class="heading heading-sm strong-300 c-gray-light text-capitalize">1. {{__('My Cart')}}</h3>
                            </div>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="icon-block icon-block--style-1-v5 text-center active">
                            <div class="block-icon mb-0">
                                <i class="icon-finance-067"></i>
                            </div>
                            <div class="block-content d-none d-md-block">
                                <h3 class="heading heading-sm strong-300 c-gray-light text-capitalize">2. {{__('Shipping info')}}</h3>
                            </div>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="icon-block icon-block--style-1-v5 text-center">
                            <div class="block-icon c-gray-light mb-0">
                                <i class="icon-finance-059"></i>
                            </div>
                            <div class="block-content d-none d-md-block">
                                <h3 class="heading heading-sm strong-300 c-gray-light text-capitalize">3. {{__('Payment')}}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-4 gry-bg">
            <div class="container">
                <div class="row cols-xs-space cols-sm-space cols-md-space">
                    <div class="col-lg-8">
                        <form class="form-default" data-toggle="validator" action="{{ route('checkout.store_shipping_infostore') }}" role="form" method="POST">
                            @csrf
                            <div class="card">
                                @if(Auth::check())
                                    @php
                                        $user = Auth::user();
                                    @endphp
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label">{{__('Name')}}</label>
                                                    <input type="text" class="form-control" name="name" value="{{ $user->name }}" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label">{{__('Email')}}</label>
                                                    <input type="email" class="form-control" name="email" value="{{ $user->email }}" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label">{{__('Address')}}</label>
                                                    <input type="text" id="address" class="form-control" name="address" value="{{ $user->address }}" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- <input type="text" class="form-control" id="address" name="address" disabled value="{{ $user->address }}" hidden> -->


                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group has-feedback">
                                                    <label class="control-label">{{__('Phone')}}</label>
                                                    <input type="text" class="form-control" value="{{ $user->phone }}" name="phone" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">{{__('Province')}}</label>
                                                    <select class="form-control selectpicker" data-live-search="true" id="province" name="country" required>
                                                        @foreach (\App\Province::all() as $key => $province)
                                                            <option value="{{ $province->provCode }}" @if ($province->provCode == $user->province) selected @endif>{{ $province->provDesc }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group has-feedback">
                                                    <label class="control-label">{{__('City')}}</label>
                                                    <!-- <input type="text" class="form-control" value="{{ $user->city }}" name="city" required> -->
                                                    <select class="form-control selectpicker" data-live-search="true" id="city" name="city" required>
                                                        @foreach (\App\City::all() as $key => $city)
                                                            @if($user->province == $city->provCode)
                                                                <option value="{{ $city->citymunCode }}" @if ($city->citymunCode == $user->city) selected @endif>{{ $city->citymunDesc }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group has-feedback">
                                                    <label class="control-label">{{__('Barangay')}}</label>
                                                    <!-- <input type="text" class="form-control" value="{{ $user->postal_code }}" name="postal_code" required> -->
                                                    <select class="form-control selectpicker" data-live-search="true" id="barangay" name="city" required>
                                                        @foreach (\App\Barangay::all() as $key => $brgy)
                                                            @if($user->city == $brgy->citymunCode)
                                                                <option value="{{ $brgy->brgyCode }}" @if ($brgy->brgyCode == $user->barangay) selected @endif>{{ $brgy->brgyDesc }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group has-feedback">
                                                    <label class="control-label">{{__('Building, Street, or etc.')}}</label>
                                                    <input type="text" class="form-control" value="{{ $user->landmark }}" id="landmark" name="landmark" required>
                                                </div>
                                            </div>
                                        </div>

                                        <input type="hidden" name="checkout_type" value="logged">
                                    </div>
                                @else
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label">{{__('Name')}}</label>
                                                    <input type="text" class="form-control" name="name" placeholder="{{__('Name')}}" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label">{{__('Email')}}</label>
                                                    <input type="text" class="form-control" name="email" placeholder="{{__('Email')}}" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="control-label">{{__('Address')}}</label>
                                                    <input type="text" class="form-control" name="address" placeholder="{{__('Address')}}" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">{{__('Select your country')}}</label>
                                                    <select class="form-control custome-control" data-live-search="true" name="country">
                                                        @foreach (\App\Country::all() as $key => $country)
                                                            <option value="{{ $country->name }}">{{ $country->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group has-feedback">
                                                    <label class="control-label">{{__('City')}}</label>
                                                    <input type="text" class="form-control" placeholder="{{__('City')}}" name="city" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group has-feedback">
                                                    <label class="control-label">{{__('Postal code')}}</label>
                                                    <input type="text" class="form-control" placeholder="{{__('Postal code')}}" name="postal_code" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group has-feedback">
                                                    <label class="control-label">{{__('Phone')}}</label>
                                                    <input type="text" class="form-control" placeholder="{{__('Phone')}}" name="phone" required>
                                                </div>
                                            </div>
                                        </div>

                                        <input type="hidden" name="checkout_type" value="guest">
                                    </div>
                                @endif
                            </div>

                            <div class="row align-items-center pt-4">
                                <div class="col-6">
                                    <a href="{{ route('home') }}" class="link link--style-3">
                                        <i class="ion-android-arrow-back"></i>
                                        {{__('Return to shop')}}
                                    </a>
                                </div>
                                <div class="col-6 text-right">
                                    <button type="submit" id="submitButton" class="btn btn-styled btn-base-1">{{__('Continue to Payment')}}</a>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="col-lg-4 ml-lg-auto">
                        @include('frontend.partials.cart_summary_final')
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection
