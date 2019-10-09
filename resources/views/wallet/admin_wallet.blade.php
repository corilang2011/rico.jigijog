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
<div class="row">
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-body text-center dash-widget pad-no">
                <div class="pad-ver mar-top text-main">
                    <i class="demo-pli-wallet-2 icon-4x"></i>
                </div>
                <br>
                <p class="text-3x text-main bg-primary pad-ver">Jigijog Fee Wallet : <strong>{{single_price($wallets->sum('prod_orig_price') - $wallets->sum('amount'))}}</strong></p>
                <!--<p class="text-3x text-main bg-primary pad-ver">product : <strong>{{single_price($wallets->sum('prod_orig_price'))}}</strong></p>-->
            </div>
            <br><br>
        </div>
    </div>
</div>
@php
    $vendor_commission =  DB::table('business_settings')->select('value')->where('type', '=', 'vendor_commission')->get();
@endphp
<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title">{{__('Seller Product Sold Lists')}}</h3>
        <!--@foreach($vendor_commission as $commission)-->
        <!--    <h3 class="panel-title" style="margin-top: -10px; margin-left: 0%;">Jigijog fee = ({{ $commission->value }}%) of Total Amount of Products Sold ({{ single_price($wallets->sum('prod_orig_price')) }})</h3>                 -->
        <!--@endforeach-->
    </div>
    <div class="panel-body">
        <div style="width: 100%; padding-left: -10px;">
            <div class="table-responsive"> 
                <table class="table table-striped table-bordered" id="table" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>{{ __('#') }}</th>
                            <th>{{ __('Order Code') }}</th>
                            <th>{{ __('Seller Name') }}</th>
                            <th>{{ __('Shop Name') }}</th>
                            <th>{{__('Product Price Sold')}}</th>
                            <th>{{ __('Fee Status') }}</th>
                            <th>{{ __('Total Added in Wallet') }}</th>
                            <th>{{__('Date Sold')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($wallets as $key => $wallet)
                            @php
                                $id = $wallet->user_id;
                                $wallet->user =  DB::table('users')->where('id','=', $id)->get();
                                $shops =  DB::table('shops')->where('user_id','=', $id)->get();
                                $order_code =  DB::table('orders')->where('id','=', $wallet->w_order_id)->get();
                            @endphp
                            <tr>
                                @foreach ($wallet->user as  $users)
                                    <td>{{ $key+1 }}</td>
                                    @foreach($order_code as $code)
                                        <td>{{ $code->code }}</td>
                                    @endforeach
                                    <td class="text-center">{{ $users->name }}</td>
                                    @if($users->user_type == "seller")
                                        @foreach($shops as $shop)
                                            <td class="text-center">{{ $shop->name }}</td>
                                        @endforeach
                                    @endif
                                    <td>
                                        <div class="label label-table label-info" style="margin-left: 40px; width: 80px;" onclick="pay('{{$wallet->id}}');">
                                          {{ single_price($wallet->prod_orig_price) }} 
                                        </div>
                                    </td>
                                    @foreach($vendor_commission as $commission)
                                        <td style="color: #dc3545"><i class="fa fa-arrow-up" aria-hidden="true"></i> 
                                            {{__('Deducted')}} {{ $commission->value }}% ({{ single_price($wallet->prod_orig_price - $wallet->amount) }}) {{__('Jigijog Fee')}}
                                        </td>
                                    @endforeach

                                    <td>
                                        <div class="label label-table label-success" style="margin-left: 55px; width: 80px;" onclick="pay('{{$wallet->id}}');">
                                          {{ single_price($wallet->amount) }} 
                                        </div>
                                    </td>
                                    <td>{{ date('m-d-Y', strtotime($wallet->created_at))}}</td>
                                @endforeach
                            </tr>
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
                var startDate = new Date(data[7]);
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
                            { extend: 'csvHtml5', text: 'EXPORT DATA', footer: true },
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