@extends('layouts.app')

@section('content')

<!-- Basic Data Tables -->
<!--===================================================-->
<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title">{{__('For Pickup')}}</h3>
    </div>
    <div class="panel-body">
        <table class="table table-striped table-bordered demo-dt-basic" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{__('Order Placed')}}</th>
                    <th>{{__('Order Code')}}</th>
                    {{-- <th>{{__('Num. of Products')}}</th> --}}
                    <th>{{__('Customer')}}</th>
                    <th>{{__('Seller Name')}}</th>
                    <th class="text-center">{{__('Shop')}}</th>
                    <th>{{__('Amount')}}</th>
                    <th>{{__('Delivery Status')}}</th>
                    <th>{{__('Payment Method')}}</th>
                    <th>{{__('Payment Status')}}</th>
                    <th>{{__('Pick up date')}}</th>
                    <th width="10%">{{__('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $key => $order_id)
                    @php
                        $order = \App\Order::find($order_id->id);

                        $orderD = DB::table('order_details')->where('order_id', $order_id->id)->groupBy('seller_id')->get();
                    @endphp
                    
                    @foreach($orderD as $OD)
                        @php
                            $shopss =  DB::table('shops')->where('user_id','=', $OD->seller_id)->get();
                            $sellerss =  DB::table('users')->where('id','=', $OD->seller_id)->get();
                        @endphp

                        {{-- @if($OD->pick_from != null) --}}
                            @if($order != null)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ date('m-d-Y h﻿﻿:i A', strtotime($order->created_at)) }}</td>
                                    <td>{{ $order->code }} @if($order->viewed == 0) <span class="pull-right badge badge-info">{{ __('New') }}</span> @endif</td>
                                    <td>
                                        @if ($order->user_id != null)
                                            {{ $order->user->name }}
                                        @else
                                            Guest ({{ $order->guest_id }})
                                        @endif
                                    </td>
                                    @foreach($sellerss as $seller)
                                        <td>{{ $seller->name }}</td>
                                    @endforeach
                                    @foreach($shopss as $shop)
                                        <td>{{ $shop->name }}</td>
                                    @endforeach
                                    <td class="text-center">
                                        <div class="label label-table label-default" style="width: 100px;" >
                                            {{ single_price($order->orderDetails->where('seller_id', '=', $OD->seller_id)->sum('price') + $order->orderDetails->where('seller_id', '=', $OD->seller_id)->sum('tax') + $order->orderDetails->where('seller_id', '=', $OD->seller_id)->sum('shipping_cost')) }}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $status = $order->orderDetails->where('seller_id', $OD->seller_id)->first()->delivery_status;
                                        @endphp
                                        @if (ucfirst(str_replace('_', ' ', $status)) == 'Delivered')
                                            <span class="badge badge--2 mr-4" style="background-color: #28a745">
                                                {{ ucfirst(str_replace('_', ' ', $status)) }}
                                            </span>
                                        @elseif (ucfirst(str_replace('_', ' ', $status)) == 'Pending')
                                            <span class="badge badge--2 mr-4" style="background-color: #ffc107">
                                                {{ ucfirst(str_replace('_', ' ', $status)) }}
                                            </span>
                                        @elseif ($status == 'on_review' || $status == 'on_delivery')
                                            <span class="badge badge--2 mr-4" style="background-color: #007bff">
                                                {{ ucfirst(str_replace('_', ' ', $status)) }}
                                            </span>
                                        @else
                                            <span class="badge badge--2 mr-4" style="background-color: #dc3545">
                                                {{ ucfirst(str_replace('_', ' ', $status)) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        {{ ucfirst(str_replace('_', ' ', $order->payment_type)) }}
                                    </td>
                                    <td class="text-center">
                                        @if ($order->orderDetails->where('seller_id', $OD->seller_id)->first()->payment_status == 'paid')
                                            <span class="badge badge--2 mr-4" style="background-color: #28a745">
                                                <i class="bg-green"></i> Paid
                                            </span>
                                        @elseif ($order->orderDetails->where('seller_id', $OD->seller_id)->first()->payment_status == 'declined')
                                            <span class="badge badge--2 mr-4" style="background-color: #dc3545">
                                                <i></i> Declined by seller
                                            </span>
                                        @else
                                            <span class="badge badge--2 mr-4" style="background-color: #007bff">
                                                <i></i> Unpaid
                                            </span>
                                        @endif
                                    </td>
                                    @if( $order->orderDetails->where('seller_id', $OD->seller_id)->first()->payment_status == 'declined' )
                                        <td class="text-center text-danger">Seller declined the order</td>
                                    @else
                                        @if( $order->orderDetails->where('seller_id', $OD->seller_id)->first()->pick_from != null)
                                            <td class="text-center text-info">{{ $order->orderDetails->where('seller_id', $OD->seller_id)->first()->pick_from }}</td>
                                        @elseif ($order->orderDetails->where('seller_id', $OD->seller_id)->first()->payment_status == 'paid')
                                            <td class="text-center text-success">Transaction Completed</td>
                                        @else
                                            <td class="text-center">Seller need to confirm order</td>
                                        @endif
                                    @endif
                                    <td class="text-center">
                                        <div class="btn-group dropdown">
                                            <button class="btn btn-primary dropdown-toggle dropdown-toggle-icon" data-toggle="dropdown" type="button">
                                                {{__('Actions')}} <i class="dropdown-caret"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-right">
                                                <li><a href="{{ route('pickup_list.view', ['order_id' => encrypt($order->id), 'seller_id' => encrypt($OD->seller_id)]) }}">{{__('View')}}</a></li>
{{--                                                 <li><a href="{{ route('customer.invoice.download', $order->id) }}">{{__('Download Invoice')}}</a></li>
                                                <li><a onclick="confirm_modal('{{route('orders.destroy', $order->id)}}');">{{__('Delete')}}</a></li> --}}
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        {{-- @endif --}}

                    @endforeach
                @endforeach
            </tbody>
        </table>

    </div>
</div>

@endsection


@section('script')
    <script type="text/javascript">

    </script>
@endsection
