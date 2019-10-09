@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">{{__('Total Amount of Payouts')}}</h3>
            </div>
            <div class="panel-body text-center dash-widget pad-no">
                <div class="pad-ver mar-top text-main">
                    <i class="demo-pli-wallet-2 icon-4x"></i>
                </div>
                @php
                    $status = "Outgoing Fund Transfer";
                    $balance = DB::table('wallets')->where('payment_details','=',$status)->sum('amount')
                @endphp
                <p class="text-3x text-main bg-primary pad-ver"><strong>{{single_price($balance)}}</strong></p>
            </div>
            <br>
        </div>
    </div>
</div>
<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title">{{__('Users Withdrawal Request')}}</h3>
    </div>
    <div class="panel-body">
        <table class="table table-striped table-bordered demo-dt-basic" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('User Type') }}</th>
                    <th>{{__('Amount Requested')}}</th>
                    <th>{{ __('Payment Method') }}</th>
                    <th>{{ __('Date Requested') }}</th>
                    <th>{{__('Date Released Payout')}}</th>
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
                            <td>{{ $users->user_type }}</td>
                            <td>{{ single_price($wallet->amount) }}</td>
                            <td>{{ $wallet->payment_method}}</td>
                            <td>{{ date('m-d-Y', strtotime($wallet->created_at))}}</td>
                            @if($wallet->date_payment_released == NULL)
                                <td>
                                    <div class="label label-table label-info" style="margin-left: 40px; width: 100px;" onclick="pay('{{$wallet->id}}');">
                                      {{__('Requested') }}
                                    </div>
                                </td>
                            @else
                                <td>
                                    <div class="label label-table label-success" style="margin-left: 40px; width: 100px;" onclick="pay('{{$wallet->id}}');">
                                          {{ date('m-d-Y', $wallet->date_payment_released) }}
                                    </div>
                                </td>
                            @endif
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="payment_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
        function pay(id){
            $.post('{{ route('sellers.payment_method_modal') }}',{_token:'{{ @csrf_token() }}', id:id}, function(data){
                $('#modal-content').html(data);
                $('#payment_modal').modal('show', {backdrop: 'static'});
                $('.demo-select2-placeholder').select2();
            });
        }

        $(document).ready(function(){
            $('.imgg').click(function(){
                window.open($(this)[0].src, '_blank')
            });
        });
    </script>
@endsection