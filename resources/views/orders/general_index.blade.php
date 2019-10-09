@extends('layouts.app')

@section('content')

<!-- Basic Data Tables -->
<!--===================================================-->
<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title">{{__('Orders')}}</h3>
    </div>
    <div class="panel-body">
        <table class="table table-striped table-bordered demo-dt-basic" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{__('Date Placed')}}</th>
                    <th>{{__('Order Code')}}</th>
                    <th>{{__('Num. of Products')}}</th>
                    <th>{{__('Customer')}}</th>
                    <th>{{__('Seller Name')}}</th>
                    <th class="text-center">{{__('Shop')}}</th>
                    <th>{{__('Amount')}}</th>
                    <th>{{__('Payment Method')}}</th>
                    <th width="10%">{{__('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $key => $order_id)
                    @php
                        $order = \App\Order::find($order_id->id);
                        $get_seller_id = $order->orderDetails->first()->seller_id;
                        $shops =  DB::table('shops')->where('user_id','=', $get_seller_id)->get();
                        $sellers =  DB::table('users')->where('id','=', $get_seller_id)->get();
                    @endphp
                    @if($order != null)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ date('m-d-Y  h:i A', strtotime($order->created_at)) }}</td>
                            <td>{{ $order->code }} @if($order->viewed == 0) <span class="pull-right badge badge-info">{{ __('New') }}</span> @endif</td>
                            <td class="text-center">{{ count($order->orderDetails) }}</td>
                            <td>
                                @if ($order->user_id != null)
                                    {{ $order->user->name }}
                                @else
                                    Guest ({{ $order->guest_id }})
                                @endif
                            </td>
                            @foreach($sellers as $seller)
                                <td>{{ $seller->name }}</td>
                            @endforeach
                            @foreach($shops as $shop)
                                <td>{{ $shop->name }}</td>
                            @endforeach
                            <td class="text-center">
                                <div class="label label-table label-default" style="width: 100px;" >
                                    {{ single_price($order->orderDetails->where('seller_id', '!=', Auth::user()->id)->sum('price') + $order->orderDetails->where('seller_id', '!=', Auth::user()->id)->sum('shipping_cost') - $order->coupon_discount) }}
                                </div>
                            </td>
                            <td class="text-center">
                                {{ ucfirst(str_replace('_', ' ', $order->payment_type)) }}
                            </td>
                            <td class="text-center">
                                <div class="btn-group dropdown">
                                    <button class="btn btn-primary dropdown-toggle dropdown-toggle-icon" data-toggle="dropdown" type="button">
                                        {{__('Actions')}} <i class="dropdown-caret"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li><a href="{{ route('general.show', encrypt($order->id)) }}">{{__('View')}}</a></li>
                                        <li><a href="{{ route('customer.invoice.download', $order->id) }}">{{__('Download Invoice')}}</a></li>
										<li><a href="#" onclick="printPage('{{ route('seller.invoice.print', $order->id) }}');" class="dropdown-item">{{__('Print Invoice')}}</a></li>
										@if ($order->payment_status != 'paid')
                                            <li><a onclick="confirm_modal('{{route('orders.destroy', $order->id)}}');">{{__('Delete')}}</a></li>
                                        @endif
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endif
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
