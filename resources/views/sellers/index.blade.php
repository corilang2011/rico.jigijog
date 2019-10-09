@extends('layouts.app')

@section('content')
@php
    $count = count(DB::table('sellers')->get());
@endphp
<div class="row">
    <div class="col-sm-12">
        <a href="{{ route('sellers.create')}}" class="btn btn-rounded btn-info pull-right">{{__('Add New Seller')}}</a>
    </div>
</div>

<br>

<!-- Basic Data Tables -->
<!--===================================================-->
<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title">{{__('Sellers')}}</h3>
    </div>
    <div class="row">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <span class="cent">No of sellers: <strong style="background-color: #DCDCDC; padding: 6px;">{{ $count }}</strong></span>
    </div>
    <div class="panel-body">
        <table class="table table-striped table-bordered demo-dt-basic" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{__('Name')}}</th>
                    <th>{{__('Shop Name')}}</th>
                    <th>{{__('Email Address')}}</th>
                    <th width="270px">{{__('Address')}}</th>
                    <th>{{__('Phone #')}}</th>
                    <th width="80px">{{__('Status')}}</th>
                    <th width="30px">{{ __('Num. of Products') }}</th>
                    <th>{{ __('Wallet Balance') }}</th>
                    {{-- <th>{{ __('Due to seller') }}</th> --}}
                    {{-- <th width="10%">{{__('Options')}}</th> --}}
                </tr>
            </thead>
            <tbody>
                @foreach($sellers as $key => $seller)
                    @php
                        $shops =  DB::table('shops')->where('user_id','=', $seller->user_id)->get();
                    @endphp
                    <tr>
                        <td>{{$key+1}}</td>
                        <td class="text-center">{{$seller->user->name}}</td>
                        @foreach($shops as $shop)
                            <td class="text-center">{{ $shop->name }}</td>
                        @endforeach
                        <td class="text-center">{{$seller->user->email}}</td>
                        <td class="text-center">{{$seller->user->address}}</td>
                        <td class="text-center">{{$seller->user->phone}}</td>
                        <td class="text-center">
                            @if ($seller->verification_status == 1)
                                <div class="label label-table label-success">
                                    {{__('Verified')}}
                                </div>
                            @elseif ($seller->verification_info != null)
                                <a href="{{ route('sellers.show_verification_request', $seller->id) }}">
                                    <div class="label label-table label-info">
                                        {{__('Requested')}}
                                    </div>
                                </a>
                            @else
                                <div class="label label-table label-danger">
                                    {{__('Unverified')}}
                                </div>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="badge" style="background-color: #007bff">
                                {{\App\Product::where('user_id', $seller->user->id)->count()}}</td>
                            </span>
                            
                        <td class="text-center">
                            <div class="label label-table label-success">
                                {{ single_price($seller->user->balance) }}
                            </div>
                        </td>


                        <!--<td>-->
                        <!--    @if ($seller->admin_to_pay > 0)-->
                        <!--        {{ single_price($seller->admin_to_pay) }}-->
                        <!--    @else-->
                        <!--        {{ single_price(0) }}-->
                        <!--    @endif-->
                        <!--</td>-->
                        {{-- <td>
                            <div class="btn-group dropdown">
                                <button class="btn btn-primary dropdown-toggle dropdown-toggle-icon" data-toggle="dropdown" type="button">
                                    {{__('Actions')}} <i class="dropdown-caret"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a onclick="show_seller_payment_modal('{{$seller->id}}');">{{__('Pay')}}</a></li>
                                    <li><a href="{{route('sellers.edit', encrypt($seller->id))}}">{{__('Edit')}}</a></li>
                                    <li><a onclick="confirm_modal('{{route('sellers.destroy', $seller->id)}}');">{{__('Delete')}}</a></li>
                                </ul>
                            </div>
                        </td> --}}
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>


<div class="modal fade" id="payment_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" id="modal-content">

        </div>
    </div>
</div>


@endsection

@section('script')
    <script type="text/javascript">
        function show_seller_payment_modal(id){
            $.post('{{ route('sellers.payment_modal') }}',{_token:'{{ @csrf_token() }}', id:id}, function(data){
                $('#modal-content').html(data);
                $('#payment_modal').modal('show', {backdrop: 'static'});
                $('.demo-select2-placeholder').select2();
            });
        }
    </script>
@endsection
