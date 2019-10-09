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
    <div class="col-lg-12">
        <a href="{{ route('expense.create')}}" class="btn btn-rounded btn-info pull-right">{{__('Add New Expenses')}}</a>
    </div>
</div>

<br>

<div class="col-lg-12">
    <div class="panel">
        <!--Panel heading-->
        <div class="panel-heading">
            <h3 class="panel-title">{{ __('Expenses') }}</h3>
        </div>
        <div class="panel-body">
	        <div style="width: 100%; padding-left: -10px;">
	            <div class="table-responsive"> 
		            <table id="table">
		                <thead>
		                    <tr>
		                        <th>#</th>
		                        <th>{{__('Description')}}</th>
		                        <th>{{__('Amount')}}</th>
		                        <th>{{__('Date Added')}}</th>
                                @if(Auth::user()->user_type == 'admin')
                                    <th>{{__('Actions')}}</th>
                                @endif
		                    </tr>
		                </thead>
		                <tbody>
							@foreach($expense as $key => $e)
								<tr>
									<td>{{ $key+1 }}</td>
									<td>{{ $e->description }}</td>
									<td>{{ number_format($e->amount,2) }}</td>
									<td>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $e->created_at)->format('m.d.Y') }}</td>
                                    @if(Auth::user()->user_type == 'admin')
                                    <td class="text-center">
                                        <div class="btn-group dropdown">
                                            <button class="btn btn-primary dropdown-toggle dropdown-toggle-icon" data-toggle="dropdown" type="button">
                                                {{__('Actions')}} <i class="dropdown-caret"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-right">
                                                <li><a onclick="confirm_modal('{{route('expense.destroy', $e->id)}}');">{{__('Delete')}}</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                    @endif
								</tr>
							@endforeach
		                </tbody>
	                    <tfoot align="right">
	                        <tr>
	                            <th></th>
	                            <th></th>
	                            <th></th>
	                            <th></th>
                                @if(Auth::user()->user_type == 'admin')
                                    <th></th>
                                @endif
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
</div>

@endsection


@section('script')
    <script type="text/javascript">
    $(document).ready( function () {
        $.fn.dataTable.ext.search.push(
        function (settings, data, dataIndex) {
            var min = $('#min').datepicker("getDate");
            var max = $('#max').datepicker("getDate");
            var startDate = new Date(data[3]);
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
                netTotal = api.column(2, {'search': 'applied'})
                    .data()
                    .reduce(function (a, b) {
                    return parseFloat(a) + parseFloat(b);
                }, 0);

                // TANAN
                total = api.column(2)
                    .data()
                    .reduce(function (a, b) {
                    return parseFloat(a) + parseFloat(b);
                });


                // UPDATE FOOTER
                $(api.column(2).footer()).html( 'Total: '+ numFormat(netTotal));
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
