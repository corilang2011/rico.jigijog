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

<div class="container-fluid">
    <div class="row">
        <button id="bb" class="btn btn-primary" style="margin:10px;">DOWNLOAD USERS</button>
    </div>
</div>
<div class="col-sm-12">
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">{{__('Send Newsletter')}}</h3>
        </div>
        <!--Horizontal Form-->
        <!--===================================================-->
        <form class="form-horizontal" action="{{ route('newsletters.send') }}" method="POST" enctype="multipart/form-data">
        	@csrf
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="name">{{__('Emails')}} ({{__('Users')}})</label>
                    <div class="col-sm-10">
                        <select class="form-control demo-select2-multiple-selects" name="user_emails[]" multiple>
                            @foreach($users as $user)
                                <option value="{{$user->email}}">{{$user->email}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="name">{{__('Emails')}} ({{__('Subscribers')}})</label>
                    <div class="col-sm-10">
                        <select class="form-control demo-select2-multiple-selects" name="subscriber_emails[]" multiple>
                            @foreach($subscribers as $subscriber)
                                <option value="{{$subscriber->email}}">{{$subscriber->email}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="subject">{{__('Newsletter subject')}}</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="subject" id="subject" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="name">{{__('Newsletter content')}}</label>
                    <div class="col-sm-10">
                        <textarea class="editor" name="content" required></textarea>
                    </div>
                </div>
            </div>
            <div class="panel-footer text-right">
                <button class="btn btn-purple" type="submit">{{__('Send')}}</button>
            </div>
        </form>
        <!--===================================================-->
        <!--End Horizontal Form-->

    </div>
</div>
@php
    $result = DB::table('users')->get();
    $subscribers = DB::table('subscribers')->get();
@endphp

<div class="container-fluid" id="content">
    <table id="extract" class="display">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                </tr>
            </thead>
            <tbody>
                @foreach($result as $r)
                    <tr>
                        <td>{{ $r->name }}</td>
                        <td>{{ $r->email }}</td>
                        <td>{{ $r->phone }}</td>
                    </tr>
                @endforeach
                @foreach($subscribers as $s)
                    <tr>
                        <td></td>
                        <td>{{ $s->email }}</td>
                        <td></td>
                    </tr>
                @endforeach
            </tbody>
    </table>
</div>

<script type="text/javascript"> 
$('#content').hide();
$(document).ready( function () {
    var table = $('#extract').DataTable({
                'dom': 'Bfrtip',
                "buttons": {
                   "dom": {
                      "button": {
                        "tag": "button",
                        "className": "waves-effect waves-light btn mrm"
                      }
                   },
                   "buttons": [ 'csv' ]   
                }
});

$('#bb').on('click', function(){
    table.button( '0,0' ).trigger();
})

} );
</script>
@endsection