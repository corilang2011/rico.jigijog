@extends('layouts.app')

@section('content')

<!-- Basic Data Tables -->
<!--===================================================-->
<div class="row">
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">{{__('Total Amount of Sales')}}</h3>
            </div>
            <div class="panel-body text-center dash-widget pad-no">
                <div class="pad-ver mar-top text-main">
                    <i class="demo-pli-wallet-2 icon-4x"></i>
                </div>
                @php
                    $status = "paid";
                    $prod_price = DB::table('order_details')->where('payment_status','=',$status)->sum('price');
                    $prod_ship_cost = DB::table('order_details')->where('payment_status','=',$status)->sum('shipping_cost');
                    $prod_discount = DB::table('orders')->where('payment_status','=',$status)->sum('coupon_discount');
                @endphp
                <div>
                    <p class="text-2x text-main bg-primary pad-ver">
                        Products (Original price sold) : <strong style="margin-right: 50px;">{{single_price($prod_price)}}</strong> 
                            Total Shipping Fees: <strong style="margin-right: 50px;">{{single_price($prod_ship_cost)}}</strong>
                                Discounts Given : <strong style="margin-right: 50px;">{{single_price( $prod_discount )}}</strong>
                    </p>
                </div>
                <div>
                    <p class="text-2x text-main bg-primary pad-ver">
                        Overall Total : <strong style="margin-right: 50px;">{{single_price($prod_price + $prod_ship_cost - $prod_discount) }}</strong> 
                    </p>
                </div>
            </div>
            <br>
        </div>
    </div>
</div>
<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title">{{__('orders')}}</h3>
    </div>
    <div class="panel-body">
        <table class="table table-striped table-bordered demo-dt-basic" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Order Code</th>
                    <th>Num. of Products</th>
                    <th>Customer</th>
                    <th>Amount</th>
                    <th>Delivery Status</th>
                    <th>Payment Status</th>
                    <th width="10%">{{__('options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $key => $order)
                    <!--@if(count($order->orderDetails) > 0)-->
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $order->code }}</td>
                        <td class="text-center">{{ count($order->orderDetails) }}</td>
                        <td>
                            @if ($order->user_id != null)
                                {{ $order->user->name }}
                            @else
                                Guest ({{ $order->guest_id }})
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="label label-table label-default" style="width: 100px;" >{{ single_price($order->grand_total) }}</div>
                        </td>
                        <td class="text-center">
                            @php
                                $status = 'Delivered';
                                foreach ($order->orderDetails as $key => $orderDetail) {
                                    if($orderDetail->delivery_status != 'delivered'){
                                        $status = 'Pending';
                                    }
                                }
                            @endphp
                            @if($status == 'Delivered')
                                <span class="badge badge--2 mr-4" style="background-color: #28a745">{{ $status }}</span>
                            @else
                                <span class="badge badge--2 mr-4" style="background-color: #ffc107">{{ $status }}</span>
                            @endif                                
                        </td>
                        <td class="text-center">
                            @php
                                $status = 'Paid';
                                foreach ($order->orderDetails as $key => $orderDetail) {
                                    if($orderDetail->payment_status != 'paid'){
                                        $status = 'Unpaid';
                                    }
                                }
                            @endphp
                            @if($status == 'Paid')
                                <span class="badge badge--2 mr-4" style="background-color: #28a745">{{ $status }}</span>
                            @else
                                <span class="badge badge--2 mr-4" style="background-color: #e22020">{{ $status }}</span>
                            @endif 
                        </td>
                        <td class="text-center">
                            <div class="btn-group dropdown">
                                <button class="btn btn-primary dropdown-toggle dropdown-toggle-icon" data-toggle="dropdown" type="button">
                                    {{__('Actions')}} <i class="dropdown-caret"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a href="{{route('sales.show', encrypt($order->id))}}">{{__('View')}}</a></li>
                                    <li><a href="{{ route('customer.invoice.download', $order->id) }}">{{__('Download Invoice')}}</a></li>
                                    <!--<li><a onclick="confirm_modal('{{route('orders.destroy', $order->id)}}');">{{__('Delete')}}</a></li>-->
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <!--@endif-->
                @endforeach
            </tbody>
        </table>

    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading">
                <h4 class="panel-title">
                    Formula #1 : Overall Total
                </h4>
            </div>
            <div class="panel-body text-center dash-widget pad-no">
                <div>
                    <p class="text-1x text-white bg-info" style="padding: 5px 0 5px 0;">
                        <b>Overall Total = ( Product Original Price Sold + Total Shipping Fees ) - Discounts Given </b>
                    </p>
                </div>
            </div>
        </div>
        <div class="panel" style="margin-top: -15px;">
            <div class="panel-heading">
                <h4 class="panel-title">
                    Formula #2 : Getting <b>Product Original Price sold , Total Shipping Fees and Discounts Given</b> from the
                    <strong class="text-success">Overall Total</strong>
                </h4>
            </div>
            <div class="panel-body text-center dash-widget pad-no">
                <div>
                    <p class="text-1x text-white bg-info" style="padding: 5px 0 5px 0;">
                        <b style="padding-right: 70px">Products = ( Overall Total - Total Shipping Fees ) + Discounts Given </b>
                           <b style="padding-right: 70px">Shipping Fees = ( Overall Total - Product Original Price Sold ) + Discounts Given </b>
                                <b >Discounts = (Product Original Price Sold + Shipping Fees) - Overall Total</b>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('script')
    <script type="text/javascript">

    </script>
@endsection
