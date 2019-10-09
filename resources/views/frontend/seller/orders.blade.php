@extends('frontend.layouts.app')
<title>JigiJog | Orders</title>
@section('content')
<style>

    input[type="date"]::-webkit-clear-button {
        display: none;
    }


    input[type="date"]::-webkit-inner-spin-button { 
        display: none;
    }

    input[type="date"]::-webkit-calendar-picker-indicator {
        color: #2c3e50;
    }


    input[type="date"] {
        appearance: none;
        -webkit-appearance: none;
        color: #95a5a6;
        font-family: "Helvetica", arial, sans-serif;
        font-size: 18px;
        border:1px solid #ecf0f1;
        background:#ecf0f1;
        padding:5px;
        display: inline-block !important;
        visibility: visible !important;
    }

    input[type="date"], focus {
        color: #95a5a6;
        box-shadow: none;
        -webkit-box-shadow: none;
        -moz-box-shadow: none;
    }

</style>

    <section class="gry-bg py-4 profile">
        <div class="container">
            <div class="row cols-xs-space cols-sm-space cols-md-space">
                <div class="col-lg-3 d-none d-lg-block">
                    @include('frontend.inc.seller_side_nav')
                </div>

                <div class="col-lg-9">
                    <div class="main-content">
                        <!-- Page title -->
                        <div class="page-title">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h2 class="heading heading-6 text-capitalize strong-600 mb-0">
                                        <!--{{__('Orders')}}-->
                                    </h2>
                                </div>
                                <div class="col-md-6">
                                    <div class="float-md-right">
                                        <ul class="breadcrumb">
                                            <li><a href="{{ route('home') }}">{{__('Home')}}</a></li>
                                            <li><a href="{{ route('dashboard') }}">{{__('Dashboard')}}</a></li>
                                            <li class="active"><a href="{{ route('orders.index') }}">{{__('Orders')}}</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                            <!-- Order history table -->
                            <div class="card no-border mt-4">
                                <div class="card-header py-3">
                                    <h4 class="mb-0 h6">Order History</h4>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm table-hover table-responsive-md">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th clas="text-center">{{__('Order Code')}}</th>
                                                <!--<th>{{__('Num. of Products')}}</th>-->
                                                <th width="100px;">{{__('Customer')}}</th>
                                                <th>{{__('Amount')}}</th>
                                                <th>{{__('Delivery Status')}}</th>
                                                <th>{{__('Payment Status')}}</th>
                                                <th class="text-center">{{__('Actions')}}</th>
                                                <th>{{__('Options')}}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @if (count($orders) > 0)
                                            @foreach ($orders as $key => $order_id)
                                                @php
                                                    $order = \App\Order::find($order_id->id);
                                                @endphp
                                                @if($order != null)
                                                    <tr>
                                                        <td>
                                                            {{ $key+1 }}
                                                        </td>
                                                        <td>
                                                            <a href="#{{ $order->code }}" onclick="show_order_details({{ $order->id }})">{{ $order->code }}</a>
                                                        </td>
                                                        <!--<td>-->
                                                        <!--    {{ count($order->orderDetails->where('seller_id', Auth::user()->id)) }}-->
                                                        <!--</td>-->
                                                        <td>
                                                            @if ($order->user_id != null)
                                                                {{ $order->user->name }}
                                                            @else
                                                                Guest ({{ $order->guest_id }})
                                                            @endif
                                                        </td>
                                                        <td>
                                                        {{ single_price($order->orderDetails->where('seller_id', Auth::user()->id)->sum('price') + $order->orderDetails->where('seller_id', Auth::user()->id)->sum('shipping_cost') - $order->orderDetails->where('seller_id', Auth::user()->id)->sum('coupon_discount') ) }}
                                                        </td>
                                                        <td class="text-center">
                                                            @php
                                                                $stat = $status = $order->orderDetails->where('order_id', $order->id)->where('seller_id', Auth::user()->id)->first()->delivery_status;
                                                            @endphp
                                                            
                                                            @if ($status == 'pending' || $status == 'declined')
                                                                <span class="badge badge-pill badge-danger">{{ ucfirst(str_replace('_', ' ', $status)) }}</span>
                                                            @elseif ($status == 'on_review' || $status == 'on_delivery')
                                                                <span class="badge badge-pill badge-primary">{{ ucfirst(str_replace('_', ' ', $status)) }}</span>
                                                            @else
                                                                <span class="badge badge-pill badge-success">{{ ucfirst(str_replace('_', ' ', $status)) }}</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="badge badge--2 mr-4">
                                                                @if ($order->orderDetails->where('order_id', $order->id)->where('seller_id', Auth::user()->id)->first()->payment_status == 'paid')
                                                                    <i class="bg-green"></i> {{__('Paid')}}
                                                                @elseif($stat == 'declined')
                                                                    <i class="bg-red"></i> {{__('Declined')}}
                                                                @else
                                                                    <i class="bg-red"></i> {{__('Unpaid')}}
                                                                @endif
                                                            </span>
                                                        </td>
                                                        <td>
                                                            @if($order->payment_status == "unpaid")
                                                                @if($order->orderDetails->where('order_id', $order->id)->where('seller_id', Auth::user()->id)->first()->pick_from == null)
                                                                <div class="row">
                                                                    @if($stat != 'declined')                                     
                                                                        <button class="btn-sm btn-primary" onclick="confirm_modal({{ $order->id }})">Confirm Order</button>&nbsp;&nbsp;
                                                                        <form class="" action="{{route('orders.decline_order')}}" method="post">
                                                                            @csrf
                                                                            <input type="text" name="decline" value="{{ $order->id }}" hidden></input>
                                                                            <input type="text" name="seller" value="{{ Auth::user()->id }}" hidden></input>
                                                                            <button type="submit" class="btn-sm btn-danger">Decline Order</button>
                                                                        </form> 
                                                                    @else
                                                                        <span class="text-danger">You declined the order!</span>
                                                                    @endif
                                                                </div>
                                                                @else
                                                                <span>Please prepare (pack) your product immediately for faster transaction. Thank you!</span>
                                                                @endif
                                                            @elseif($order->payment_status == "declined")
                                                                <div class="row">
                                                                    <span class="text-danger">You declined the order!</span>
                                                                </div>
                                                            @else
                                                                <div class="row">
                                                                    <span class="text-success">Transaction Completed.</span>
                                                                </div>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="dropdown">
                                                                <button class="btn" type="button" id="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    <i class="fa fa-ellipsis-v"></i>
                                                                </button>

                                                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="">
                                                                    <button onclick="show_order_details({{ $order->id }})" class="dropdown-item">{{__('Order Details')}}</button>
                                                                    <a href="{{ route('seller.invoice.download', $order->id) }}" class="dropdown-item">{{__('Download Invoice')}}</a>
                                                                </div>
                                                            </div>
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
                                {{ $orders->links() }}
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

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

    <div class="modal fade" id="accept_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-zoom product-modal" id="modal-size" role="document">
            <div class="modal-content position-relative">
                <div class="modal-header">
                    <h5 class="modal-title strong-600 heading-5">{{__('Set date for pick up')}}</h5>
                </div>
                <form class="" action="{{route('orders.add.pickdate')}}" method="post">
                    @csrf
                    <div class="modal-body gry-bg px-3 pt-3">
                        <div class="row">
                            <div class="col-md-12 d-flex justify-content-center">
                                <input type="date" name="from_date" required>
                            </div>
                            <!-- <div class="col-md-6">
                                <label><b>To:</b></label> -->
                                <input type="text" name="to_date" hidden>
                            <!-- </div> -->
                        </div>
                        <hr>
                        <input type="text" name="ids" id="ids" hidden>
                        <input type="text" name="seller_id" value="{{ Auth::user()->id }}" id="ids" hidden>
                        <div class="col-md-12 col-6">
                            <i class="fa fa-info-circle text-info" style="margin-left: 80px;"></i>  
                            <span class="d-block sub-title text-center" style="margin-top: -20px;">Please set the date of pickup.</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Continue</button>&nbsp;
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">

        function confirm_modal($id){
            $('#ids').val($id);
            $('#accept_modal').modal('show');
        }
        
    </script>
@endsection