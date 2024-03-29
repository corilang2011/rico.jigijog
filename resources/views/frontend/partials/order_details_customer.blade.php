<div class="modal-header">
    <h5 class="modal-title strong-600 heading-5">{{__('Order id')}}: {{ $order->code }}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

@php
    $orderD = DB::table('order_details')->where('order_id', $order->id)->groupBy('seller_id')->get();
@endphp

@foreach($orderD as $OD)       
    <div>
        @php
            $status = $order->orderDetails->where('seller_id', $OD->seller_id)->first()->delivery_status;
            $orderDetails = DB::table('order_details')->where('order_id', $OD->order_id)->where('seller_id', $OD->seller_id)->get();
        @endphp
        <div class="modal-body gry-bg px-3 pt-0">
            <div class="pt-4">
                @if($status != 'declined')
                <ul class="process-steps clearfix">
                    <li @if($status == 'pending') class="active" @else class="done" @endif>
                        <div class="icon">1</div>
                        <div class="title">{{__('Order placed')}}</div>
                    </li>
                    <li @if($status == 'on_review') class="active" @elseif($status == 'on_delivery' || $status == 'delivered') class="done" @endif>
                        <div class="icon">2</div>
                        <div class="title">{{__('On review')}}</div>
                    </li>
                    <li @if($status == 'on_delivery') class="active" @elseif($status == 'delivered') class="done" @endif>
                        <div class="icon">3</div>
                        <div class="title">{{__('On delivery')}}</div>
                    </li>
                    <li @if($status == 'delivered') class="done" @endif>
                        <div class="icon">4</div>
                        <div class="title">{{__('Delivered')}}</div>
                    </li>
                </ul>
                @else
                    <div class="container-fluid text-center" style="color:red;">
                        <h5>YOUR ORDER TO THIS SHOP WAS DECLINED BY THE SELLER</h5>
                    </div>
                @endif
            </div>
            

            <div class="card mt-4">
                <div class="card-header py-2 px-3 heading-6 strong-600 clearfix">
                    <div class="float-left">{{__('Order Summary')}}</div>
                </div>
                <div class="card-body pb-0">
                    @php
                        $shopName = DB::table('shops')->where('user_id', $OD->seller_id)->first();
                        $payment_stat = $order->orderDetails->where('seller_id', $OD->seller_id)->first()->payment_status;
                    @endphp
                    <div class="row pb-4">
                        <div class="col-md-6">
                            <h5>Shop Name: <strong>{{ $shopName->name }}</strong></h5>
                        </div>
                        <div class="col-md-6" style="font-size: 20px;">
                            <div class="row">
                                <strong>{{__('Payment Status')}}:</strong>&nbsp;
                                @if($payment_stat == 'paid')
                                    <span style="color:green;" >PAID</span> 
                                @elseif($payment_stat == 'unpaid')
                                    <span style="color:orange;" >UNPAID</span> 
                                @else
                                    <span style="color:red;">DECLINED</span> 
                                @endif
                            </div>
                            <!-- <div class="row">
                                <strong>{{__('Delivery Status')}}:</strong>&nbsp;
                                    @if($status == 'pending')
                                        <span style="color:green;" >PENDING</span> 
                                    @elseif($status == 'on_review')
                                        <span style="color:green;" >ON REVIEW</span> 
                                    @elseif($status == 'on_delivery')
                                        <span style="color:pink;" >ON DELIVERY</span> 
                                    @else
                                        <span style="color:red;">DECLINED</span> 
                                    @endif
                            </div> -->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <table class="details-table table">
                                <tr>
                                    <td class="w-50 strong-600">{{__('Order Code')}}:</td>
                                    <td>{{ $order->code }}</td> 
                                </tr>
                                <tr>
                                    <td class="w-50 strong-600">{{__('Customer')}}:</td>
                                    <td>{{ json_decode($order->shipping_address)->name }}</td>
                                </tr>
                                <tr>
                                    <td class="w-50 strong-600">{{__('Email')}}:</td>
                                    @if ($order->user_id != null)
                                        <td>{{ $order->user->email }}</td>
                                    @endif
                                </tr>
                                <tr>
                                    <td class="w-50 strong-600">{{__('Shipping address')}}:</td>
                                    <td>{{ json_decode($order->shipping_address)->address }}, {{ json_decode($order->shipping_address)->city }}, {{ json_decode($order->shipping_address)->country }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-lg-6">
                            <table class="details-table table">
                                <tr>
                                    <td class="w-50 strong-600">{{__('Order date')}}:</td>
                                    <td>{{ date('d-m-Y H:m A', $order->date) }}</td>
                                </tr>
                                <tr>
                                    <td class="w-50 strong-600">{{__('Order status')}}:</td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $status)) }}</td>
                                </tr>
                                <tr>
                                    <td class="w-50 strong-600">{{__('Total order amount')}}:</td>
                                    <td>{{ single_price($order->orderDetails->where("seller_id", $OD->seller_id)->sum('price') + $order->orderDetails->sum('tax')) }}</td>
                                </tr>
                                <tr>
                                    <td class="w-50 strong-600">{{__('Payment method')}}:</td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $order->payment_type)) }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-9">
                    <div class="card mt-4">
                        <div class="card-header py-2 px-3 heading-6 strong-600">{{__('Order Details')}}</div>
                        <div class="card-body pb-0">
                            <table class="details-table table">
                                <thead>
                                    <tr>
                                       
                                        <th width="40%">{{__('Product')}}</th>
                                        <th>{{__('Variation')}}</th>
                                        <th>{{__('Quantity')}}</th>
                                        <th>{{__('Price')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->orderDetails->where("seller_id", $OD->seller_id) as $keys => $orderDetail)
                                        <tr>
                                            
                                            <td><a href="{{ route('product', $orderDetail->product->slug) }}" target="_blank">{{ $orderDetail->product->name }}</a></td>
                                            <td>
                                                {{ $orderDetail->variation }}
                                            </td>
                                            <td>
                                                {{ $orderDetail->quantity }}
                                            </td>
                                            <td>{{ single_price($orderDetail->price) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="card mt-4">
                        <div class="card-header py-2 px-3 heading-6 strong-600">{{__('Order Ammount')}}</div>
                        <div class="card-body pb-0">
                            <table class="table details-table">
                                <tbody>
                                    <tr>
                                        <th>{{__('Subtotal')}}</th>
                                        <td class="text-right">
                                            <span class="strong-600">{{ single_price($order->orderDetails->where("seller_id", $OD->seller_id)->sum('price')) }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{__('Shipping')}}</th>
                                        <td class="text-right">
                                            <span class="text-italic">{{ single_price($order->orderDetails->where("seller_id", $OD->seller_id)->sum('shipping_cost')) }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{__('Tax')}}</th>
                                        <td class="text-right">
                                            <span class="text-italic">{{ single_price($order->orderDetails->where("seller_id", $OD->seller_id)->sum('tax')) }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{__('Coupon Discount')}}</th>
                                        <td class="text-right">
                                            <span class="text-italic">{{ single_price($order->orderDetails->where("seller_id", $OD->seller_id)->first()->coupon_discount) }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th><span class="strong-600">{{__('Total')}}</span></th>
                                        <td class="text-right">
                                            <strong><span>{{ single_price(($order->orderDetails->where("seller_id", $OD->seller_id)->sum('price') + $order->orderDetails->where("seller_id", $OD->seller_id)->sum('shipping_cost')) - $order->orderDetails->where("seller_id", $OD->seller_id)->sum('coupon_discount')) }}</span></strong>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- @foreach($orderDetails as $orderDeta)
            {{ $orderDeta->product_id }}
        @endforeach -->
        <br>
    </div>
@endforeach

<div>
    <div class="col-lg-12">
        <div class="card mt-4">
            <div class="card-header py-2 px-3 heading-6 strong-600 text-right">{{__('Overall Total')}}<br><br>{{ single_price($order->grand_total) }}</div>
        </div>
    </div>
</div>
<br>