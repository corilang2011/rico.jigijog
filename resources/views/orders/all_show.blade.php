@extends('layouts.app')

@section('content')

    <div class="panel">
    	<div class="panel-body">
    		<div class="invoice-masthead">
    			<div class="invoice-text">
    				<h3 class="h1 text-thin mar-no text-primary">{{ __('Order Details') }}</h3>
    			</div>
    		</div>
    		<div class="invoice-bill row">
    			<div class="col-sm-6 text-xs-center">
    				<address>
        				<strong class="text-main">{{ json_decode($order->shipping_address)->name }}</strong><br>
                         {{ json_decode($order->shipping_address)->email }}<br>
                         {{ json_decode($order->shipping_address)->phone }}<br>
        				 {{ json_decode($order->shipping_address)->address }}, {{ json_decode($order->shipping_address)->city }}, {{ json_decode($order->shipping_address)->country }}
                    </address>
    			</div>
    			<div class="col-sm-6 text-xs-center">
    				<table class="invoice-details">
    				<tbody>
    				<tr>
    					<td class="text-main text-bold">
    						{{__('Order #')}}
    					</td>
    					<td class="text-right text-info text-bold">
    						{{ $order->code }}
    					</td>
    				</tr>
    				<tr>
    					<td class="text-main text-bold">
    						{{__('Order Status')}}
    					</td>
                        @php
                            $status = $order->orderDetails->first()->delivery_status;
                        @endphp
    					<td class="text-right">
                            @if($status == 'delivered')
                                <span class="badge badge-success">{{ ucfirst(str_replace('_', ' ', $status)) }}</span>
                            @else
                                <span class="badge badge-info">{{ ucfirst(str_replace('_', ' ', $status)) }}</span>
                            @endif
    					</td>
    				</tr>
    				<tr>
    					<td class="text-main text-bold">
    						{{__('Order Date')}}
    					</td>
    					<td class="text-right">
    						{{ date('d-m-Y H:m A', $order->date) }}
    					</td>
    				</tr>
                    <tr>
    					<td class="text-main text-bold">
    						{{__('Total amount')}}
    					</td>
    					<td class="text-right">
    						{{ single_price($order->orderDetails->sum('price') + $order->orderDetails->sum('tax')) }}
    					</td>
    				</tr>
                    <tr>
    					<td class="text-main text-bold">
    						{{__('Payment method')}}
    					</td>
    					<td class="text-right">
    						{{ ucfirst(str_replace('_', ' ', $order->payment_type)) }}
    					</td>
    				</tr>
    				</tbody>
    				</table>
    			</div>
    		</div>
    		<hr class="new-section-sm bord-no">
    		<div class="row">
    			<div class="col-lg-12 table-responsive">
    				<table class="table table-bordered invoice-summary">
        				<thead>
            				<tr class="bg-trans-dark">
                                <th class="min-col">#</th>
            					<th class="text-uppercase">
            						{{__('Description')}}
            					</th>
            					<th class="min-col text-center text-uppercase">
            						{{__('Qty')}}
            					</th>
            					<th class="min-col text-center text-uppercase">
            						{{__('Price')}}
            					</th>
            					<th class="min-col text-right text-uppercase">
            						{{__('Total')}}
            					</th>
            				</tr>
        				</thead>
        				<tbody>
                            @foreach ($order->orderDetails as $key => $orderDetail)
                                @php
                                    $seller = App\User::find($orderDetail->seller_id);
                                @endphp
                                @if($seller->user_type == 'admin')
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>
                                            <strong>
                                                <a href="{{ route('product', $orderDetail->product->slug) }}" target="_blank" class="text-info">
                                                    {{ $orderDetail->product->name }}
                                                </a>
                                            </strong>
                                            <small>{{ $orderDetail->variation }}</small>
                                        </td>
                                        <td class="text-center">
                                            {{ $orderDetail->quantity }}
                                        </td>
                                        <td class="text-center">
                                            {{ single_price($orderDetail->price/$orderDetail->quantity) }}
                                        </td>
                                        <td class="text-center">
                                            {{ single_price($orderDetail->price) }}
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>
                                            <strong>
                                                <a href="{{ route('product', $orderDetail->product->slug) }}" target="_blank" class="text-info">
                                                    {{ $orderDetail->product->name }}
                                                </a>
                                            </strong>
                                        </td>
                                        <td class="text-center">
                                            {{ $orderDetail->quantity }}
                                        </td>
                                        <td class="text-center">
                                            {{ single_price($orderDetail->price/$orderDetail->quantity) }}
                                        </td>
                                        <td class="text-center">
                                            {{ single_price($orderDetail->price) }}
                                        </td>
                                    </tr>
                                @endif
                                
                            @endforeach
        				</tbody>
    				</table>
    			</div>
    		</div>
    		<div class="clearfix">
    			<table class="table invoice-total">
    			<tbody>
    			<tr>
    				<td>
    					<strong>{{__('Sub Total')}} :</strong>
    				</td>
    				<td>
    					{{ single_price($order->orderDetails->where('seller_id', '!=', Auth::user()->id)->sum('price')) }}
                    </td>
    			</tr>
                <tr>
    				<td>
    					<strong>{{__('Shipping')}} :</strong>
    				</td>
    				<td>
    					{{ single_price($order->orderDetails->where('seller_id', '!=', Auth::user()->id)->sum('shipping_cost')) }}
    				</td>
    			</tr>
    			<tr>
                    <td>
                        <strong>{{__('Discount')}} :</strong>
                    </td>
                    <td>
                        {{ single_price($order->coupon_discount) }}
                    </td>
                </tr>
    			<tr>
    				<td>
    					<strong>{{__('TOTAL')}} :</strong>
    				</td>
    				<td class="text-bold h4">
    					{{ single_price($order->orderDetails->sum('price') + $order->orderDetails->sum('tax') + $order->orderDetails->sum('shipping_cost') - $order->coupon_discount) }}
    				</td>
    			</tr>
    			</tbody>
    			</table>
    		</div>
    		<div class="text-right no-print">
    			<a href="#" onclick="printPage('{{ route('seller.invoice.print', $order->id) }}');" class="dropdown-item">{{__('Print Invoice')}}<i class="demo-pli-printer icon-lg"></i></a>
    		</div>
    	</div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $('#update_delivery_status').on('change', function(){
            var order_id = {{ $order->id }};
            var status = $('#update_delivery_status').val();
            $.post('{{ route('orders.update_delivery_status') }}', {_token:'{{ @csrf_token() }}',order_id:order_id,status:status}, function(data){
                showAlert('success', 'Delivery status has been updated');
                location.reload().setTimeOut(500);
            });
        });

        $('#update_payment_status').on('change', function(){
            var order_id = {{ $order->id }};
            var status = $('#update_payment_status').val();
            $.post('{{ route('orders.update_payment_status') }}', {_token:'{{ @csrf_token() }}',order_id:order_id,status:status}, function(data){
                showAlert('success', 'Payment status has been updated');
                location.reload().setTimeOut(500);
            });
        });
    </script>
@endsection
