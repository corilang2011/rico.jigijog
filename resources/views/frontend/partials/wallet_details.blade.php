@php
    $id = $wallet->user_id;
    $send_id = $wallet->send_to_user_id;
    
    $user =  DB::table('users')->where('id','=', $id)->get();
    $shops =  DB::table('shops')->where('user_id','=', $id)->get();
    $vendor_commission =  DB::table('business_settings')->select('value')->where('type', '=', 'vendor_commission')->get();
    
    $user_send =  DB::table('users')->where('id','=', $send_id)->get();
@endphp

@if($wallet->title == "Wallet Withdrawal")
    <div class="modal-header">
        <h5 class="modal-title strong-600 heading-5">{{__('Wallet Withdrawal Details')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
<div class="modal-body gry-bg px-3 pt-0">

    @if($wallet->title == "Wallet Withdrawal")
        <div class="pt-4" style="margin-left: 200px;">
            <ul class="process-steps clearfix">
                <li @if($wallet->payment_details == 'Processing Fund Transfer') class="done" @else class="done" @endif>
                    <div class="icon">1</div>
                    <div class="title">{{__('Request sent')}}</div>
                </li>
                <li @if($wallet->payment_details == 'Processing Fund Transfer') class="active" @else class="done" @endif>
                    <div class="icon">2</div>
                    <div class="title">{{__('Processing')}}</div>
                </li>
                <li @if($wallet->payment_details == 'Outgoing Fund Transfer') class="done" @endif>
                    <div class="icon">3</div>
                    <div class="title">{{__('Payment released')}}</div>
                </li>
            </ul>
        </div>
        <div class="row mt-3">
            <div class="offset-lg-2 col-lg-4 col-sm-6">
                <!--payment-->
            </div>
            <div class="col-lg-4 col-sm-6">
                <div class="form-inline">
                    <!--delivery status-->
                </div>
            </div>
        </div>
    @else
        <div class="pt-1"></div>
    @endif
    @if($wallet->title == "Wallet Withdrawal")
        @foreach($user as $users)
            <div class="card mt-3">
                <div class="card-header py-2 px-3 ">
                    @if($users->user_type == "seller")
                        <div class="heading-6 strong-600">{{__('Seller Details')}}</div>
                    @else
                        <div class="heading-6 strong-600">{{__('Customer Details')}}</div>
                    @endif
                </div>
                <div class="card-body pb-0">
                    <div class="row">
                        <div class="col-lg-4">
                            <table class="details-table table">
                                <tr>
                                    <td class="strong-600">{{__('Name')}}:</td>
                                    <td>{{ $users->name }}</td>
                                </tr>
                                <tr>
                                    <td class="strong-600">{{__('User Type')}}:</td>
                                    <td>{{$users->user_type}}</td>
                                </tr>
                                <tr>
                                    <td class="strong-600">{{__('Email')}}:</td>
                                    <td>{{$users->email}}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-lg-8">
                            <table class="details-table table">
                                @if($users->user_type == "seller")
                                    @foreach($shops as $shop)
                                    <tr>
                                        <td class="strong-600">{{__('Shop Name')}}:</td>
                                        <td>{{ $shop->name }}</td>
                                    </tr>
                                    @endforeach
                                @endif
                                <tr>
                                    <td class="strong-600">{{__('Address')}}:</td>
                                    <td>{{$users->address}}</td>
                                </tr>
                                <tr>
                                    <td class="strong-600">{{__('Phone Number')}}:</td>
                                    <td>{{$users->phone}}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        <div class="row">
            <div class="col-lg-12">
                <div class="card mt-3">
                    <div class="card-header py-2 px-3 ">
                        <div class="heading-6 strong-600">{{__('Request Details')}}</div>
                    </div>
                    <div class="card-body pb-0">
                        <div class="row">
                            <div class="col-lg-4">
                                <table class="details-table table">
                                    <tr>
                                        <td class="w-50 strong-600">{{__('Payment Method')}}:</td>
                                        <td>{{$wallet->payment_method}}</td>
                                    </tr>
                                    @if($wallet->payment_method == "Bank")
                                    <tr>
                                        <td class="w-50 strong-600">{{__('Bank Name')}}:</td>
                                        <td>{{$wallet->bank_name}}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 strong-600">{{__('Account Number')}}:</td>
                                        <td>{{ $wallet->card_number}}</td>
                                    </tr>
                                    @else
                                        <tr>
                                            <td class="w-50 strong-600">{{ $wallet->payment_method }}{{__(' Number')}}:</td>
                                            <td>{{ $wallet->card_number}}</td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                            <div class="col-lg-4">
                                <table class="details-table table">
                                    @if($wallet->payment_method == "Bank")
                                        <tr>
                                            <td class="w-50 strong-600">{{__('Account Name')}}:</td>
                                            <td>{{ $wallet->cardholders_name }}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td class="w-50 strong-600">{{__('Amount Requested')}}:</td>
                                        <td>{{ single_price($wallet->amount) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 strong-600">{{__('Date Requested')}}:</td>
                                        <td>{{ date('m-d-Y H:m A', strtotime($wallet->created_at))}}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-lg-4">
                                <table class="details-table table">
                                    <tr>
                                        <td class="w-50 strong-600">{{__('Date of Payment')}}:</td>
                                        @if($wallet->date_payment_released == NULL)
                                            <td>{{__('Processing') }}</td>
                                        @else
                                            <td>{{ date('m-d-Y H:m A', $wallet->date_payment_released) }}</td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <td class="w-50 strong-600">{{__('Referrence Number')}}:</td>
                                        @if($wallet->ref_number == NULL)
                                            <td>{{__('Processing') }}</td>
                                        @else
                                            <td>{{ $wallet->ref_number }}</td>
                                        @endif
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if($wallet->title == "Referral Bonus")
        @foreach($user as $users)
            <div class="row">
                <div class="col-lg-12">
                    <div class="card mt-3">
                        <div class="card-header py-2 px-3">
                            <div class="heading-6 strong-600 ">{{__('Referral Bonus Details')}}</div>
                        </div>
                        <div class="card-body pb-0">
                            <div class="row">
                                <div class="col-lg-4">
                                    <table class="details-table table">
                                        <tr>
                                            <td class="w-50 strong-600">{{__('Name')}} :</td>
                                            <td>{{ $users->name }}</td>
                                        </tr>
                                        
                                        <tr>
                                            <td class="w-50 strong-600">{{__('Email')}} :</td>
                                            <td>{{$users->email}}</td>
                                        </tr>
                                        <tr>
                                            <td class="strong-600">{{__('Phone #')}} :</td>
                                            <td>{{ $users->phone }}</td>
                                        </tr>
                                        
                                    </table>
                                </div>
                                <div class="col-lg-8">
                                    <table class="details-table table">
                                        @if($users->user_type == "seller")
                                            @foreach($shops as $shop)
                                            <tr>
                                                <td class="strong-600">{{__('Shop Name')}} :</td>
                                                <td>{{ $shop->name }}</td>
                                            </tr>
                                            @endforeach
                                        @endif
                                        <tr>
                                            <td class="strong-600">{{__('Address')}} :</td>
                                            <td>{{$users->address}}</td>
                                        </tr>
                                        <tr>
                                            <td class="strong-600">{{__('Rewards Claimed')}} :</td>
                                            <td>{{ single_price($wallet->amount) }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
    @if($wallet->title == "Products Sold")
        @php
            $order = \App\Order::findOrFail($wallet->w_order_id);
            $status = $order->orderDetails->first()->delivery_status;
            $payment_status = $order->orderDetails->first()->payment_status;
        @endphp
        <div class="row">
            <div class="col-lg-12">
                <div class="card mt-3">
                    <div class="card-header py-2 px-3">
                        <div class="heading-6 strong-600 ">{{__('Customer Details')}}</div>
                    </div>
                    <div class="card-body pb-0">
                        <div class="row">
                            <div class="col-lg-4">
                                <table class="details-table table">
                                    <tr>
                                        <td class="w-50 strong-600">{{__('Order Code')}} :</td>
                                        <td>{{ $order->code }}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 strong-600">{{__('Name')}} :</td>
                                        <td>{{ json_decode($order->shipping_address)->name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 strong-600">{{__('Email')}} :</td>
                                        @if ($order->user_id != null)
                                            <td>{{ $order->user->email }}</td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <td class="w-50 strong-600">{{__('Order date')}} :</td>
                                        <td>{{ date('d-m-Y H:m A', $order->date) }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-lg-8">
                                <table class="details-table table">
                                    <tr>
                                        <td class="strong-600">{{__('Payment Status')}} :</td>
                                        <td>{{ $payment_status }}</td>
                                    </tr>
                                    <tr>
                                        <td class="strong-600">{{__('Order status')}} :</td>
                                        <td>{{ $status }}</td>
                                    </tr>
                                    <tr>
                                        <td class="strong-600">{{__('Payment method')}} :</td>
                                        <td>{{ ucfirst(str_replace('_', ' ', $order->payment_type)) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="strong-600">{{__('Shipping address')}} :</td>
                                        <td style="padding-left: -100px;">{{ json_decode($order->shipping_address)->address }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @foreach($user as $users)
            <div class="row">
                <div class="col-lg-9">
                    <div class="card mt-3">
                        <div class="card-header py-2 px-3">
                            <div class="heading-6 strong-600 ">{{__('Order Details')}}</div>
                        </div>
                        <div class="card-body pb-0">
                            <table class="details-table table">
                                <thead>
                                    <tr>
                                        <th width="40%">{{__('Product')}}</th>
                                        <th>{{__('Variation')}}</th>
                                        <th>{{__('Product Price')}}</th>
                                        <th>{{__('Quantity')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->orderDetails->where('id', $wallet->w_order_detail_id) as $key => $orderDetail)
                                        <tr>
                                            <td><a href="{{ route('product', $orderDetail->product->slug) }}" target="_blank">{{ $orderDetail->product->name }}</a></td>
                                            <td style="padding-left: 10px;">
                                                {{ $orderDetail->variation }}
                                            </td>
                                            <td  style="padding-left: 25px;">{{ single_price($orderDetail->price/$orderDetail->quantity) }}</td>
                                            <td style="padding-left: 30px;">
                                                {{ $orderDetail->quantity }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="card mt-3">
                        <div class="card-header py-2 px-3 heading-6 strong-600">{{__('Order Amount')}}</div>
                        <div class="card-body pb-0">
                            <table class="table details-table">
                                <tbody>
                                    <tr>
                                        <th class="strong-600">{{__('Subtotal')}} :</th>
                                        <td>{{ single_price($wallet->prod_orig_price) }}</td>
                                    </tr>
                                    @foreach($vendor_commission as $commission)
                                        <tr>
                                            <th class="strong-600">{{__('Jigijog Fee')}} :</th>
                                            <td class="text-right">
                                                <span class="text-italic">{{ $commission->value }}%</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <th>{{__('Total')}} :</th>
                                        <td class="text-right">
                                            <strong>
                                                <span>{{single_price($wallet->amount)}}</span>
                                            </strong>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
    
    
    @if($wallet->title != "Products Sold" && $wallet->title != "Referral Bonus"  && $wallet->title != "Wallet Withdrawal")
        @if($wallet->send_to_user_id == Auth::user()->id)
            @foreach($user as $users)
                <div class="card mt-3">
                    <div class="card-header py-2 px-3 ">
                        <div class="heading-6 strong-600">{{__('Sent by : ')}}</div>
                    </div>
                    <div class="card-body pb-0">
                        <div class="row">
                            <div class="col-lg-4">
                                <table class="details-table table">
                                    <tr>
                                        <td class="strong-600">{{__('Name')}}:</td>
                                        <td>{{ $users->name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="strong-600">{{__('Email')}}:</td>
                                        <td>{{$users->email}}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-lg-8">
                                <table class="details-table table">
                                    @if($users->user_type == "seller")
                                        @foreach($shops as $shop)
                                        <tr>
                                            <td class="strong-600">{{__('Shop Name')}}:</td>
                                            <td>{{ $shop->name }}</td>
                                        </tr>
                                        @endforeach
                                    @endif
                                    <tr>
                                        <td class="strong-600">{{__('Address')}}:</td>
                                        <td>{{$users->address}}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-9">
                        <div class="card mt-3">
                            <div class="card-header py-2 px-3">
                                <div class="heading-6 strong-600 ">{{__('Detailed Description')}}</div>
                            </div>
                            <div class="card-body pb-0">
                                <table class="details-table table">
                                    <tbody>                                        
                                        <tr>
                                                <td>{{$wallet->payment_details}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="card mt-3">
                            <div class="card-header py-2 px-3 heading-6 strong-600">{{__('Transfer Amount')}}</div>
                            <div class="card-body pb-0">
                                <table class="table details-table">
                                    <tbody>
                                        <tr class="text-center">
                                            <td>{{ single_price($wallet->amount) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            @foreach($user_send as $users)
                <div class="card mt-3">
                    <div class="card-header py-2 px-3 ">
                        <div class="heading-6 strong-600">{{__('Received by : ')}}</div>
                    </div>
                    <div class="card-body pb-0">
                        <div class="row">
                            <div class="col-lg-4">
                                <table class="details-table table">
                                    <tr>
                                        <td class="strong-600">{{__('Name')}}:</td>
                                        <td>{{ $users->name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="strong-600">{{__('Email')}}:</td>
                                        <td>{{$users->email}}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-lg-8">
                                <table class="details-table table">
                                    @if($users->user_type == "seller")
                                        @foreach($shops as $shop)
                                        <tr>
                                            <td class="strong-600">{{__('Shop Name')}}:</td>
                                            <td>{{ $shop->name }}</td>
                                        </tr>
                                        @endforeach
                                    @endif
                                    <tr>
                                        <td class="strong-600">{{__('Address')}}:</td>
                                        <td>{{$users->address}}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-9">
                        <div class="card mt-3">
                            <div class="card-header py-2 px-3">
                                <div class="heading-6 strong-600 ">{{__('Detailed Description')}}</div>
                            </div>
                            <div class="card-body pb-0">
                                <table class="details-table table">
                                    <tbody>                                        
                                        <tr>
                                                <td>{{$wallet->payment_details}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="card mt-3">
                            <div class="card-header py-2 px-3 heading-6 strong-600">{{__('Transfer Amount')}}</div>
                            <div class="card-body pb-0">
                                <table class="table details-table">
                                    <tbody>
                                        <tr class="text-center">
                                            <td>{{ single_price($wallet->amount) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    @endif
</div>

