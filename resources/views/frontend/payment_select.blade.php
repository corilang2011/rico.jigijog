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
                        <div class="icon-block icon-block--style-1-v5 text-center">
                            <div class="block-icon mb-0">
                                <i class="icon-finance-067"></i>
                            </div>
                            <div class="block-content d-none d-md-block">
                                <h3 class="heading heading-sm strong-300 c-gray-light text-capitalize">2. {{__('Shipping info')}}</h3>
                            </div>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="icon-block icon-block--style-1-v5 text-center active">
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




        <section class="py-3 gry-bg">
            <div class="container">
                <div class="row cols-xs-space cols-sm-space cols-md-space">
                    <div class="col-lg-8">
                        @if (Auth::check() && \App\BusinessSetting::where('type', 'coupon_system')->first()->value == 1)
                            <div class="card">
                                <button type="submit" class="btn btn-base-1" onclick="coupon_code_modal()">{{__('Apply Coupon code')}}</button>
                            </div>
                        @endif
                        <form action="{{ route('payment.checkout') }}" class="form-default" data-toggle="validator" role="form" method="POST">
                            @csrf
                            <div class="card">
                                <div class="card-title px-4">
                                    <h3 class="heading heading-5 strong-500">
                                        {{__('Select a payment option')}}
                                    </h3>
                                    <a href="{{ route('home') }}" class="link link--style-3" style="float: right;">
                                        <i class="ion-android-arrow-back"></i>
                                        {{__(' < Return to shop')}}
                                    </a>
                                </div>
                                <div class="card-body text-center">
                                    <ul class="inline-links">
                                        <li>
                                            <label class="payment_option">
                                                <input type="radio" id="" name="payment_option" value="gcash_wallet">
                                                <span id="h_gcash">
                                                    <img src="{{ asset('frontend/images/icons/cards/gcash.png')}}" class="img-fluid">
                                                    <p class="mt-4">GCash e-Wallet</p>
                                                </span>
                                            </label>
                                        </li>
                                        <li>
                                            <label class="payment_option">
                                                <input type="radio" id="" name="payment_option" value="jigi_wallet">
                                                <span id="h_wallet">
                                                    <img src="{{ asset('frontend/images/icons/cards/WALLET.png')}}" class="img-fluid">
                                                    <p class="mt-4">Jigijog Wallet</p>
                                                </span>
                                            </label>
                                        </li>
                                        @if(\App\BusinessSetting::where('type', 'cash_payment')->first()->value == 1)
                                            <li>
                                                <label class="payment_option">
                                                    <input type="radio" id="" name="payment_option" value="cash_on_delivery">
                                                    <span id="s_cod">
                                                        <img src="{{ asset('frontend/images/icons/cards/cod.png')}}" class="img-fluid">
                                                        <p class="mt-4">Cash on Delivery</p>
                                                    </span>
                                                </label>
                                            </li>
                                        @endif
                                        <div id="cod_inf" class="d-none text-left" style="margin-left: 15%;">
                                            <div class="row no-gutters" style="margin-top: 40px;">
                                                <div  class="col-2">
                                                    <img src="{{ asset('frontend/images/icons/buyer-protection.png') }}" width="60" class="">
                                                </div>
                                                <div class="col-10">
                                                    <div class="heading-6 strong-700 text-info" >Buyer protection</div>
                                                    <ul class="list-symbol--1 mb-0 pl-0 mt-2" style="list-style: none;">
                                                        <li class="mt-2"><strong >Full Refund</strong> if you don't receive your order.</li>
                                                        <li class="mt-2"><strong>Full or Partial Refund</strong>, if the item is not as described.</li>
                                                        <li class="mt-2">Sellers will <strong>NEVER</strong> ask you to send money.</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="wallet_inf" class="d-none text-left" style="margin-left: 25%;">
                                            <div class="row no-gutters" style="margin-top: 40px;">
                                                @if (Auth::check())
                                                    <div class="text-center mt-4">
                                                        @php
                                                            $wallet = Auth::user()->balance;
                                                            $user_id = auth()->user()->id;
                                                            $id_status = "Approved";
                                                            $balance = DB::table('referrals')->where([['id_status','=',$id_status], ['user_id','LIKE',"%{$user_id}%"]])->sum('reward_points');
                                                            $final_total = $balance + $wallet;
                                                        @endphp
                                                        <div class="h5">Your wallet balance : <strong>{{ single_price($final_total) }}</strong></div>
                                                        
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div id="gcash_inf" class="d-none text-left" style="margin-left: 37%;">
                                            <div class="row no-gutters" style="margin-top: 70px;">
                                                <div class="h5"><strong>Coming Soon...</strong></div>
                                            </div>
                                        </div>
                                    </ul>
                                </div>
                            </div>

                            <div class="row align-items-center pt-4">
                                <div class="col-6">
                                    <a href="{{ route('home') }}" class="link link--style-3">
                                        <!--<i class="ion-android-arrow-back"></i>-->
                                        <!--{{__('Return to shop')}}-->
                                    </a>
                                </div>
                                <div id="cod_pay" class="col-6 text-right d-none">
                                    <button type="submit" class="btn btn-styled btn-base-1" onclick="this.disabled=true;this.form.submit();" >
                                        {{__('Complete order')}}
                                    </button>
                                </div>
                                <div id="wallet_pay" class="col-6 text-right d-none">
                                    @if($final_total < $total)
                                        <a class="btn btn-styled btn-base-1 text-white" disabled>Not Enough Balance</a>
                                    @endif
                                    @if($final_total >= $total)
                                        <button onclick="use_wallet()" class="btn btn-styled btn-base-1" >Complete Order</button>
                                    @endif
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

        <div class="modal fade" id="coupon_code_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-zoom product-modal" id="modal-size" role="document">
                <div class="modal-content position-relative">
                    <div class="modal-header">
                        <h5 class="modal-title strong-600 heading-5">{{__('Apply Coupon Code')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form class="" action="{{ route('checkout.apply_coupon_code') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body gry-bg px-3 pt-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <input type="text" class="form-control mb-3" name="code" placeholder="Code" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('cancel')}}</button>
                            <button type="submit" class="btn btn-base-1">{{__('Apply')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        function use_wallet(){
            $('input[name=payment_option]').val('wallet');
            $('#checkout-form').submit();
        }

        function coupon_code_modal(){
            $('#coupon_code_modal').modal('show');
        }
        $(document).ready(function(){
            $("#h_gcash").click(function(){
                $("#gcash_inf").removeClass('d-none');
                $("#gcash_inf").show();
                $("#cod_inf").hide();
                $("#wallet_inf").hide();
                $("#wallet_pay").addClass('d-none');
                $("#cod_pay").addClass('d-none');
                $("#wallet_pay").hide();
                $("#cod_pay").hide();
            });
            $("#h_wallet").click(function(){
                $("#wallet_inf").removeClass('d-none');
                $("#wallet_inf").show();
                $("#cod_inf").hide();
                $("#gcash_inf").hide();
                $("#wallet_pay").removeClass('d-none');
                $("#cod_pay").hide();
                $("#wallet_pay").show();
            });
            $("#s_cod").click(function(){
                $("#cod_inf").removeClass('d-none');
                $("#gcash_inf").hide();
                $("#wallet_inf").hide();
                $("#cod_pay").removeClass('d-none');
                $("#cod_pay").show();
                $("#wallet_pay").hide();
                $("#cod_inf").show();
            });
        });
    </script>
@endsection
