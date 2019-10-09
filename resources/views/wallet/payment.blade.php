<style type="text/css">
    .imgg{
        cursor:pointer;
    }
</style>

<form class="form-horizontal" action="{{ route('wallets.payments') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">{{__('Payment Details')}}</h4>
    </div>
    @php
        $id = $wallet->user_id;
        $user =  DB::table('users')->where('id','=', $id)->get();
        $shops =  DB::table('shops')->where('user_id','=', $id)->get();
        $products =  DB::table('products')->where('user_id','LIKE',"%{$wallet->user_id}%")->get();
        $apprv_sales =  DB::table('products')->where([['user_id','LIKE',"%{$wallet->user_id}%"], ['num_of_sale', '>', 0]])->get();
        $vendor_commission =  DB::table('business_settings')->select('value')->where('type', '=', 'vendor_commission')->get();
    @endphp
    <div class="panel-body">
        <table class="table table-striped table-bordered demo-dt-basic" cellspacing="0" width="100%" id="example">
            <tbody>
                @foreach($user as $users)
                    <tr>
                        <td width="60%">{{__('Name')}}</td>
                        <td class="text-center">{{ $users->name }}</td>
                    </tr>
                    <tr>
                        <td>{{__('User Type')}}</td>
                        <td class="text-center">{{ $users->user_type }}</td>
                    </tr>
                    @if($users->user_type == "seller")
                        @foreach($shops as $shop)
                            <tr>
                                <td>{{__('Shop Name')}}</td>
                                <td class="text-center">{{ $shop->name }}</td>
                            </tr>
                        @endforeach
                    @endif
                    <tr>
                        <td>{{__('Address')}}</td>
                        <td class="text-center">{{ $users->address }}</td>
                    </tr>
                    <tr>
                        <td>{{__('Phone Number')}}</td>
                        <td class="text-center">{{ $users->phone }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @foreach($user as $users)
            @if($users->user_type == "seller")
                <table class="table table-striped table-bordered demo-dt-basic" cellspacing="0" width="100%" id="example">
                    <tbody>
                            <tr>
                                <td width="60%">{{__('Total Products Uploaded')}}</td>
                                <td class="text-center">{{ count($products) }}</td>
                            </tr>
                            <tr>
                                <td>{{__('Total Approved Sales')}}</td>
                                <td class="text-center">{{ count($apprv_sales) }}</td>
                            </tr>
                            @foreach($vendor_commission as $commission)
                                <tr>
                                    <td>{{__('Seller Commission')}}</td>
                                    <td class="text-center">{{ $commission->value }}%</td>
                                </tr>
                            @endforeach
                    </tbody>
                </table>
            @endif
        @endforeach
        <table class="table table-striped table-bordered demo-dt-basic" cellspacing="0" width="100%" id="example">
            <tbody>
                <input type="text" class="val" name="user_id" value="{{ $wallet->user_id }}" hidden>
                <input type="text" class="val" name="amount_requested" value="{{ $wallet->amount }}" hidden>
                @foreach($user as $users)
                    <tr>
                        <td width="60%">{{__('Payment Method')}}</td>
                        <td class="text-center">{{ $wallet->payment_method }}</td>
                    </tr>
                    @if($wallet->payment_method == "Bank")
                        <tr>
                            <td width="60%">{{__('Bank Name')}}</td>
                            <td class="text-center">{{ $wallet->bank_name }}</td>
                        </tr>
                    @endif
                    @if($wallet->payment_method == "Bank")
                        <tr>
                            <td width="60%">{{__('Card Number')}}</td>
                            <td class="text-center">{{ $wallet->card_number }}</td>
                        </tr>
                        <tr>
                            <td width="60%">{{__('Account Name')}}</td>
                            <td class="text-center">{{ $wallet->cardholders_name }}</td>
                        </tr>
                    @else
                        <tr>
                            <td width="60%">{{$wallet->payment_method}} {{__('Number')}}</td>
                            <td class="text-center">{{ $wallet->card_number }}</td>
                        </tr>
                    @endif
                    <tr>
                        <td>{{__('Date Requested')}}</td>
                        <td class="text-center">{{ date('m-d-Y', strtotime($wallet->created_at)) }}</td>
                    </tr>
                    @if($wallet->payment_details == "Processing Fund Transfer")
                        <tr>
                            <td>{{__('Total Wallet Balance')}}</td>
                            <td class="text-center">{{ single_price($users->balance) }}</td>
                        </tr>
                    @endif
                    <tr>
                        <td>{{__('Amount Requested to Payout')}}</td>
                        <td class="text-center">{{ single_price($wallet->amount) }}</td>
                    </tr>
                    @if($wallet->payment_details == "Processing Fund Transfer")
                        <tr>
                            <td style="padding-top: 15px;">{{__('Referrence Number')}} <i style="color: red;">*</i></td>
                            <td class="text-center"><input type="text" class="form-control mb-3" name="ref_number" required  style="border: 2px solid #007bff;"></td>
                        </tr>
                    @endif
                    @if($wallet->receipt != null)
                        @php
                            $img = json_decode($wallet->receipt);
                            $photo = $img[0];
                        @endphp
                        <tr>
                            <td style="padding-top: 15px;">{{__('Bank Receipt')}}</td>
                            <td class="text-center">
                                <img class="xzoom-gallery imgg" style="border-radius: 10px;" width="300" src="{{ asset($photo) }}" xpreview="{{ asset($photo) }}">
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-top: 15px;">{{__('Date of Transcation')}}</td>
                            <td class="text-center">{{ $wallet->date_of_trans }}</td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
    @if($wallet->payment_details == "Processing Fund Transfer")
        <div class="modal-footer">
            <div class="panel-footer text-right">
                <button class="btn btn-purple" type="submit">{{__('Pay')}}</button>
                <button class="btn btn-default" data-dismiss="modal">{{__('Cancel')}}</button>
            </div>
        </div>
    @endif
</form>



<script type="text/javascript">
    $(document).ready(function(){
        $('.imgg').click(function(){
            window.open($(this)[0].src, '_blank')
        });
    });
</script>
