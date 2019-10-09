@extends('layouts.app')

@section('content')

    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">{{__('ID Verification')}}</h3>
        </div>
        <div class="panel-body">
            <table class="table table-striped table-bordered demo-dt-basic" cellspacing="0" width="100%" id="example">
                <thead>
                    <tr>
                        <th>{{ __('Name') }}</th>
                        <th>{{__('Email')}}</th>
                        <th>{{__('User Type')}}</th>
                        <th>{{__('Email Date Veried')}}</th>
                        <th>{{__('ID Status')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                            @if($user->ref_id_status != NULL && $user->ref_id_status != 'Declined')
                                <tr onclick="show_seller_payment_modal('{{$user->id}}');" style="cursor: pointer;">
                                    <td>{{$user->name}}</td>
                                    <td>{{$user->email}}</td>
                                    <td class="text-center">
                                        <div class="label label-default" style="width: 120px; font-size: 1em; margin-left: 30px;" >
                                             {{$user->user_type}}
                                        </div>
                                    </td>
                                    <td>{{ date('m-d-Y', strtotime($user->email_verified_at)) }}</td>
                                    @if($user->ref_id_status == 'Approved')
                                    <td>
                                        <div class="label label-success" style="width: 120px; font-size: 1em; margin-left: 30px;" >
                                             Approved
                                        </div>
                                    </td>
                                    @elseif($user->ref_id_status == 'Requested')
                                        <td>
                                            <div class="label label-info" style="width: 120px; font-size: 1em; margin-left: 30px;" >
                                                 Requested
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="id_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-zoom product-modal" id="modal-size" role="document">
            <div class="modal-content position-relative">
                <div class="modal-body p-4 added-to-cart">
                    <div id="modal-content"></div>
                </div>
            </div>
        </div>
    </div>


@endsection
@section('script')
    <script type="text/javascript">
        function show_seller_payment_modal(id){
            $.post('{{ route('sellers.id_modal') }}',{_token:'{{ @csrf_token() }}', id:id}, function(data){
                $('#modal-content').html(data);
                $('#id_modal').modal('show', {backdrop: 'static'});
                $('.demo-select2-placeholder').select2();
            });
        }
    </script>
@endsection
