
<div class="panel">
    <div class="panel-heading">

        <h3 class="panel-heading h3">{{__('ID Verification')}}</h3>
    </div>
    <div class="panel-body">
        @php
            $id =  DB::table('referrals')->where([['user_id','LIKE',"%{$users->id}%"], ['id_image', '!=' , NULL]])->get();
            $shops =  DB::table('shops')->where('user_id','=', $users->id)->get();
            $sellers =  DB::table('sellers')->where('user_id','=', $users->id)->get();
            $referred =  DB::table('users')->where('referred_by','LIKE', "%{$users->id}%")->count();
        @endphp

        @foreach($id as $ayde)
            @if($ayde->id_status == "Declined")
                <img src="{{ asset('frontend/images/icons/non_verified.png') }}" alt="" width="70" style="position: absolute; margin-left: 30px;">
                @if(is_array(json_decode($ayde->id_image)) && count(json_decode($ayde->id_image)) > 1)
                    <img src="{{ asset('frontend/images/icons/non_verified.png') }}" alt="" width="70" style="position: absolute; margin-left: 485px;">
                @endif
            @endif
            @if($ayde->id_status == "Approved")
                <img src="{{ asset('frontend/images/icons/verified.png') }}" alt="" width="70" style="position: absolute; margin-left: 30px;">
                @if(is_array(json_decode($ayde->id_image)) && count(json_decode($ayde->id_image)) > 1)  
                    <img src="{{ asset('frontend/images/icons/verified.png') }}" alt="" width="70" style="position: absolute; margin-left: 485px;">
                @endif
            @endif
            @if(is_array(json_decode($ayde->id_image)) && count(json_decode($ayde->id_image)) > 0)  
                <div class="product-gal-thumb">
                    <div style="margin-left: -120px;">
                        @foreach (json_decode($ayde->id_image) as $key => $photo)
                            <a href="{{ asset($photo) }}" style="margin-left: 150px;">
                                <img class="xzoom-gallery" style="border: 1px solid #fd7e14; border-radius: 10px;" width="300" src="{{ asset($photo) }}"  @if($key == 0) xpreview="{{ asset($photo) }}" @endif>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
            <br>
            @if($ayde->id_status != 'Declined')
                <table class="table table-striped table-bordered demo-dt-basic" cellspacing="0" width="100%" id="example">
                    <div class="panel-heading">
                        <h3 class="text-lg">{{__('User Info')}}</h3>
                    </div>
                    <tbody>
                            <tr>
                                <td width="50%">{{__('Name')}}</td>
                                <td class="text-center">{{ $users->name }}</td>
                            </tr>
                            <tr>
                                <td>{{__('Email')}}</td>
                                <td class="text-center">{{ $users->email }}</td>
                            </tr>
                            <tr>
                                <td>{{__('User Type')}}</td>
                                <td class="text-center">{{ $users->user_type }}</td>
                            </tr>
                            <tr>
                                <td>{{__('Address')}}</td>
                                @if($users->address == NULL)
                                    <td class="text-center">None</td>
                                @else
                                    <td class="text-center">{{ $users->address }}</td>
                                @endif
                            </tr>
                            <tr>
                                <td>{{__('Phone Number')}}</td>
                                <td class="text-center">{{ $users->phone }}</td>
                            </tr>
                    </tbody>
                </table>
                @if($users->user_type == "seller")
                    <table class="table table-striped table-bordered demo-dt-basic" cellspacing="0" width="100%" id="example">
                        <div class="panel-heading">
                            <h3 class="text-lg">{{__('Shop Info')}}</h3>
                        </div>
                        <tbody>
                            @foreach($shops as $shop)
                                <tr>
                                    <td width="50%">{{__('Shop Name')}}</td>
                                    <td class="text-center">{{ $shop->name }}</td>
                                </tr>
                                <tr>
                                    <td>{{__('Shop Adress')}}</td>
                                    @if($users->address == NULL)
                                        <td class="text-center">None</td>
                                    @else
                                        <td class="text-center">{{ $shop->address }}</td>
                                    @endif
                                </tr>
                            @endforeach
                            @foreach($sellers as $seller)
                                <tr>
                                    <td width="50%">{{__('Shop Status')}}</td>
                                    @if($seller->verification_status == 1)
                                        <td class="text-center">Verified Seller</td>
                                    @else
                                        <td class="text-center">Not Verified Seller</td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
                <table class="table table-striped table-bordered demo-dt-basic" cellspacing="0" width="100%" id="example">
                    <div class="panel-heading">
                        <h3 class="text-lg">{{__('ID Details')}}</h3>
                    </div>
                    <tbody>
                            <tr>
                                <td width="50%">{{__('ID Type')}}</td>
                                <td class="text-center">{{ $ayde->id_type }}</td>
                            </tr>
                            <tr>
                                <td width="50%">{{__('ID Number')}} </td>
                                <td class="text-center">{{ $ayde->id_number }}</td>
                            </tr>
                    </tbody>
                </table>
                <table class="table table-striped table-bordered demo-dt-basic" cellspacing="0" width="100%" id="example">
                    <div class="panel-heading">
                        <h3 class="text-lg">{{__('Referral Info')}}</h3>
                    </div>
                    <tbody>
                            <tr>
                                <td width="50%">{{__('Referred Users')}}</td>
                                <td class="text-center">{{ $referred }}</td>
                            </tr>
                            <tr>
                                <td width="50%">{{__('Rewards to be claim')}} </td>
                                @if($ayde->id_status == "Approved") 
                                    <td class="text-center">
                                        <div class="label label-success" style="width: 120px; font-size: 1em; margin-left: 30px;" > 
                                            Claimed
                                        </div>
                                    </td>
                                @else
                                    <td class="text-center">{{ single_price($ayde->reward_points) }}</td>
                                @endif
                                
                            </tr>
                    </tbody>
                </table>
            @endif
        @endforeach
        @if($ayde->id_status != "Approved")
            <div class="panel-body">
                <div style="margin-left: 330px;">
                    <a href="{{ route('referral_program.reject', $users->id) }}" class="btn btn-danger d-innline-block">{{__('Reject')}}</a></li>
                    <a href="{{ route('referral_program.approve', $users->id, $users->points) }}" class="btn btn-success d-innline-block">{{__('Accept')}}</a>
                </div>
            </div>
        @endif
    </div>
</div>