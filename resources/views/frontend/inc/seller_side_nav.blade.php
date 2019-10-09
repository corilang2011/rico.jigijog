<div class="sidebar sidebar--style-3 no-border stickyfill p-0">
    <div class="widget mb-0">
        <div class="widget-profile-box text-center p-3">
            <!--<div class="name">-->
            <!--    <div class="photoContainer">-->
            <!--        <div class="_1ro1 _5ycb drop_elem" id="u_0_1b" style="-->
            <!--                /* margin: auto; */-->
            <!--                /* padding: 20px 0 0 0; */-->
            <!--                text-align: center;-->
            <!--                /* vertical-align: middle; */-->
            <!--            ">-->
            <!--            <a class="_1nv3 _11kg _1nv5 profilePicThumb" href="{{ asset(Auth::user()->avatar_original) }}" rel="theater" id="u_0_10" saprocessedanchor="true" style="" target="_blank">-->
            <!--                <img class="_11kf img" alt="your Profile Photo, Image may contain: {{ Auth::user()->name }}" src="{{ asset(Auth::user()->avatar_original) }}" style=""></a>-->
            <!--                <div class="fbTimelineProfilePicSelector _23fv" style="">-->
            <!--            <div class="_156n _23fw _1o59" data-ft="{&quot;tn&quot;:&quot;+B&quot;}" style="">-->
            <!--            <a href="#" class="_156p _1o5e" rel="dialog" role="button" id="u_0_1c" tabindex="0" data-toggle="modal" data-target="#myProfileModal" id="open">Update</a>-->
                        
            <!--                </div>-->
            <!--            </div>-->
            <!--        </div>-->
            <!--    </div>-->
            <!--</div>-->

           
            <div class="image" style="background-image:url('{{ asset(Auth::user()->avatar_original) }}')"></div>
            @if(Auth::user()->seller->verification_status == 1)
                <div class="name mb-0">
                    <a href="#" class="_156p _1o5e" rel="dialog" role="button" id="u_0_1c" tabindex="0" data-toggle="modal" data-target="#myProfileModal" id="open">{{ Auth::user()->name }} <span class="ml-2"><i class="fa fa-check-circle" style="color:green"></i></span></a></div>
            @else
                <div class="name mb-0">
                    <a href="#" class="_156p _1o5e" rel="dialog" role="button" id="u_0_1c" tabindex="0" data-toggle="modal" data-target="#myProfileModal" id="open">{{ Auth::user()->name }} <span class="ml-2"><i class="fa fa-times-circle" style="color:red"></i></span></a></div>
            @endif
        </div>
        <div class="sidebar-widget-title py-3">
            <span>{{__('Menu')}}</span>
        </div>
        <div class="widget-profile-menu py-3">
            <ul class="categories categories--style-3">
                <li>
                    <a href="{{ route('dashboard') }}" class="{{ areActiveRoutesHome(['dashboard'])}}">
                        <i class="la la-dashboard"></i>
                        <span class="category-name">
                            {{__('Dashboard')}}
                        </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('purchase-history.index') }}" class="{{ areActiveRoutesHome(['purchase-history.index'])}}">
                        <i class="la la-file-text"></i>
                        <span class="category-name">
                            {{__('Purchase History')}}
                        </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('wishlists.index') }}" class="{{ areActiveRoutesHome(['wishlists.index'])}}">
                        <i class="la la-heart-o"></i>
                        <span class="category-name">
                            {{__('Wishlist')}}
                        </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('seller.products') }}" class="{{ areActiveRoutesHome(['seller.products', 'seller.products.upload', 'seller.products.edit'])}}">
                        <i class="la la-diamond"></i>
                        <span class="category-name">
                            {{__('Products')}}
                        </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('orders.index') }}" class="{{ areActiveRoutesHome(['orders.index'])}}">
                        <i class="la la-file-text"></i>
                        <span class="category-name">
                            {{__('Orders')}}
                        </span>
                        &nbsp;
                        @php
                            $count = DB::table('order_details')->where('seller_id', Auth::user()->id)->where('payment_status', "unpaid")->count('id');
                        @endphp
                        <span class="badge badge-pill badge-danger">{{ $count }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('reviews.seller') }}" class="{{ areActiveRoutesHome(['reviews.seller'])}}">
                        <i class="la la-star-o"></i>
                        <span class="category-name">
                            {{__('Product Reviews')}}
                        </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('shops.index') }}" class="{{ areActiveRoutesHome(['shops.index'])}}">
                        <i class="la la-cog"></i>
                        <span class="category-name">
                            {{__('Shop Setting')}}
                        </span>
                    </a>
                </li>
                <!--<li>-->
                <!--    <a href="{{ route('payments.index') }}" class="{{ areActiveRoutesHome(['payments.index'])}}">-->
                <!--        <i class="la la-cc-mastercard"></i>-->
                <!--        <span class="category-name">-->
                <!--            {{__('Payment History')}}-->
                <!--        </span>-->
                <!--    </a>-->
                <!--</li>-->
                <li>
                    <a href="{{ route('profile') }}" class="{{ areActiveRoutesHome(['profile'])}}">
                        <i class="la la-user"></i>
                        <span class="category-name">
                            {{__('Manage Profile')}}
                        </span>
                    </a>
                </li>
                @if (\App\BusinessSetting::where('type', 'wallet_system')->first()->value == 1)
                    <li>
                        <a href="{{ route('wallet.index') }}" class="{{ areActiveRoutesHome(['wallet.index'])}}">
                            <i class="la la-dollar"></i>
                            <span class="category-name">
                                {{__('My Wallet')}}
                            </span>
                        </a>
                    </li>
                @endif
                <li>
                    <a href="{{ route('support-ticket.index') }}" class="{{ areActiveRoutesHome(['support-ticket.index'])}}">
                        <i class="la la-support"></i>
                        <span class="category-name">
                            {{__('Support Ticket')}}
                        </span>
                        &nbsp;
                        @php
                            $count = DB::table('tickets')->where('user_id', Auth::user()->id)->where('replied', 1)->count('id');
                        @endphp
                        @if($count != 0)
                            <span class="badge badge-pill badge-danger">{{ $count }}</span>
                        @endif
                    </a>
                </li>
                <li>
                    <a href="{{ route('referrals.index') }}" class="{{ areActiveRoutesHome(['referrals.index'])}}">
                        <i class="la la-users"></i>
                        <span class="category-name">
                            {{__('Referral Program')}}
                        </span>
                    </a>
                </li>
                @php
                    $shops =  DB::table('shops')->where('user_id','=', Auth::user()->id)->get();
                @endphp
                @foreach($shops as $shop)
                    <li>
                        <a href="{{ route('shop.visit', $shop->slug) }}" target="_blank">
                            <i class="la la-dashboard"></i>
                            <span class="category-name">
                                {{__('Visit Shop')}}
                            </span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="sidebar-widget-title py-3">
            <span style="color: #ff4e00 ;">{{__('Wallet')}}</span>
        </div>
        <div class="widget-balance pb-3 pt-1">
            <div class="text-center">
                <div class="heading-4 strong-700 mb-4">
                    @php
                        $orderDetails = \App\OrderDetail::where('seller_id', Auth::user()->id)->where('created_at', '>=', date('-30d'))->get();
                        $total = 0;
                        foreach ($orderDetails as $key => $orderDetail) {
                            if($orderDetail->order->payment_status == 'paid'){
                                $total += $orderDetail->price;
                            }
                        }
                    @endphp
                    <small class="d-block text-sm alpha-5 mb-2">{{__('Your wallet earnings')}}</small>
                    <span class="p-2 bg-base-1 rounded">{{ single_price(Auth::user()->balance) }}</span>
                </div>
                <table class="text-left mb-0 table w-75 m-auto">
                    <tr>
                        @php
                            $user_id = auth()->user()->id;
                            $title_two = "Referral Bonus";
                            $title = "Products Sold";
                            $status = "Outgoing Fund Transfer";
                            $product = DB::table('wallets')->where([['title','=',$title], ['user_id','LIKE',"%{$user_id}%"]])->sum('amount')
                        @endphp
                        <td class="p-1 text-sm">
                            {{__('Product earnings')}}:
                        </td>
                        <td class="p-1">
                            {{ single_price($product) }}
                        </td>
                    </tr>
                    <tr>
                        @php
                            $balance = DB::table('wallets')->where([['title','=',$title_two], ['user_id','LIKE',"%{$user_id}%"]])->sum('amount')
                        @endphp
                        <td class="p-1 text-sm">
                            {{__('Referral earnings')}}:
                        </td>
                        <td class="p-1">
                            {{ single_price($balance) }}
                        </td>
                    </tr>
                    <tr>
                        @php
                            $balance = DB::table('wallets')->where([['payment_details','=',$status], ['user_id','LIKE',"%{$user_id}%"]])->sum('amount')
                        @endphp
                        <td class="p-1 text-sm">
                            {{__('Total Withdrawn')}}:
                        </td>
                        <td class="p-1">
                            {{ single_price($balance) }}
                        </td>
                    </tr>
                    <tr>
                        @php
                            $orderDetails = \App\OrderDetail::where('seller_id', Auth::user()->id)->where('created_at', '>=', date('-60d'))->where('created_at', '<=', date('-30d'))->get();
                            $total = 0;
                            foreach ($orderDetails as $key => $orderDetail) {
                                if($orderDetail->order->payment_status == 'paid'){
                                    $total += $orderDetail->price;
                                }
                            }
                        @endphp
                        <!--<td class="p-1 text-sm">-->
                        <!--    {{__('Last Month')}}:-->
                        <!--</td>-->
                        <!--<td class="p-1">-->
                        <!--    {{ single_price($total) }}-->
                        <!--</td>-->
                    </tr>
                </table>
            </div>
            <table>

            </table>
        </div>
    </div>
</div>

<!--Modal Form -->
<form class="" action="{{ route('seller.profile.update') }}" method="POST" enctype="multipart/form-data" id="form">
        @csrf
  <!-- Modal -->

<div class="modal" tabindex="-1" role="dialog" id="myProfileModal">


  <div class="modal-dialog" role="document">

    
    <div class="modal-content">
        <div class="alert alert-danger" style="display:none"></div>



      <div class="modal-header">
        
        <h5 class="modal-title">{{__('Update Profile Picture')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-box bg-white mt-4">
        <div class="form-box-title px-3 py-2" style="display:none;">
            {{__('Basic info')}}
        </div>
        <div class="form-box-content p-3">
            <div class="row" style="display:none;">
                <div class="col-md-3">
                    <label>{{__('Your Name')}}</label>
                </div>
                <div class="col-md-9">
                    <input type="text" class="form-control mb-3" placeholder="{{__('Your Name')}}" name="name" id="my_name" value="{{ Auth::user()->name }}">
                </div>
            </div>
            <div class="row" style="display:none;">
                <div class="col-md-3">
                    <label>{{__('Your Email')}}</label>
                </div>
                <div class="col-md-9">
                    <input type="email" class="form-control mb-3" placeholder="{{__('Your Email')}}" name="email" id="my_email" value="{{ Auth::user()->email }}" disabled>
                </div>
            </div>
            <div class="row" >
                <div class="col-md-3">
                    <label>{{__('Photo')}}</label>
                </div>
                <div class="col-md-9">
                    <input type="file" name="photo" id="file-0001" class="custom-input-file custom-input-file--4" data-multiple-caption="{count} files selected" accept="image/*" />
                    <label for="file-0001" class="mw-100 mb-3">
                        <span></span>
                        <strong>
                            <i class="fa fa-upload"></i>
                            {{__('Choose image')}}
                        </strong>
                    </label>
                </div>
            </div>
            <div class="row" style="display:none;">
                <div class="col-md-3">
                    <label>{{__('Your Password')}}</label>
                </div>
                <div class="col-md-9">
                    <input type="password" class="form-control mb-3" placeholder="{{__('New Password')}}" name="new_password" id="my_new_password">
                </div>
            </div>
            <div class="row" style="display:none;">
                <div class="col-md-3">
                    <label>{{__('Confirm Password')}}</label>
                </div>
                <div class="col-md-9">
                    <input type="password" class="form-control mb-3" placeholder="{{__('Confirm Password')}}" name="confirm_password" id="my_confirm_password">
                </div>
            </div>
        </div>
    </div>

    <div class="form-box bg-white mt-4" style="display:none;">
        <div class="form-box-title px-3 py-2">
            {{__('Shipping info')}}
        </div>
        <div class="form-box-content p-3">
            <div class="row">
                <div class="col-md-3 pt-2">
                    <label>{{__('Phone')}}</label>
                </div>
                <div class="col-md-9">
                    <input id="my_phone" type="number" class="form-control mb-3" placeholder="Your Phone Number" name="phone" value="{{ Auth::user()->phone }}">
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-3 pt-2">
                    <label>{{__('Province')}}</label>
                </div>
                <div class="col-md-9">
                    <div class="mb-3">
                        <select class="form-control mb-3 selectpicker" data-placeholder="Select your province" id="my_province" name="province" required>
                            @foreach (\App\Province::all() as $key => $province)
                                <option value="{{ $province->provCode }}" <?php if(Auth::user()->province == $province->provCode) echo "selected";?> >{{ $province->provDesc }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 pt-2">
                    <label>{{__('City')}}</label>
                </div>
                <div class="col-md-9">
                    <div class="mb-3">
                        <select class="form-control mb-3 selectpicker" data-placeholder="Select your city" id="my_city" name="city" required>
                            @foreach (\App\City::all() as $key => $city)
                                @if(Auth::user()->province == $city->provCode)
                                    <option value="{{ $city->citymunCode }}" <?php if(Auth::user()->city == $city->citymunCode) echo "selected";?> >{{ $city->citymunDesc }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 pt-2">
                    <label>{{__('Barangay')}}</label>
                </div>
                <div class="col-md-9">
                    <div class="mb-3">
                        <select class="form-control mb-3 selectpicker" data-placeholder="Select your barangay" id="my_barangay" name="barangay" required>
                            @foreach (\App\Barangay::all() as $key => $brgy)
                                @if(Auth::user()->city == $brgy->citymunCode)
                                    <option value="{{ $brgy->brgyCode }}" <?php if(Auth::user()->barangay == $brgy->brgyCode) echo "selected";?> >{{ $brgy->brgyDesc }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-3 pt-2">
                    <label>{{__('Building, Street, or etc.')}}</label>
                </div>
                <div class="col-md-9">
                    <input type="text" class="form-control textarea-autogrow mb-3" placeholder="Building, Street, or etc." id="my_landmark" name="landmark" value="{{ Auth::user()->landmark }}" required></input>
                </div>
            </div>
            <input id="my_address" name="address" type="text" hidden value="{{ Auth::user()->address }}"></input>
        </div>
    </div>
                               
        
      <div class="modal-footer">

        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button  class="btn btn-success" id="ajaxProfileSubmit">{{__('Update Profile')}}</button>

        
        </div>
        </div>

    </div>
  </div>
</div>


  </form>
  <!--End Modal Form -->
