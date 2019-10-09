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

<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title">{{__('Jigijog Sales Report')}}</h3>
    </div>
    <div class="panel-body">
        <div style="width: 100%; padding-left: -10px;">
            <div class="table-responsive"> 
                <table id="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{__('Order ID')}}</th>
                            <th>{{__('Name of the Product')}}</th>
                            <th>{{__('Base Price')}}</th>
                            <th>{{__('Selling Price')}}</th>
                            <th>{{__('Quantity Sold')}}</th>
                            <th>{{__('Total Base Price')}}</th>
                            <th>{{__('Total Selling Price')}}</th>
                            <th>{{__('Shipping Fee')}}</th>
                            <th>{{__('Coupon Discount')}}</th>
                            <th>{{__('Gross Income')}}</th>
                            <th>{{__('Net Income')}}</th>
                            <th>{{__('Date Created')}}</th>
                            {{-- <th width="10%">{{__('Options')}}</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $key => $order_details_id)
                        @php
                            $product = \App\Product::findOrFail($order_details_id->product_id);
                        @endphp
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $order_details_id->order_id }}</td>
                                    <td>{{ $product->name }} | {{ $order_details_id->variation }}</td>
                                    <td>{{ $order_details_id->base_price }}.00</td>
                                    @php
                                        $totalBP = (float)$order_details_id->base_price * (float)$order_details_id->quantity;
                                    @endphp
                                    <td>{{ $order_details_id->selling_price }}.00</td>
                                    <td>{{ $order_details_id->quantity }}</td>
                                    <td>{{ $totalBP }}.00</td>
                                    <td>{{ $order_details_id->price }}.00</td>
                                    <td>{{ $order_details_id->shipping_cost }}.00</td>
                                    @if($order_details_id->coupon_discount == null)
                                        @php
                                            $discount = 0;
                                        @endphp
                                        <td>0.00</td>
                                    @else
                                        @php
                                            $discount = $order_details_id->coupon_discount;
                                        @endphp
                                        <td>{{ $order_details_id->coupon_discount }}.00</td>
                                    @endif
                                    @php
                                        $gross = (float)$order_details_id->price + (float)$order_details_id->shipping_cost;
                                        $net = (float)$order_details_id->price + ((float)$order_details_id->shipping_cost - (float)$product->purchase_price) - $discount;
                                        $net -= (float)$order_details_id->shipping_cost;
                                    @endphp
                                    <td>{{ $gross }}.00</td>
                                    <td>{{ $net }}.00</td>
                                    <td>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $order_details_id->created_at)->format('m.d.Y') }}</td>
                                    {{-- <td></td> --}}
                                </tr>
                        @endforeach
                    </tbody>
                    <tfoot align="right">
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </tfoot>
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
<script>
    $(document).ready( function () {
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
        var numFormat = $.fn.dataTable.render.number( '\,', '.', 2, 'P' ).display;
        var table = $('#table').DataTable({
            "footerCallback": function (row, data, start, end, display) {
                var api = this.api(),
                    data;

                var intVal = function (i) {
                    return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
                };

                // KADA PAGINATION
                netTotal = api.column(11, {'search': 'applied'})
                    .data()
                    .reduce(function (a, b) {
                    return parseFloat(a) + parseFloat(b);
                }, 0);

                grossTotal = api.column(10, {'search': 'applied'})
                    .data()
                    .reduce(function (a, b) {
                    return parseFloat(a) + parseFloat(b);
                }, 0);

                couponTotal = api.column(9, {'search': 'applied'})
                    .data()
                    .reduce(function (a, b) {
                    return parseFloat(a) + parseFloat(b);
                }, 0);

                shipTotal = api.column(8, {'search': 'applied'})
                    .data()
                    .reduce(function (a, b) {
                    return parseFloat(a) + parseFloat(b);
                }, 0);

                totalSellTotal = api.column(7, {'search': 'applied'})
                    .data()
                    .reduce(function (a, b) {
                    return parseFloat(a) + parseFloat(b);
                }, 0);

                totalBaseTotal = api.column(6, {'search': 'applied'})
                    .data()
                    .reduce(function (a, b) {
                    return parseFloat(a) + parseFloat(b);
                }, 0);

                sellTotal = api.column(4, {'search': 'applied'})
                    .data()
                    .reduce(function (a, b) {
                    return parseFloat(a) + parseFloat(b);
                }, 0);

                baseTotal = api.column(3, {'search': 'applied'})
                    .data()
                    .reduce(function (a, b) {
                    return parseFloat(a) + parseFloat(b);
                }, 0);

                // TANAN
                total = api.column(8)
                    .data()
                    .reduce(function (a, b) {
                    return parseFloat(a) + parseFloat(b);
                });


                // UPDATE FOOTER
                $(api.column(3).footer()).html( 'Total: '+ numFormat(baseTotal));
                $(api.column(4).footer()).html( 'Total: '+ numFormat(sellTotal));
                $(api.column(8).footer()).html( 'Total: '+ numFormat(shipTotal));
                $(api.column(9).footer()).html( 'Total: '+ numFormat(couponTotal));
                $(api.column(7).footer()).html( 'Total: '+ numFormat(totalSellTotal));
                $(api.column(6).footer()).html( 'Total: '+ numFormat(totalBaseTotal));
                $(api.column(10).footer()).html( 'Total: '+ numFormat(grossTotal));
                $(api.column(11).footer()).html( 'Total: '+ numFormat(netTotal));
            },
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
    } );
</script>
@endsection
