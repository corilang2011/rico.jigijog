@extends('layouts.app')

@section('content')
<style type="text/css">
a:link {
  color: blue;
}

a:visited {
  color: blue;
}

a:hover {
  color: hotpink;
}

a:active {
  color: red;
}

label{
    margin-left: 20px;
}
.inp {
  cursor: pointer;
}
#datepicker{
    width:180px; margin: 0 20px 20px 20px;
}
#datepicker > span:hover{
    cursor: pointer;
}

</style>

<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title">{{__('Upload Receipts')}}</h3>
    </div>
    <div class="panel-body">
        <table class="table table-striped table-bordered demo-dt-basic" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>{{ __('Name') }}</th>
                    {{-- <th>{{ __('User Type') }}</th> --}}
                    <th>{{__('Amount Requested')}}</th>
                    {{-- <th>{{ __('Payment Method') }}</th> --}}
                    <th>{{ __('Date Requested') }}</th>
                    <th>{{__('Date Released Payout')}}</th>
                    <th>{{__('Action')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($wallets as $wallet)
                    @php
                        $id = $wallet->user_id;
                        $wallet->user =  DB::table('users')->where('id','=', $id)->get();
                    @endphp
                    <tr>
                        @foreach ($wallet->user as  $users)
                            <td>{{ $users->name }}</td>
                            {{-- <td>{{ $users->user_type }}</td> --}}
                            <td>{{ single_price($wallet->amount) }}</td>
                            {{-- <td>{{ $wallet->payment_method}}</td> --}}
                            <td>{{ date('m-d-Y', strtotime($wallet->created_at))}}</td>
                            @if($wallet->date_payment_released == NULL)
                                <td>
                                    <div class="label label-table label-info" style="margin-left: 40px; width: 100px;">
                                      {{__('Requested') }}
                                    </div>
                                </td>
                            @else
                                <td>
                                    <div class="label label-table label-success" style="margin-left: 40px; width: 100px;">
                                          {{ date('m-d-Y', $wallet->date_payment_released) }}
                                    </div>
                                </td>
                            @endif
                            <td>
                                @if($wallet->date_of_trans != null)
                                    <span>Done uploading</span>
                                @else
                                    <a href="#" onclick="pay('{{$wallet->id}}');">Upload Receipt</a>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- <div class="modal fade" id="payment_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-zoom product-modal" id="modal-size" role="document">
        <div class="modal-content position-relative">
            <div class="modal-body p-4 added-to-cart">
                <div id="modal-content"></div>
            </div>
        </div>
    </div>
</div> --}}

    <div class="modal fade" id="payment_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-zoom product-modal" id="modal-size" role="document">
            <div class="modal-content position-relative">
                <div class="modal-header">
                    <h5 class="modal-title strong-600 heading-5">{{__('Please upload the bank receipt')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="" action="{{ route('receipt.upload') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" id="id" required>
                    <label>Select Date of Transaction: </label>
                    <div id="datepicker" class="input-group date" data-date-format="mm-dd-yyyy">
                        <input type="text" class="form-control inp" name="DoT" readonly/>
                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                    </div>
                    <div class="modal-body gry-bg px-3 pt-3">
                        <div class="form-group">
                            <div class="col-lg-12">
                                <br>
                                <div id="id_image">
    
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" >{{__('cancel')}}</button>
                        <button type="submit" class="btn btn-base-1">{{__('Confirm')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        function pay(id){
            $('#id').val(id);
            $('#payment_modal').modal('show', {backdrop: 'static'});
        }

        $(document).ready(function(){
            $("#id_image").spartanMultiImagePicker({
                fieldName:        'id_image[]',
                maxCount:         1,
                rowHeight:        '200px',
                groupClassName:   'col-md-12 col-sm-9 col-xs-6',
                maxFileSize:      '',
                dropFileLabel : "Drop Here",
                onExtensionErr : function(index, file){
                    console.log(index, file,  'extension err');
                    alert('Please only input png or jpg type file')
                },
                onSizeErr : function(index, file){
                    console.log(index, file,  'file size too big');
                    alert('File size too big');
                },
                
                
            });

            $(function () {
              $("#datepicker").datepicker({ 
                    autoclose: true, 
                    todayHighlight: true
              }).datepicker();
            });
        });
    </script>
@endsection