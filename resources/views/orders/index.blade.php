@extends('layouts.app')

@section('content')

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">

<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>

<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>
<script type="text/javascripts" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>


<style>
    #min:hover, #max:hover{
        cursor:pointer;
    }
</style>
<!-- Basic Data Tables -->
<!--===================================================-->
<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title">{{__('Orders')}}</h3>
    </div>
    <div class="panel-body">
        <div style="width: 100%; padding-left: -10px;">
            <div class="table-responsive"> 
                <table class="table table-striped table-bordered" id="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{__('Date Placed')}}</th>
                            <th>{{__('Order Code')}}</th>
                            <th>{{__('Num. of Products')}}</th>
                            <th>{{__('Marketer')}}</th>
                            <th>{{__('Customer')}}</th>
                            <th>{{__('Seller Name')}}</th>
                            <th>{{__('Amount')}}</th>
                            <th>{{__('Delivery Status')}}</th>
                            <th>{{__('Payment Method')}}</th>
                            <th>{{__('Payment Status')}}</th>
                            <th width="10%">{{__('Options')}}</th>
                            <td>{{__('Time')}}</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $key => $order_id)
                            @php
                                $order = \App\Order::find($order_id->id);
                                $get_seller_id = $order->orderDetails->first()->seller_id;
                                $shops =  DB::table('shops')->where('user_id','=', $get_seller_id)->get();
                                $sellers =  DB::table('users')->where('id','=', $get_seller_id)->get();
                                $shipping_name = json_decode($order->shipping_address);
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
                                    <td>{{ $shipping_name->name }}</td>
                                    @foreach($sellers as $seller)
                                        <td>{{ $seller->name }}</td>
                                    @endforeach
                                    <td class="text-center">
                                        <div class="label label-table label-default" style="width: 100px;" >
                                            {{ single_price($order->orderDetails->where('seller_id', '!=', Auth::user()->id)->sum('price') + $order->orderDetails->where('seller_id', '!=', Auth::user()->id)->sum('shipping_cost') - $order->coupon_discount) }}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $status = $order->orderDetails->first()->delivery_status;
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
                                        @elseif ($status == 'returned_item' || $status == 'returned_item')
                                            <span class="badge badge--2 mr-4" style="background-color: #008080">
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
                                        @if ($order->orderDetails->first()->payment_status == 'paid')
                                            <span class="badge badge--2 mr-4" style="background-color: #28a745">
                                                <i class="bg-green"></i> Paid
                                            </span>
                                        @elseif ($order->orderDetails->first()->payment_status == 'declined')
                                            <span class="badge badge--2 mr-4" style="background-color: #dc3545">
                                                <i></i> Declined Unauthorized
                                            </span>
                                        @else
                                            <span class="badge badge--2 mr-4" style="background-color: #e22020">
                                                <i></i> Unpaid
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group dropdown">
                                            <button class="btn btn-primary dropdown-toggle dropdown-toggle-icon" data-toggle="dropdown" type="button">
                                                {{__('Actions')}} <i class="dropdown-caret"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-right">
                                                <li><a href="{{ route('orders.show', encrypt($order->id)) }}">{{__('View')}}</a></li>
                                                <li><a href="{{ route('customer.invoice.download', $order->id) }}">{{__('Download Invoice')}}</a></li>
                                                @if(Auth::user()->user_type == 'admin')
                                                    @if ($order->orderDetails->first()->payment_status != 'paid')
                                                        <li><a onclick="confirm_modal('{{route('orders.destroy', $order->id)}}');">{{__('Delete')}}</a></li>
                                                    @endif
                                                @endif
                                            </ul>
                                        </div>
                                    </td>
                                    <td class="text-center">{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $order->created_at)->format('m.d.Y') }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                    <tbody>
                        <tr>
                            <td>From:</td>
                            <td><input name="min" id="min" type="text"></td>
                        </tr>
                        <tr>
                            <td>To:</td>
                            <td><input name="max" id="max" type="text"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection


@section('script')
    <script type="text/javascript">

        $(document).ready(function(){
            //$('#container').removeClass('mainnav-lg').addClass('mainnav-sm');
            $.fn.dataTable.ext.search.push(
            function (settings, data, dataIndex) {
                var min = $('#min').datepicker("getDate");
                var max = $('#max').datepicker("getDate");
                var startDate = new Date(data[12]);
                if (min == null && max == null) { return true; }
                if (min == null && startDate <= max) { return true;}
                if(max == null && startDate >= min) {return true;}
                if (startDate <= max && startDate >= min) { return true; }
                return false;
            }
            );

            $("#min").datepicker({ onSelect: function () { table.draw(); }, changeMonth: true, changeYear: true });
            $("#max").datepicker({ onSelect: function () { table.draw(); }, changeMonth: true, changeYear: true });

// ===============================================================================================
            var table = $('#table').DataTable({
                    "columnDefs": [
                        {
                            "targets": [ 12 ],
                            "visible": false
                        }
                    ],
                    'dom': 'Bfrtip',
                    "buttons": {
                       "dom": {
                          "button": {
                            "tag": "button",
                            "className": "btn btn-primary"
                          }
                       },
                       "buttons": [ 
                            // { extend: 'copyHtml5', footer: true },
                            { extend: 'csvHtml5', text: 'EXPORT DATA', footer: true, exportOptions: { columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 ,10 ] } },
                            // { extend: 'pdfHtml5', footer: true }
                       ]   
                    }
            });
// ===============================================================================================
            $('#min, #max').change(function () {
                table.draw();
            });
        });
    </script>
@endsection