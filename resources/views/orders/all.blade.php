@extends('layouts.app')

@section('content')
@php
    $admin = App\User::select('id')->where('user_type', 'admin')->get();
    $admin_id = array();
    foreach($admin as $i) { array_push($admin_id, $i['id']); }
@endphp
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
                    <th>{{__('Order Code')}}</th>
                    <th>{{__('Num. of Products')}}</th>
                    <th>{{__('Product Holder')}}</th>
                    <th>{{__('Customer')}}</th>
                    {{-- <th>{{__('Amount')}}</th> --}}
                    <th>{{__('Delivery Status')}}</th>
                    <th>{{__('Payment Method')}}</th>
                    <th>{{__('Payment Status')}}</th>
                    <th width="10%">{{__('Options')}}</th>
                </tr>
            </thead>
            <tbody>
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
                                {{ $order->code }} @if($order->viewed == 0) <span class="pull-right badge badge-info">{{ __('New') }}</span> @endif
                            </td>
                            <td>
                                {{ count($order->orderDetails) }}
                            </td>
                            <td>
                                <span>
                                    @if( count($order->orderDetails->whereIn('seller_id', $admin_id)) == count($order->orderDetails) )
                                        <span class="text-info">
                                            {{ __('Inhouse') }}
                                        </span>
                                    @elseif( count($order->orderDetails->whereNotIn('seller_id', $admin_id)) == count($order->orderDetails) )
                                        <span class="text-warning">
                                            {{ __('Others') }}
                                        </span>
                                    @else
                                            {{ __('Mixed') }}
                                    @endif
                                </span>
                            </td>
                            <td>
                                @if ($order->user_id != null)
                                    {{ $order->user->name }}
                                @else
                                    Guest ({{ $order->guest_id }})
                                @endif
                            </td>
                            {{-- <td>
                                {{ single_price($order->orderDetails->sum('price') + $order->orderDetails->sum('tax')) }}
                            </td>  --}}
                            <td>
                                @php
                                    $status = $order->orderDetails->first()->delivery_status;
                                @endphp
                                    {{ ucfirst(str_replace('_', ' ', $status)) }}
                            </td>
                            <td>
                                {{ ucfirst(str_replace('_', ' ', $order->payment_type)) }}
                            </td>
                            <td>
                                @if ($order->orderDetails->first()->payment_status == 'paid')
                                    <span class="badge badge--2 mr-4 bg-success">
                                        <i class="bg-success"></i> Paid
                                    </span>
                                @else
                                    <span class="badge badge--2 mr-4">
                                        <i class="bg-red"></i> Unpaid
                                    </span>
                                @endif
                                
                            </td>
                            <td>
                                <div class="btn-group dropdown">
                                    <button class="btn btn-primary dropdown-toggle dropdown-toggle-icon" data-toggle="dropdown" type="button">
                                        {{__('Actions')}} <i class="dropdown-caret"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li><a href="{{ route('orders.show.all', encrypt($order->id)) }}">{{__('View')}}</a></li>
                                        {{-- <li><a href="{{ route('seller.invoice.download', $order->id) }}">{{__('Download Invoice')}}</a></li> --}}
										{{-- <li><a href="#" onclick="printPage('{{ route('seller.invoice.print', $order->id) }}');" class="dropdown-item">{{__('Print Invoice')}}</a></li> --}}
                                        {{-- <li><a onclick="confirm_modal('{{route('orders.destroy', $order->id)}}');">{{__('Delete')}}</a></li> --}}
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
<small>adfafd</small>

@endsection


@section('script')
    <script type="text/javascript">

    </script>
@endsection
