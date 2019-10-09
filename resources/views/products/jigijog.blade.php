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

@if($type != 'Seller')
    <div class="row">
        <div class="col-lg-12">
            <a href="{{ route('products.create')}}" class="btn btn-rounded btn-info pull-right">{{__('Add New Product')}}</a>
        </div>
    </div>
@endif

<br>

<style>
    #min:hover, #max:hover{
        cursor:pointer;
    }
</style>

<div class="col-lg-12">
    <div class="panel">
        <!--Panel heading-->
        <div class="panel-heading">
            <h3 class="panel-title">{{ __($type.' Products') }}</h3>
        </div>
        <div class="panel-body">
            <div style="width: 100%; padding-left: -10px;">
                <div class="table-responsive"> 
                    <table class="table table-striped table-bordered" id="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th width="20%">{{__('Product Name')}}</th>
                                @if($type == 'Seller')
                                    <th>{{__('Seller Name')}}</th>
                                    <th>{{__('Shop Name')}}</th>
                                @endif
                                <th>{{__('Current qty')}}</th>
                                <th>{{__('Base Price')}}</th>
                                <th>{{__('Date of Product Created')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $key => $product)
                                @if($type == 'Seller')
                                    @php
                                        $shops =  DB::table('shops')->where('user_id','=', $product->user_id)->get();
                                        $sellers =  DB::table('users')->where('id','=', $product->user_id)->get();
                                    @endphp
                                @endif
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td><a style="color: #ff4e00;" href="{{ route('product', $product->slug) }}" target="_blank">{{ __($product->name) }}</a></td>

                                    @if($type == 'Seller')
                                        @foreach($sellers as $seller)
                                            <td class="text-center">{{ $seller->name }}</td>
                                        @endforeach
                                        @foreach($shops as $shop)
                                            <td class="text-center">{{ $shop->name }}</td>
                                        @endforeach
                                    @endif
                                    <td class="text-center">
                                        @php
                                            $qty = 0;
                                            foreach (json_decode($product->variations) as $key => $variation) {
                                                $qty += $variation->qty;
                                            }
                                            echo $qty;
                                        @endphp
                                    </td>
                                    <td class="text-center">
                                        <div class="label label-table label-default" style="width: 100px;" >P{{ $product->unit_price }}</div>
                                    </td>
                                    <td class="text-center">{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $product->created_at)->format('m.d.Y') }}</td>
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
                var startDate = new Date(data[6]);
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
