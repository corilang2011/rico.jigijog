@extends('layouts.app')

@section('content')

    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">{{__('Seller Referral Program')}}</h3>
        </div>
        <div class="panel-body">
            <table class="table table-striped table-bordered demo-dt-basic" cellspacing="0" width="100%" id="example">
                <thead>
                    <tr>
                        <th>{{__('Referral Link')}}</th>
                        <th>{{ __('Seller name') }}</th>
                        <th>{{__('Email')}}</th>
                        <th>{{__('Email Status')}}</th>
                        <th>{{__('ID Status')}}</th>
                        <th>{{__('Referred Users')}}</th>
                        <th>{{__('Balance')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                            @php
                                $user_id = $user->id;
                                $user->referred =  DB::table('users')->select('id', 'name', 'referred_by')->where('referred_by','LIKE',"%{$user_id}%")->get();
                            @endphp
                            @if($user->user_type == 'customer')
                                @if(count($user->referred) > 0)
                                    <tr onclick="show_seller_payment_modal_form('{{$user->id}}');" style="cursor: pointer;">
                                        <td>{{url('/').'/?ref='.\Hashids::encode($user->id)}}</td>
                                        <td>{{$user->name}}</td>
                                        <td>{{$user->email}}</td>
                                        @if($user->email_verified_at != NULL)
                                           <td>
                                                <div class="label label-table label-success" style="margin-left: 20px;" >
                                                     Verified
                                                </div>
                                            </td>
                                        @else
                                            <td>
                                                <div class="label label-table label-danger" style="margin-left: 20px;" >
                                                     Not Verified
                                                </div>
                                            </td>
                                        @endif
                                        @if($user->ref_id_status != NULL)
                                           <td>
                                                <div class="label label-table label-success" style="margin-left: 20px;" >
                                                     Approved
                                                </div>
                                            </td>
                                        @else
                                            <td>
                                                <div class="label label-table label-danger" style="margin-left: 20px;" >
                                                     Pending
                                                </div>
                                            </td>
                                        @endif
                                        <td>
                                            <div class="label label-table label-info" style="margin-left: 40px; width: 50px;">
                                              {{ count($user->referred) }}
                                            </div>
                                        </td>
                                        <td style="margin-left: 200px;">{{ single_price($user->balance)}}</td>
                                    </tr>
                                @endif
                            @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="customer_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-zoom product-modal" id="modal-size" role="document" style="width: 1100px;">
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
        function show_seller_payment_modal_form(id){
            $.post('{{ route('sellers.payment_modal_form') }}',{_token:'{{ @csrf_token() }}', id:id}, function(data){
                $('#modal-content').html(data);
                $('#customer_modal').modal('show', {backdrop: 'static'});
                $('.demo-select2-placeholder').select2();
            });
        }
    </script>
@endsection
