@extends('frontend.layouts.app')
<title>JigiJog | Referral Program</title>
@section('content')
    <section class="gry-bg py-4 profile">
        <div class="container">
            <div class="row cols-xs-space cols-sm-space cols-md-space">
                <div class="col-lg-3 d-none d-lg-block">
                    @if(Auth::user()->user_type == 'seller')
                        @include('frontend.inc.seller_side_nav')
                    @elseif(Auth::user()->user_type == 'customer')
                        @include('frontend.inc.customer_side_nav')
                    @endif
                </div>
                @php
                    $user_id = auth()->user()->id;
                    $counter = DB::table('referrals')->select('id_status')->where('user_id','LIKE',"%{$user_id}%")->count()
                @endphp
                <div class="col-lg-9">
                    <div class="main-content">
                        <!-- Page title -->
                        <div class="page-title">
                            <div class="row align-items-center">
                                <div class="col-md-6 mb-4">
                                    <h2 class="heading heading-5 text-capitalize strong-600 mb-0 d-inline-block">
                                        {{__('Referral Program')}}
                                    </h2>
                                </div>
                                <div class="col-md-6">
                                    <div class="float-md-right">
                                        <ul class="breadcrumb">
                                            <li><a href="{{ route('home') }}">{{__('Home')}}</a></li>
                                            <li><a href="{{ route('dashboard') }}">{{__('Dashboard')}}</a></li>
                                            <li><a href="{{ route('referrals.index') }}">{{__('Referral Program')}}</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1" style="margin-left: 145px; margin-top: -70px;">
                                <div class="mb-3">
                                    @if($counter > 0 )
                                        @php
                                            $user_id = auth()->user()->id;
                                            $referrals =  DB::table('referrals')->select('user_id','id_status')->where('user_id', '=', $user_id)->orderBy('created_at', 'desc')->first();
                                        @endphp
                                        @foreach($referrals as $referral)
                                            @if($referral == "Approved")
                                                <img src="{{ asset('frontend/images/icons/verified.png') }}" alt="" width="60">
                                            @endif
                                            @if($referral == "Requested")
                                                <img src="{{ asset('frontend/images/icons/non_verified.png') }}" alt="" width="60">
                                            @endif
                                            @if($referral == "Declined")
                                                <img src="{{ asset('frontend/images/icons/non_verified.png') }}" alt="" width="60">
                                            @endif
                                            
                                        @endforeach
                                    @else
                                        <img src="{{ asset('frontend/images/icons/non_verified.png') }}" alt="" width="60">
                                    @endif
                                </div>
                            </div>
                            @php
                                $refer_id_status = DB::table('users')->where('id','=', auth()->user()->id)->get();
                                foreach ($refer_id_status as $key => $refer) {
                                    
                                    
                                    if($refer->referred_by == NULL && $refer->ref_id_status == NULL){
                                        $total = 0;
                                    }
                                    if($refer->referred_by == NULL && $refer->ref_id_status != NULL){
                                        $total = 0;
                                    }
                                    if($refer->referred_by != NULL && $refer->ref_id_status == NULL){
                                        $total = 50;
                                    }
                                    if($refer->referred_by != NULL && $refer->ref_id_status != NULL){
                                        $total = 0;
                                    }
                                }
                                
                                foreach ($users as $key => $user) {
                                    
                                    if($user->refer_points != 0 && $user->email_verified_at != NULL && $user->ref_id_status == "Approved"){
                                        $total += 50;
                                    }
                                }
                            @endphp
                            <div class="row mb-4">
                                <div class="col-md-3 col-6">
                                    <div class="dashboard-widget text-center gray-widget mt-4 c-pointer">
                                        <a onclick="show_promo_modal()" class="text-white d-block">
                                            <i class="fa fa-check-square-o"></i>
                                            <span class="d-block title text-orange heading-6 strong-400" >{{__('Referral Terms & Condition')}}</span>
                                        </a>
                                    </div>
                                </div>
                                 
                                <div class="col-md-6 col-6" id="clipboardlink" data-clipboard-target="#ref_link"  ddata-toggle="tooltip" data-placement="bottom" title="Click to copy link">
                                    <div class="dashboard-widget text-center blue-widget mt-4 c-pointer">
                                        <a class="d-block text-white">
                                            <i class="fa fa-users"></i>
                                            <span id="ref_link" class="d-block title heading-6 strong-400 mb-1">{{url('/').'/users/registration?ref='.\Hashids::encode(auth()->user()->id)}}</span>
                                            <span class="d-block sub-title">{{__('Referral Link')}}</span>
                                        </a>
                                    </div>
                                </div>
                                @if($counter > 0 )
                                    @php
                                        $user_id = auth()->user()->id;
                                        $id_status = "Requested";
                                        $id_submit_count = DB::table('referrals')->select('id_status')->where([['id_status','=',$id_status], ['user_id','LIKE',"%{$user_id}%"]])->count()
                                    @endphp
                                @endif
                                
                                @if($counter > 0 )
                                    @php
                                        $user_id = auth()->user()->id;
                                        
                                        $referrals =  DB::table('referrals')->select('user_id','id_status')->where('user_id', '=', $user_id)->orderBy('created_at', 'desc')->first();
                                    @endphp
                                    @foreach($referrals as $referral)
                                        @if($referral == "Approved")
                                            <div class="col-md-3 col-6">
                                                <div class="dashboard-widget text-center red-widget mt-4 c-pointer">
                                                    <a onclick="show_rewards_modal()" class="text-white d-block">
                                                        <i class="fa fa-dollar"></i>
                                                        <span class="d-block title heading-3 strong-400">{{ single_price($total) }}</span>
                                                        <span class="d-block sub-title">{{__('Rewards to be Claim')}}</span>
                                                    </a>
                                                </div>
                                            </div>
                                        @elseif($referral == "Requested")
                                            <div class="col-md-3 col-6">
                                                <div class="dashboard-widget text-center orange-widget mt-4 c-pointer">
                                                    <a class="text-white d-block">
                                                        <span class="d-block title text-orange heading-4 strong-400" style="margin-bottom: 20px;">{{__('Processing ID Verification')}}</span>
                                                        <span class="d-block sub-title">{{__('Within three business days.')}}</span>
                                                    </a>
                                                </div>
                                            </div>
                                        @elseif($referral == "Declined")
                                            @php
                                                $user_id = auth()->user()->id;
                                                $id_status_decline = "Declined";
                                                $reject = DB::table('referrals')->select('id_status')->where([['id_status','=',$id_status_decline], ['user_id','LIKE',"%{$user_id}%"]])->count()
                                            @endphp
                                            @if($reject == 1)
                                            <div class="col-md-3 col-6">
                                                <div class="dashboard-widget text-center yellow-widget mt-4 c-pointer">
                                                    <a onclick="show_ticket_modal()" class="text-white d-block">
                                                        <span class="d-block title text-orange heading-4 strong-400" style="margin-bottom: 20px;">{{__('Declined Verification')}}</span>
                                                        <span class="d-block sub-title">{{__('You have (2) attempts remaining')}}</span>
                                                    </a>
                                                </div>
                                            </div>
                                            @endif
                                            @if($reject == 2)
                                                <div class="col-md-3 col-6">
                                                    <div class="dashboard-widget text-center yellow-widget mt-4 c-pointer">
                                                        <a onclick="show_ticket_modal()" class="text-white d-block">
                                                            <span class="d-block title text-orange heading-4 strong-400" style="margin-bottom: 20px;">{{__('Declined Verification')}}</span>
                                                            <span class="d-block sub-title">{{__('You have (1) attempt remaining')}}</span>
                                                        </a>
                                                    </div>
                                                </div>       
                                            @endif
                                            @if($reject == 3)
                                                <div class="col-md-3 col-6">
                                                    <div class="dashboard-widget text-center red-widget mt-4 c-pointer">
                                                        <a  class="text-white d-block">
                                                            <span class="d-block title text-orange heading-4 strong-400" style="margin-bottom: 20px;">{{__('DISQUALIFIED')}}</span>
                                                            <span class="d-block sub-title">{{__('You have (0) attempt remaining, Disqualified from the referral program')}}</span>
                                                        </a>
                                                    </div>
                                                </div>       
                                            @endif
                                        @endif
                                    @endforeach
                                @else
                                    <div class="col-md-3 col-6">
                                        <div class="dashboard-widget text-center red-widget mt-4 c-pointer">
                                            <a onclick="show_ticket_modal()" class="text-white d-block">
                                                <i class="fa fa-dollar"></i>
                                                <span class="d-block title heading-3 strong-400">{{ single_price($total) }}</span>
                                                <span class="d-block sub-title">{{__('Rewards to be Claim')}}</span>
                                            </a>
                                        </div>
                                    </div>
                                @endif
                                
                                
                                
                            </div>
                            
                            <!-- DISPLAY REFERRED USERS -->
                            @if(count($users) > 0)
                                <div class="card no-border mt-4">
                                    <div class="card-header py-3">
                                        <h4 class="mb-0 h6">Referred Users</h4>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm table-hover table-responsive-md">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th class="text-center">Email Date Verifed</th>
                                                    <th class="text-center">ID Verification</th>
                                                    <th class="text-center">Reward</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach ($users as $key => $user)
                                                    @php
                                                        $user_id = $user->id;
                                                        $name = NULL;
                                                        $id_status_decline = "Declined";
                                                        $user->reject = DB::table('referrals')->select('id_status')->where([['id_status','=',$id_status_decline], ['user_id','LIKE',"%{$user_id}%"]])->count();
                                                        $user->referred =  DB::table('users')->select('id', 'name', 'referred_by')->where([['email_verified_at','!=',$name], ['referred_by','LIKE',"%{$user_id}%"]])->get();
                                                        $user->referrals =  DB::table('referrals')->select('id_status')->where('user_id', '=', $user->id)->orderBy('created_at', 'desc')->get();
                                                        
                                                        $referrals_verify =  DB::table('referrals')->select('user_id','id_status')->where('user_id', '=', $user_id)->orderBy('created_at', 'desc')->first();
                                                        $counter = DB::table('referrals')->select('id_status')->where('user_id','LIKE',"%{$user_id}%")->count()
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $key+1 }}</td>
                                                        <td>{{$user->name}}</td>
                                                        <td>{{$user->email}}</td>
                                                        @if($user->email_verified_at == NULL)
                                                            <td class="text-center"><span class="badge badge-pill badge-danger">Pending</span></td>
                                                        @else
                                                            <td class="text-center">{{date('m-d-Y', strtotime($user->email_verified_at))}}</td>
                                                        @endif
                                                        {{-- id verification --}}
                                                        @if($user->ref_id_status == 'Requested')
                                                            <td class="text-center"><span class="badge badge-pill badge-warning">Processing</span></td>
                                                            <td class="text-warning text-center"><b>------</b></td>
                                                        @elseif($user->ref_id_status == NULL)
                                                            <td class="text-center"><span class="badge badge-pill badge-danger">Pending</span></td>
                                                            <td class="text-danger text-center"><b>------</b></td>
                                                        @elseif($user->ref_id_status == 'Declined')
                                                            <td class="text-center"><span class="badge badge-pill badge-danger">Declined</span></td>
                                                            <td class="text-danger text-center"><b>------</b></td>
                                                        @elseif($user->ref_id_status == 'Approved' && $user->refer_points == 50 )
                                                            <td class="text-center"><span class="badge badge-pill badge-success">Approved</span></td>
                                                            <td class="text-success text-center"><b>{{ single_price($user->refer_points) }}</b></td>
                                                        @elseif($user->ref_id_status == 'Approved' && $user->refer_points == 0)
                                                            <td class="text-center"><span class="badge badge-pill badge-success">Approved</span></td>
                                                            <td class="text-success text-center">Claimed</td>
                                                        @endif
                                                    </tr>
                                            @endforeach
                                            </tbody>
                                            
                                        </table>
                                    </div>
                                    @if(Auth::user()->referred_by != NULL)
                                        @if($counter > 0 )
                                            @php
                                                $user_id = auth()->user()->id;
                                                $id_status = "Requested";
                                                $id_submit_count = DB::table('referrals')->select('id_status')->where([['id_status','=',$id_status], ['user_id','LIKE',"%{$user_id}%"]])->count();
                                            @endphp
                                            @if($id_submit_count > 0)
                                                <hr>
                                                <span class="d-block sub-title text-black strong-400" style="text-align: center; margin-bottom: 10px;">{{__('Thank you! We will review your verification as soon as possible.')}}</span>
                                            @elseif($id_submit_count == 0)
                                                @if(Auth::user()->ref_id_status == NULL)
                                                <hr>
                                                <span class="d-block sub-title text-black strong-400" style="text-align: center; margin-bottom: 10px;">{{__('You have been referred by your Friend! please verify to get ₱50.00 Reward!')}}</span>
                                                <div class="col-md-2" style="margin-left: 350px; margin-bottom: 10px;">
                                                    <div class="text-center green-widget c-pointer">
                                                        <a onclick="show_ticket_modal()" class="text-white d-block">
                                                            <span class="d-block title heading-6 strong-400" >{{__('Verify')}}</span>
                                                        </a>
                                                    </div>
                                                </div>
                                                @endif
                                            @endif
                                        @else
                                            <span class="d-block sub-title text-black strong-400" style="text-align: center; margin-bottom: 10px;">{{__('You have been referred by your Friend! please verify to get ₱50.00 Reward!')}}</span>
                                            <div class="col-md-2" style="margin-left: 350px; margin-bottom: 10px;">
                                                <div class="text-center green-widget c-pointer">
                                                    <a onclick="show_ticket_modal()" class="text-white d-block">
                                                        <span class="d-block title heading-6 strong-400" >{{__('Verify')}}</span>
                                                    </a>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                                <div class="pagination-wrapper py-4">
                                    <ul class="pagination justify-content-end">
                                    {{ $users->links() }}
                                    </ul>
                                </div>
                                
                            
                            @elseif(count($users) == 0)
                                <!--USERS HAS NO REFERRALS AND NOT REFERRED-->
                                @if(Auth::user()->referred_by == NULL)
                                    <div class="card no-border mt-4">
                                        <div class="card-header py-3">
                                            <h4 class="mb-0 h6">Referred Users</h4>
                                        </div>
                                        <div class="card-body">
                                            <table class="table table-sm table-hover table-responsive-md">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Name</th>
                                                        <th>Email</th>
                                                        <th>Email Verifed</th>
                                                        <th>ID Verification</th>
                                                        <th>Reward</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="text-center pt-5 h4" colspan="100%">
                                                            <i class="la la-meh-o d-block heading-1 alpha-5"></i>
                                                        <span class="d-block">{{ __('No history found.') }}</span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endif
                                <!--USER IS REFERRED-->
                                @if(Auth::user()->referred_by != NULL)
                                    <div class="card no-border mt-4">
                                        <div class="card-header py-3">
                                            <h4 class="mb-0 h6">Referred Users</h4>
                                        </div>
                                        <div class="card-body">
                                            <table class="table table-sm table-hover table-responsive-md">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Name</th>
                                                        <th>Email</th>
                                                        <th>Email Verifed</th>
                                                        <th>ID Verification</th>
                                                        <th>Reward</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if($counter > 0 )
                                                        @if($id_submit_count > 0)
                                                            <tr>
                                                                <td class="text-center pt-5 h4" colspan="100%">
                                                                    <i class="la la-smile-o d-block heading-1 alpha-5 text-info"></i>
                                                                    <span class="d-block text-info">{{ __('Thank you! We will review your verification as soon as possible.') }}</span>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @else
                                                        <tr>
                                                            <div class="text-center">
                                                                <td class="text-center pt-5 h4" colspan="100%">
                                                                    <i class="la la-smile-o d-block heading-1 alpha-5 text-success"></i>
                                                                    <span class="d-block mb-4">{{ __('You have been referred by your Friend! please verify to get ₱50.00 Reward!') }}</span>
                                                                    <span onclick="show_ticket_modal()" class="p-2 green-widget rounded text-white c-pointer">{{__('Click here to verify')}}</span>
                                                                </td>
                                                            </div>
                                                        </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="ticket_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-zoom product-modal" id="modal-size" role="document">
            <div class="modal-content position-relative">
                <div class="modal-header">
                    <h5 class="modal-title strong-600 heading-5">{{__('Please verify to claim your rewards')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="" action="{{route('referrals.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    
                    @php
                        $id_status = "Requested";
                        
                        if(Auth::user()->referred_by != NULL){
                            $user_id = auth()->user()->id;
                            $refer_id_status = DB::table('users')->where('id','LIKE',"%{$user_id}%")->get();
                            foreach ($refer_id_status as $key => $refer) {
                                
                                if($refer->referred_by == NULL && $refer->ref_id_status == NULL){
                                        $total = 0;
                                }
                                if($refer->referred_by == NULL && $refer->ref_id_status != NULL){
                                    $total = 0;
                                }
                                if($refer->referred_by != NULL && $refer->ref_id_status == NULL){
                                    $total = 50;
                                }
                                if($refer->referred_by != NULL && $refer->ref_id_status != NULL){
                                    $total = 0;
                                }
                            }
                            foreach ($users as $key => $user) {
                                    
                                if($user->refer_points != 0 && $user->email_verified_at != NULL && $user->ref_id_status == "Approved"){
                                    $total += 50;
                                }
                            }
                        }
                        if(Auth::user()->referred_by == NULL){
                            $user_id = auth()->user()->id;
                            $refer_id_status = DB::table('users')->where('id','LIKE',"%{$user_id}%")->get();
                            foreach ($refer_id_status as $key => $refer) {
                                
                                if($refer->referred_by == NULL && $refer->ref_id_status == NULL){
                                        $total = 0;
                                }
                                if($refer->referred_by == NULL && $refer->ref_id_status != NULL){
                                    $total = 0;
                                }
                                if($refer->referred_by != NULL && $refer->ref_id_status == NULL){
                                    $total = 50;
                                }
                                if($refer->referred_by != NULL && $refer->ref_id_status != NULL){
                                    $total = 0;
                                }
                            }
                            foreach ($users as $key => $user) {
                                    
                                if($user->refer_points != 0 && $user->email_verified_at != NULL && $user->ref_id_status == "Approved"){
                                    $total += 50;
                                }
                            }
                        }
                    @endphp
                    <input type="hidden" name="reward_points" value="{{ $total }}" required>
                    <div class="modal-body gry-bg px-3 pt-3">
                        <div class="row mb-4">
                            <div class="col-md-12">
                               <select class="browser-default custom-select" name="id_type" required>
                                    <option value="" disabled selected>Type of ID</option>
                                    <option value ="Passport">Passport</option>
                                    <option value ="Drivers License">Driver's License</option>
                                    <option value ="SSS">SSS</option>
                                    <option value ="UMID">UMID</option>
                                    <option value ="PhilHealth">PhilHealth</option>
                                    <option value ="TIN">TIN</option>
                                    <option value ="Postal ID">Postal ID</option>
                                    <option value ="Voters ID">Voter's ID</option>
                                    <option value ="PRC">PRC</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" name="id_status" value="{{ $id_status }}" required>
                                <input type="text" class="form-control mb-3" name="id_number" placeholder="ID Number" required>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label class="col-lg-12 control-label">{{__('ID Photo : Front and Back')}}</label>
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
    
    <div class="modal fade" id="promo_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-zoom product-modal" id="modal-size" role="document">
            <div class="modal-content position-relative">
                <button type="button" class="close absolute-close-btn" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="modal-body p-4 added-to-cart">
                    <div class="text-center text-success">
                        <h3>PROMO MECHANICS</h3>
                    </div>
                    <div class="product-box">
                        <div class="block">
                            
                            <div class="block-body">
                                <h6 class="strong-600">
                                    1. Share your referral link from. <br>
                                    2. Your friend registers on the Jigijog webiste using your link. <br>
                                    3. Verify your referral by sending any kind of Government ID.<br>
                                    4. After a valid referral, Both of you will receive P50 in your Jigijog wallet.
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div class="text-center text-success">
                        <h3>TERMS & CONDITIONS</h3>
                    </div>
                    <div class="product-box">
                        <div class="block-body">
                            <h6 class="strong-600">
                                1.The Jigijog referral program is open to all existing and new users who have been registered. However, customers who have been <br>
                                   <b style="margin-left: 15px;"></b> determined to be fraudulent or abusive in any ongoing or completed 
                                    promotion are automatically disqualified from the referral program. <br>
                                2.Definition of terms:<br>
                                   <b style="margin-left: 30px;"></b> Referrer – a jigigog customer or seller who shares his/her referral link.<br>
                                    <b style="margin-left: 30px;"></b>Referral – a user who registers using the referral link shared by the Referrer.<br>
                                3.The Referral must register using the Referrer’s referral link.<br>
                                4.Referral must be a new unique Jigijog user. A unique customer is determined by Jigijog based on the customer's Identification verification.<br>
                                5.The Referrer and Referral will receive P50 after the verification is being validated.<br>
                                6.Jigijog, in its sole discretion, reserves the right to refuse the awarding of rewards for suspicion of abuse or fraud.<br>
                            </h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    
    
    <div class="modal fade" id="rewards_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-zoom product-modal" id="modal-size" role="document">
            <div class="modal-content position-relative">
                <div class="modal-header">
                    <h5 class="modal-title strong-600 heading-5">{{__('Referral Program Claim Rewards')}}</h5>
                </div>
                <form class="" action="{{route('referrals.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    
                    @php
                        $total = 0;
                        
                        $id_type = "from_verified_user";
                        $id_number = "from_verified_user";
                        $id_status = "Approved";
                        foreach ($users as $key => $user) {
                                    
                            if($user->refer_points != 0 && $user->email_verified_at != NULL && $user->ref_id_status == "Approved"){
                                $total += 50;
                            }
                        }
                    @endphp
                    <div class="modal-body gry-bg px-3 pt-3">
                        <div class="col-md-4 col-6" style="margin-left: 155px;">
                            <div class="dashboard-widget text-center red-widget c-pointer">
                                <a href="javascript:;" class="d-block">
                                    <span class="d-block title heading-4 strong-400">{{ single_price($total) }}</span>
                                </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" class="form-control mb-3" name="id_type" value="{{ $id_type }}" required>
                                <input type="hidden" class="form-control mb-3" name="id_number" value="{{ $id_number }}" required>
                                <input type="hidden" class="form-control mb-3" name="reward_points" value="{{ $total }}" required>
                                <input type="hidden" class="form-control mb-3" name="id_status" value="{{ $id_status }}" required>
                            </div>
                        </div>
                        
                        <hr>
                        <div class="col-md-12 col-6">
                            <i class="fa fa-info-circle text-info" style="margin-left: 80px;"></i>  
                            <span class="d-block sub-title text-center" style="margin-top: -20px;">{{__('Claimed rewards will go into your wallet.')}}</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" data-dismiss="modal">{{__('cancel')}}</button>
                        @if($total > 0)
                            <button type="submit" class="btn btn-base-1">{{__('Confirm')}}</button>
                        @else
                            <button type="submit" class="btn btn-primary" disabled>{{__('No Claimable Reward')}}</button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
    
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.5.10/clipboard.min.js"></script>
    <script type="text/javascript">
        function show_ticket_modal(){
            $('#ticket_modal').modal('show');
        }
        function show_rewards_modal(){
            $('#rewards_modal').modal('show');
        }
        function show_promo_modal(){
            $('#promo_modal').modal('show');
        }
        $(document).ready(function(){
            $("#id_image").spartanMultiImagePicker({
                fieldName:        'id_image[]',
                maxCount:         2,
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
        });
        
    </script>
    <script type="text/javascript">
        $(function () {
          $('#clipboardlink').tooltip()
        })
        function setTooltip(message) {
          $('#clipboardlink').tooltip('hide')
            .attr('data-original-title', message)
            .tooltip('show');
        }
        function hideTooltip() {
          setTimeout(function() {
            $('#clipboardlink').tooltip('hide');
          }, 1000);
        }
        // Clipboard
        var clipboard = new Clipboard('#clipboardlink');

        clipboard.on('success', function(e) {
          setTooltip('Copied!');
          hideTooltip();
        });

        clipboard.on('error', function(e) {
          setTooltip('Failed!');
          hideTooltip();
        });
    </script>
@endsection
