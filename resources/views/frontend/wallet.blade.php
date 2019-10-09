@extends('frontend.layouts.app')
<title>JigiJog | Wallet</title>
@section('content')
    <section class="gry-bg py-4 profile">
        <div class="container">
            <style type="text/css">
                .plus-widget i {
                    background: #ddd;
                    height: 69px;
                    width: 69px;
                    line-height: 69px;
                    font-size: 36px;
                }
            </style>
            <div class="row cols-xs-space cols-sm-space cols-md-space">
                <div class="col-lg-3 d-none d-lg-block">
                    @if(Auth::user()->user_type == 'seller')
                        @include('frontend.inc.seller_side_nav')
                    @elseif(Auth::user()->user_type == 'customer')
                        @include('frontend.inc.customer_side_nav')
                    @endif
                </div>

                <div class="col-lg-9">
                    <div class="main-content">
                        <!-- Page title -->
                        <div class="page-title">
                            <div class="row align-items-center">
                                <div class="col-md-6 col-12 d-flex align-items-center">
                                    <h2 class="heading heading-6 text-capitalize strong-600 mb-0">
                                        <!--{{__('My Wallet History')}}-->
                                    </h2>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="float-md-right">
                                        <ul class="breadcrumb">
                                            <li><a href="{{ route('home') }}">{{__('Home')}}</a></li>
                                            <li><a href="{{ route('dashboard') }}">{{__('Dashboard')}}</a></li>
                                            <li class="active"><a href="{{ route('wallet.index') }}">{{__('My Wallet')}}</a></li>
                                        </ul>
                                        <br>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        @if(Auth::user()->id == 186)
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="dashboard-widget text-center plus-widget mt-4 c-pointer" onclick="show_wallet_modal()">
                                        <i class="la la-plus"></i>
                                        <span class="d-block title heading-6 strong-400 c-base-1">{{ __('Request Withdrawal') }}</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="dashboard-widget text-center green-widget text-white mt-4 c-pointer">
                                        <i class="fa fa-dollar"></i>
                                        <span class="d-block title heading-3 strong-400">{{ single_price(Auth::user()->balance) }}</span>
                                        <span class="d-block sub-title">{{ __('Wallet Balance') }}</span>
                                        
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="dashboard-widget text-center plus-widget mt-4 c-pointer"onclick="show_send_wallet_modal()">
                                        <i class="la la-plus"></i>
                                        <span class="d-block title heading-6 strong-400 c-base-1">{{ __('Send Funds to seller') }}</span>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="row">
                                <div class="col-md-4 offset-md-2">
                                    <div class="dashboard-widget text-center green-widget text-white mt-4 c-pointer">
                                        <i class="fa fa-dollar"></i>
                                        <span class="d-block title heading-3 strong-400">{{ single_price(Auth::user()->balance) }}</span>
                                        <span class="d-block sub-title">{{ __('Wallet Balance') }}</span>
                                        
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="dashboard-widget text-center plus-widget mt-4 c-pointer" onclick="show_wallet_modal()">
                                        <i class="la la-plus"></i>
                                        <span class="d-block title heading-6 strong-400 c-base-1">{{ __('Request Withdrawal') }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="card no-border mt-4">
                            <div class="card-header py-3">
                                <h4 class="mb-0 h6">Wallet history</h4>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm table-hover table-responsive-md">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Name') }}</th>
                                            <th>{{ __('Date') }}</th>
                                            <th>{{__('Amount')}}</th>
                                            <th>{{__('Status')}}</th>
                                            <th>{{__('Option')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($wallets) > 0)
                                            @foreach ($wallets as $key=> $wallet)
                                                @if($wallet->amount > 0)
                                                    <tr>
                                                        @if($wallet->title == "Referral Bonus")
                                                            <td style="color: #72bf40">{{ $wallet->title }}</td>
                                                        @elseif($wallet->title == "Products Sold")
                                                            <td style="color: #20c997"><b>{{__('Product Sold')}}</b></td>
                                                        @elseif($wallet->title == "Send Funds To Seller")
                                                            @php
                                                                $send_id = DB::table('users')->where('id','=', $wallet->send_to_user_id)->get();
                                                                $send_id2 = DB::table('users')->where('id','=', $wallet->user_id)->get();
                                                            @endphp
                                                            @if($wallet->user_id == Auth::user()->id)
                                                                @foreach($send_id as $send)<td style="color: #dc3545">Funds sent to {{$send->name}}</td>@endforeach
                                                            @else
                                                                @foreach($send_id2 as $send2)<td style="color: #72bf40"><b>Funds from {{$send2->name}}</b></td>@endforeach
                                                            @endif
                                                        @else
                                                            <td style="color: #dc3545">{{$wallet->title}}</td>
                                                        @endif
                                                        <td>{{ date('m-d-Y h:i A', strtotime($wallet->created_at)) }}</td>
                                                        @if($wallet->payment_details == "Outgoing Fund Transfer")
                                                            <td style="color: #dc3545">- {{ single_price($wallet->amount) }}</td>
                                                        @elseif($wallet->payment_details == "Processing Fund Transfer")
                                                            <td style="color: #ffc107"> {{ single_price($wallet->amount) }} </td>
                                                        @elseif($wallet->payment_details == "Incoming Fund Transfer")
                                                            <td style="color: #72bf40">+ {{ single_price($wallet->amount) }}</td>
                                                        @elseif($wallet->title == "Send Funds To Seller")
                                                            @if($wallet->user_id != Auth::user()->id) 
                                                                <td style="color: #72bf40">+ {{ single_price($wallet->amount) }}</td>
                                                            @endif
                                                            @if($wallet->user_id == Auth::user()->id) 
                                                                <td style="color: #dc3545">- {{ single_price($wallet->amount) }}</td>
                                                            @endif
                                                        @endif
                                                        @if($wallet ->title == "Referral Bonus" || $wallet ->title == "Products Sold" )
                                                            <td style="color: #72bf40"><i class="fa fa-arrow-right" aria-hidden="true"></i>{{__(' Added into your Wallet')}}</td>
                                                        @elseif($wallet ->payment_details == "Outgoing Fund Transfer")
                                                            <td style="color: #dc3545"><i class="fa fa-arrow-left" aria-hidden="true"></i>{{__('Funds Transfered successfully')}}</td>
                                                        @elseif($wallet->title == "Send Funds To Seller")
                                                            @if($wallet->user_id != Auth::user()->id) 
                                                                <td style="color: #72bf40"><i class="fa fa-arrow-left" aria-hidden="true"></i>{{__('Added into your Wallet')}}</td>
                                                            @endif
                                                            @if($wallet->user_id == Auth::user()->id) 
                                                                <td style="color: #dc3545"><i class="fa fa-arrow-left" aria-hidden="true"></i>{{__('Funds Transfered successfully')}}</td>
                                                            @endif
                                                        @else
                                                            <td style="color: #ffc107"><i class="fa fa-arrow-up" aria-hidden="true"></i>{{__('Processing Withdrawal Request')}}</td>
                                                        @endif
                                                        <td>
                                                            <a onclick="show_wallet_details({{ $wallet->id }})" class="btn btn-styled btn-link py-1 px-0 icon-anim text-underline--none text-primary">{{__('View Details')}}
                                                             <i class="la la-angle-right text-sm"></i></a>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @else
                                            <tr>
                                                <td class="text-center pt-5 h4" colspan="100%">
                                                    <i class="la la-meh-o d-block heading-1 alpha-5"></i>
                                                <span class="d-block">{{ __('No history found.') }}</span>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="pagination-wrapper py-4">
                            <ul class="pagination justify-content-end">
                                {{ $wallets->links() }}
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="wallet_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-zoom product-modal" id="modal-size" role="document" style="width: 700px;">
            <div class="modal-content position-relative">
                <div class="modal-header">
                    <h5 class="modal-title heading-6">{{__('Request Withdrawal')}} </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="" action="{{ route('wallet.recharge') }}" method="post">
                    @csrf
                    <div class="modal-body gry-bg px-3 pt-3">
                        <div class="row">
                            <div class="col-md-4 pt-2">
                                <label>{{__('Amount')}} <i style="color: red;">*</i></label>
                            </div>
                            <div class="col-md-8">
                                @php
                                    $max = Auth::user()->balance;
                                @endphp
                                <input id="bg-default" type="number" class="form-control mb-3" name="amount" min="1" max="{{ $max }}" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 pt-2">
                                <label>{{__('Payment Method')}}<i style="color: red;">*</i></label>
                            </div>
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <input type="text" class="form-control mb-3" name="payment_option" value="Bank" readonly="">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="bank row">
                            <div class="col-md-4 pt-2">
                                <label>{{__('Bank Name')}}<i style="color: red;">*</i></label>
                            </div>
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <input type="text" class="form-control mb-3" name="ba_name">
                                </div>
                            </div>
                            <div class="col-md-4 pt-2">
                                <label>{{__('Account Number')}}<i style="color: red;">*</i></label>
                            </div>
                            <div class="col-md-8">
                                <div class="mb-4">
                                    <input type="text" class="form-control mb-3" name="ca_number">
                                </div>
                            </div>
                            <div class="col-md-4 pt-2" >
                                <label>{{__('Account Name ')}}<i style="color: red;">*</i></label>
                            </div>
                            <div class="col-md-8">
                                <div class="mb-4">
                                    <input type="text" class="form-control mb-3" name="ca_name">
                                </div>
                            </div>
                        </div>
                        <input type="number" class="val" value="{{ $max }}" hidden>
                    </div>
                    @php
                        $user_id = auth()->user()->id;
                        $title = "Wallet Withdrawal";
                        $date = \Carbon\Carbon::today()->subDays(6);
                        $c_wallets = DB::table('wallets')->select('id')->where([['user_id','=', $user_id ],['created_at', '>=', $date], ['title', '=', $title]])->first();
                    @endphp
                    @if($c_wallets == NULL)
                        @if(Auth::user()->balance > 0)
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('cancel')}}</button>
                                <button type="submit" class="btn btn-base-1">{{__('Confirm')}}</button>
                            </div>
                        @else
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('cancel')}}</button>
                                <button type="submit" class="btn btn-primary" disabled data-dismiss="modal">{{__('Not Enough Balance')}}</button>
                            </div>
                        @endif   
                    @else
                        @foreach($c_wallets as $h_wallet)
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('cancel')}}</button>
                                <button type="button" class="btn btn-warning" disabled>{{__('One Request Per Week')}}</button>
                            </div>
                        @endforeach
                    @endif   
                </form>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="send_wallet_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-zoom product-modal" id="modal-size" role="document" style="width: 700px;">
            <div class="modal-content position-relative">
                <div class="modal-header">
                    @php
                        $max = Auth::user()->balance;
                        $user_type = "seller";
                        $sellers = DB::table('users')->where('user_type','=', $user_type)->where('id','!=', Auth::user()->id)->get();
                    @endphp
                    <h5 class="modal-title heading-6">{{__('Maximum amount to send: ')}} {{ single_price($max) }} </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="" action="{{ route('wallet.recharge') }}" method="post">
                    @csrf
                    <div class="modal-body gry-bg px-3 pt-3">
                        <div class="row">
                            <div class="col-md-4 pt-2">
                                <label>{{__('Amount')}} <i style="color: red;">*</i></label>
                            </div>
                            <div class="col-md-8">
                                <input id="bg-default-2" type="number" class="form-control mb-3" name="send_amount" min="0" max="{{ $max }}" step=".01" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 pt-2">
                                <label>{{__('Seller')}}<i style="color: red;">*</i></label>
                            </div>
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <select class="form-control selectpicker" data-minimum-results-for-search="Infinity" name="seller" required>
                                        @foreach($sellers as $seller)
                                            @php
                                                $shops = DB::table('shops')->where('user_id','=', $seller->id)->get();
                                            @endphp
                                            @foreach($shops as $shop)
                                                <option value="#" disabled="" selected=""></option>
                                                <option value="{{$seller->id}}">{{$seller->name}} ( {{ $shop->name }} )</option>
                                            @endforeach
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Provide a detailed description <span class="text-danger">*</span></label>
                            <textarea class="form-control editor" name="details" placeholder="Type your reply" data-buttons="bold,underline,italic,|,ul,ol,|,paragraph,|,undo,redo" required=""></textarea>
                        </div>
                        <hr>
                        <input type="number" class="val" value="{{ $max }}" hidden>
                    </div>
                    @if(Auth::user()->balance > 0)
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('cancel')}}</button>
                            <button type="submit" class="btn btn-base-1">{{__('Confirm')}}</button>
                        </div>
                    @else
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('cancel')}}</button>
                            <button type="submit" class="btn btn-primary" disabled data-dismiss="modal">{{__('Not Enough Balance')}}</button>
                        </div>
                    @endif  
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="order_details" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-zoom product-modal" id="modal-size" role="document">
            <div class="modal-content position-relative">
                <div class="c-preloader">
                    <i class="fa fa-spin fa-spinner"></i>
                </div>
                <div id="order-details-modal-body">

                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script type="text/javascript">
        function show_wallet_modal(){
            $('#wallet_modal').modal('show');
        }
        function show_send_wallet_modal(){
            $('#send_wallet_modal').modal('show');
        }
        $(function () {
          $("#bg-default").keydown(function () {
            var max = $('.val').val();
            if (!$(this).val() || (parseInt($(this).val()) <= max && parseInt($(this).val()) >= 0))
            $(this).data("old", $(this).val());
          });
          $("#bg-default").keyup(function () {
            var max = $('.val').val();
            if (!$(this).val() || (parseInt($(this).val()) <= max && parseInt($(this).val()) >= 0))
              ;
            else
              $(this).val($(this).data("old"));
          });
        });
        $(function () {
          $("#bg-default-2").keydown(function () {
            var max = $('.val').val();
            if (!$(this).val() || (parseInt($(this).val()) <= max && parseInt($(this).val()) >= 0))
            $(this).data("old", $(this).val());
          });
          $("#bg-default-2").keyup(function () {
            var max = $('.val').val();
            if (!$(this).val() || (parseInt($(this).val()) <= max && parseInt($(this).val()) >= 0))
              ;
            else
              $(this).val($(this).data("old"));
          });
        });
    </script>

@endsection
