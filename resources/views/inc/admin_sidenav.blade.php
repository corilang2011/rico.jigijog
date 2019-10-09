<!--MAIN NAVIGATION-->
<!--===================================================-->
<nav id="mainnav-container">
    <div id="mainnav">

        <!--Menu-->
        <!--================================-->
        <div id="mainnav-menu-wrap">
            <div class="nano">
                <div class="nano-content">

                    <!--Profile Widget-->
                    <!--================================-->
                    {{-- <div id="mainnav-profile" class="mainnav-profile">
                        <div class="profile-wrap text-center">
                            <div class="pad-btm">
                                <img class="img-circle img-md" src="{{ asset('img/profile-photos/1.png') }}" alt="Profile Picture">
                            </div>
                            <a href="#profile-nav" class="box-block" data-toggle="collapse" aria-expanded="false">
                                <span class="pull-right dropdown-toggle">
                                    <i class="dropdown-caret"></i>
                                </span>
                                <p class="mnp-name">{{Auth::user()->name}}</p>
                                <span class="mnp-desc">{{Auth::user()->email}}</span>
                            </a>
                        </div>
                        <div id="profile-nav" class="collapse list-group bg-trans">
                            <a href="#" class="list-group-item">
                                <i class="demo-pli-male icon-lg icon-fw"></i> View Profile
                            </a>
                            <a href="#" class="list-group-item">
                                <i class="demo-pli-gear icon-lg icon-fw"></i> Settings
                            </a>
                            <a href="#" class="list-group-item">
                                <i class="demo-pli-information icon-lg icon-fw"></i> Help
                            </a>
                            <a href="#" class="list-group-item">
                                <i class="demo-pli-unlock icon-lg icon-fw"></i> Logout
                            </a>
                        </div>
                    </div> --}}


                    <!--Shortcut buttons-->
                    <!--================================-->
                    <div id="mainnav-shortcut" class="hidden">
                        <ul class="list-unstyled shortcut-wrap">
                            <li class="col-xs-3" data-content="My Profile">
                                <a class="shortcut-grid" href="#">
                                    <div class="icon-wrap icon-wrap-sm icon-circle bg-mint">
                                    <i class="demo-pli-male"></i>
                                    </div>
                                </a>
                            </li>
                            <li class="col-xs-3" data-content="Messages">
                                <a class="shortcut-grid" href="#">
                                    <div class="icon-wrap icon-wrap-sm icon-circle bg-warning">
                                    <i class="demo-pli-speech-bubble-3"></i>
                                    </div>
                                </a>
                            </li>
                            <li class="col-xs-3" data-content="Activity">
                                <a class="shortcut-grid" href="#">
                                    <div class="icon-wrap icon-wrap-sm icon-circle bg-success">
                                    <i class="demo-pli-thunder"></i>
                                    </div>
                                </a>
                            </li>
                            <li class="col-xs-3" data-content="Lock Screen">
                                <a class="shortcut-grid" href="#">
                                    <div class="icon-wrap icon-wrap-sm icon-circle bg-purple">
                                    <i class="demo-pli-lock-2"></i>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <!--================================-->
                    <!--End shortcut buttons-->


                    <ul id="mainnav-menu" class="list-group">

                        <!--Category name-->
                        {{-- <li class="list-header">Navigation</li> --}}

                        <!--Menu list item-->
                        <li class="{{ areActiveRoutes(['admin.dashboard'])}}">
                            <a class="nav-link" href="{{route('admin.dashboard')}}">
                                <i class="fa fa-home"></i>
                                <span class="menu-title">{{__('Dashboard')}}</span>
                            </a>
                        </li>

                        <!-- Product Menu -->
                        @if(Auth::user()->user_type == 'admin' || in_array('1', json_decode(Auth::user()->staff->role->permissions)))
                            <li>
                                <a href="#">
                                    <i class="fa fa-shopping-cart"></i>
                                    <span class="menu-title">{{__('Products')}}</span>
                                    <i class="arrow"></i>
                                </a>

                                <!--Submenu-->
                                <ul class="collapse">
                                    <li class="{{ areActiveRoutes(['products.jigijog'])}}">
                                        <a class="nav-link" href="{{route('products.jigijog')}}">{{__('Jigijog Products')}}</a>
                                    </li>
                                    @php
                                        $id = Auth::user()->id;
                                        $role = DB::table('staff')->where('user_id', $id)->first();
                                    @endphp
                                    @if(Auth::user()->user_type == 'admin')
                                            <li class="{{ areActiveRoutes(['brands.index', 'brands.create', 'brands.edit'])}}">
                                                <a class="nav-link" href="{{route('brands.index')}}">{{__('Brand')}}</a>
                                            </li>
                                            <li class="{{ areActiveRoutes(['categories.index', 'categories.create', 'categories.edit'])}}">
                                                <a class="nav-link" href="{{route('categories.index')}}">{{__('Category')}}</a>
                                            </li>
                                            <li class="{{ areActiveRoutes(['subcategories.index', 'subcategories.create', 'subcategories.edit'])}}">
                                                <a class="nav-link" href="{{route('subcategories.index')}}">{{__('Subcategory')}}</a>
                                            </li>
                                            <li class="{{ areActiveRoutes(['subsubcategories.index', 'subsubcategories.create', 'subsubcategories.edit'])}}">
                                                <a class="nav-link" href="{{route('subsubcategories.index')}}">{{__('Sub Subcategory')}}</a>
                                            </li>
                                            <li class="{{ areActiveRoutes(['products.admin', 'products.create', 'products.admin.edit'])}}">
                                                <a class="nav-link" href="{{route('products.admin')}}">{{__('In House Products')}}</a>
                                            </li>
                                            @if(\App\BusinessSetting::where('type', 'vendor_system_activation')->first()->value == 1)
                                                <li class="{{ areActiveRoutes(['products.seller', 'products.seller.edit'])}}">
                                                    <a class="nav-link" href="{{route('products.seller')}}">{{__('Seller Products')}}</a>
                                                </li>
                                            @endif
                                            <li class="{{ areActiveRoutes(['reviews.index'])}}">
                                                <a class="nav-link" href="{{route('reviews.index')}}">{{__('Product Reviews')}}</a>
                                            </li>
                                    @endif
                                </ul>
                            </li>
                        @endif

                        @if(Auth::user()->user_type == 'admin' || in_array('2', json_decode(Auth::user()->staff->role->permissions)))
                        <li class="{{ areActiveRoutes(['flash_deals.index', 'flash_deals.create', 'flash_deals.edit'])}}">
                            <a class="nav-link" href="{{ route('flash_deals.index') }}">
                                <i class="fa fa-bolt"></i>
                                <span class="menu-title">{{__('Flash Deal')}}</span>
                            </a>
                        </li>
                        @endif

                        @if(Auth::user()->user_type == 'admin' || in_array('3', json_decode(Auth::user()->staff->role->permissions)))
                            @php
                                $orders_shop1 = DB::table('orders')
                                                ->orderBy('code', 'desc')
                                                ->join('order_details', 'orders.id', '=', 'order_details.order_id')
                                                ->where('order_details.seller_id', '=', 53)
                                                ->where('orders.viewed', 0)
                                                ->select('orders.id')
                                                ->distinct()
                                                ->count();
                                $orders_shop2 = DB::table('orders')
                                                ->orderBy('code', 'desc')
                                                ->join('order_details', 'orders.id', '=', 'order_details.order_id')
                                                ->where('order_details.seller_id', '=', 186)
                                                ->where('orders.viewed', 0)
                                                ->select('orders.id')
                                                ->distinct()
                                                ->count();
                                $orders = $orders_shop1 + $orders_shop2;
                            @endphp
                            <li class="{{ areActiveRoutes(['orders.index.admin', 'orders.show'])}}">
                                <a class="nav-link" href="{{ route('orders.index.admin') }}">
                                    <i class="fa fa-shopping-basket"></i>
                                    <span class="menu-title">{{__('Inhouse orders')}} @if($orders > 0)<span class="pull-right badge badge-info">{{ $orders }}</span>@endif</span>
                                </a>
                            </li>
                        @endif
                        
                        @if(Auth::user()->user_type == 'admin' || in_array('4', json_decode(Auth::user()->staff->role->permissions)))
                            @php
                                $general_orders = DB::table('orders')
                                    ->orderBy('code', 'desc')
                                    ->join('order_details', 'orders.id', '=', 'order_details.order_id')
                                    ->where('order_details.seller_id', '!=', Auth::user()->id)->where('order_details.seller_id', '!=', 186)->where('order_details.seller_id', '!=', 53)
                                    ->where('orders.viewed', 0)
                                    ->select('orders.id')
                                    ->distinct()
                                    ->count();
                            @endphp
                            <li class="{{ areActiveRoutes(['order.general.admin', 'general.show'])}}">
                                <a class="nav-link" href="{{ route('order.general.admin') }}">
                                    <i class="fa fa-shopping-basket"></i>
                                    <span class="menu-title">{{__('General Orders (Outside)')}} @if($general_orders > 0)<span class="pull-right badge badge-info">{{ $general_orders }}</span>@endif</span>
                                </a>
                            </li>
                        @endif

                        @if(Auth::user()->user_type == 'admin' || in_array('19', json_decode(Auth::user()->staff->role->permissions)))
                        @php
                            $pickup_orders = DB::table('orders')
                                        ->orderBy('code', 'desc')
                                        ->join('order_details', 'orders.id', '=', 'order_details.order_id')
                                        ->where('pick_from', '!=', null)
                                        ->select('orders.id')
                                        ->distinct()
                                        ->count();
                        @endphp
                        <li class="{{ areActiveRoutes(['pickup_list.index'])}}">
                            <a class="nav-link" href="{{ route('pickup_list.index') }}">
                                <i class="fa fa-car"></i>
                                <span class="menu-title">{{__('For Pickup')}} @if($pickup_orders > 0)<span class="pull-right badge badge-info">{{ $pickup_orders }}</span>@endif</span>
                            </a>
                        </li>
                        @endif
                        
                        @if(Auth::user()->user_type == 'admin' || in_array('21', json_decode(Auth::user()->staff->role->permissions)))
                            <li class="{{ areActiveRoutes(['jigijog.index'])}}">
                                <a class="nav-link" href="{{ route('jigijog.index') }}">
                                    <i class="fa fa-money"></i>
                                    <span class="menu-title">{{__('Jigijog Product Sales Report')}}</span>
                                </a>
                            </li>
                        @endif
                        
                        @if(Auth::user()->user_type == 'admin' || in_array('23', json_decode(Auth::user()->staff->role->permissions)))
                            <li class="{{ areActiveRoutes(['adjustments.index'])}}">
                                <a class="nav-link" href="{{ route('adjustments.index') }}">
                                    <i class="fa fa-money"></i>
                                    <span class="menu-title">{{__('Price Adjustments')}}</span>
                                </a>
                            </li>
                        @endif
                        
                        @if(Auth::user()->user_type == 'admin' || in_array('5', json_decode(Auth::user()->staff->role->permissions)))
                            <li class="{{ areActiveRoutes(['sales.index', 'sales.show'])}}">
                                <a class="nav-link" href="{{ route('sales.index') }}">
                                    <i class="fa fa-money"></i>
                                    <span class="menu-title">{{__('Total sales')}}</span>
                                </a>
                            </li>
                        @endif
                        
                        @if(Auth::user()->user_type == 'admin' || in_array('22', json_decode(Auth::user()->staff->role->permissions)))
                            <li class="{{ areActiveRoutes(['expense.index'])}}">
                                <a class="nav-link" href="{{ route('expense.index') }}">
                                    <i class="fa fa-money"></i>
                                    <span class="menu-title">{{__('Expenses')}}</span>
                                </a>
                            </li>
                        @endif
                        
                        @if((Auth::user()->user_type == 'admin' || in_array('6', json_decode(Auth::user()->staff->role->permissions))) && \App\BusinessSetting::where('type', 'vendor_system_activation')->first()->value == 1)
                            <li>
                                <a href="#">
                                    <i class="fa fa-user-plus"></i>
                                    <span class="menu-title">{{__('User Manual Verification')}}</span>
                                    <i class="arrow"></i>
                                </a>
    
                                <!--Submenu-->
                                <ul class="collapse">
                                    <li class="{{ areActiveRoutes(['user_list.user_list_index'])}}">
                                        <a class="nav-link" href="{{ route('user_list.user_list_index') }}">{{__('User List')}}</a>
                                    </li>
                                </ul>
                            </li>
                            
                            <li>
                                <a href="#">
                                    <i class="fa fa-user-plus"></i>
                                    <span class="menu-title">{{__('Sellers')}}</span>
                                    <i class="arrow"></i>
                                </a>
    
                                <!--Submenu-->
                                <ul class="collapse">
                                    <li class="{{ areActiveRoutes(['sellers.index', 'sellers.create', 'sellers.edit'])}}">
                                        @php
                                            $sellers = \App\Seller::where('verification_status', 0)->where('verification_info', '!=', null)->count();
                                        @endphp
                                        <a class="nav-link" href="{{route('sellers.index')}}">{{__('Seller list')}} @if($sellers > 0)<span class="pull-right badge badge-info">{{ $sellers }}</span> @endif</a>
                                    </li>
                                    <li class="{{ areActiveRoutes(['seller_verification_form.index'])}}">
                                        <a class="nav-link" href="{{route('seller_verification_form.index')}}">{{__('Seller verification form')}}</a>
                                    </li>
                                    <li class="{{ areActiveRoutes(['business_settings.vendor_commission'])}}">
                                        <a class="nav-link" href="{{ route('business_settings.vendor_commission') }}">{{__('Jigijog Fee')}}</a>
                                    </li>
                                </ul>
                            </li>
                            
                            <li>
                                <a href="#">
                                    <i class="fa fa-user-plus"></i>
                                    <span class="menu-title">{{__('Customers')}}</span>
                                    <i class="arrow"></i>
                                </a>
    
                                <!--Submenu-->
                                <ul class="collapse">
                                    <li class="{{ areActiveRoutes(['customers.index'])}}">
                                        <a class="nav-link" href="{{ route('customers.index') }}">{{__('Customer list')}}</a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                        
                        @if(Auth::user()->user_type == 'admin' || in_array('7', json_decode(Auth::user()->staff->role->permissions)))
                            <li>
                                <a href="#">
                                    <i class="fa fa-file"></i>
                                    <span class="menu-title">{{__('General Reports')}}</span>
                                    <i class="arrow"></i>
                                </a>
    
                                <!--Submenu-->
                                <ul class="collapse">
                                    <li class="{{ areActiveRoutes(['stock_report.index'])}}">
                                        <a class="nav-link" href="{{ route('stock_report.index') }}">{{__('Stock Report')}}</a>
                                    </li>
                                    <li class="{{ areActiveRoutes(['seller_report.index'])}}">
                                        <a class="nav-link" href="{{ route('seller_report.index') }}">{{__('Seller Report')}}</a>
                                    </li>
                                    <li class="{{ areActiveRoutes(['seller_sale_report.index'])}}">
                                        <a class="nav-link" href="{{ route('seller_sale_report.index') }}">{{__('Seller Based Selling Report')}}</a>
                                    </li>
                                    <li class="{{ areActiveRoutes(['wish_report.index'])}}">
                                        <a class="nav-link" href="{{ route('wish_report.index') }}">{{__('Product Wish Report')}}</a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                        
                        @if(Auth::user()->user_type == 'admin' || in_array('18', json_decode(Auth::user()->staff->role->permissions)))
                            <li>
                                <a href="#">
                                    <i class="fa fa-file"></i>
                                    <span class="menu-title">{{__('Inhouse Reports')}}</span>
                                    <i class="arrow"></i>
                                </a>
    
                                <!--Submenu-->
                                <ul class="collapse">
                                    <li class="{{ areActiveRoutes(['in_house_sale_report.index'])}}">
                                        <a class="nav-link" href="{{ route('in_house_sale_report.index') }}">{{__('In House Sale Report')}}</a>
                                    </li>
                                </ul>
                            </li>
                        @endif

                        @if(Auth::user()->user_type == 'admin' || in_array('8', json_decode(Auth::user()->staff->role->permissions)))
                            <li>
                                <a href="#">
                                    <i class="fa fa-envelope"></i>
                                    <span class="menu-title">{{__('Messaging')}}</span>
                                    <i class="arrow"></i>
                                </a>
    
                                <!--Submenu-->
                                <ul class="collapse">
                                    <li class="{{ areActiveRoutes(['newsletters.index'])}}">
                                        <a class="nav-link" href="{{route('newsletters.index')}}">{{__('Newsletters')}}</a>
                                    </li>
                                </ul>
                            </li>
                        @endif

                        @if(Auth::user()->user_type == 'admin' || in_array('9', json_decode(Auth::user()->staff->role->permissions)))
                            <li>
                                <a href="#">
                                    <i class="fa fa-briefcase"></i>
                                    <span class="menu-title">{{__('Business Settings')}}</span>
                                    <i class="arrow"></i>
                                </a>
    
                                <!--Submenu-->
                                <ul class="collapse">
                                    <li class="{{ areActiveRoutes(['activation.index'])}}">
                                        <a class="nav-link" href="{{route('activation.index')}}">{{__('Activation')}}</a>
                                    </li>
                                    <li class="{{ areActiveRoutes(['payment_method.index'])}}">
                                        <a class="nav-link" href="{{ route('payment_method.index') }}">{{__('Payment method')}}</a>
                                    </li>
                                    <li class="{{ areActiveRoutes(['smtp_settings.index'])}}">
                                        <a class="nav-link" href="{{ route('smtp_settings.index') }}">{{__('SMTP Settings')}}</a>
                                    </li>
                                    <li class="{{ areActiveRoutes(['google_analytics.index'])}}">
                                        <a class="nav-link" href="{{ route('google_analytics.index') }}">{{__('Google Analytics')}}</a>
                                    </li>
                                    <li class="{{ areActiveRoutes(['facebook_chat.index'])}}">
                                        <a class="nav-link" href="{{ route('facebook_chat.index') }}">{{__('Facebook Chat')}}</a>
                                    </li>
                                    <li class="{{ areActiveRoutes(['social_login.index'])}}">
                                        <a class="nav-link" href="{{ route('social_login.index') }}">{{__('Social Media Login')}}</a>
                                    </li>
                                    <li class="{{ areActiveRoutes(['currency.index'])}}">
                                        <a class="nav-link" href="{{route('currency.index')}}">{{__('Currency')}}</a>
                                    </li>
                                    <li class="{{ areActiveRoutes(['languages.index', 'languages.create', 'languages.store', 'languages.show', 'languages.edit'])}}">
                                        <a class="nav-link" href="{{route('languages.index')}}">{{__('Languages')}}</a>
                                    </li>
                                </ul>
                            </li>
                        @endif

                        @if(Auth::user()->user_type == 'admin' || in_array('10', json_decode(Auth::user()->staff->role->permissions)))
                            <li>
                                <a href="#">
                                    <i class="fa fa-desktop"></i>
                                    <span class="menu-title">{{__('Frontend Settings')}}</span>
                                    <i class="arrow"></i>
                                </a>
    
                                <!--Submenu-->
                                <ul class="collapse">
                                    <li class="{{ areActiveRoutes(['home_settings.index', 'home_banners.index', 'sliders.index', 'home_categories.index', 'home_banners.create', 'home_categories.create', 'home_categories.edit', 'sliders.create'])}}">
                                        <a class="nav-link" href="{{route('home_settings.index')}}">{{__('Home')}}</a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <span class="menu-title">{{__('Policy Pages')}}</span>
                                            <i class="arrow"></i>
                                        </a>
    
                                        <!--Submenu-->
                                        <ul class="collapse">
    
                                            <li class="{{ areActiveRoutes(['sellerpolicy.index'])}}">
                                                <a class="nav-link" href="{{route('sellerpolicy.index', 'seller_policy')}}">{{__('Seller Policy')}}</a>
                                            </li>
                                            <li class="{{ areActiveRoutes(['returnpolicy.index'])}}">
                                                <a class="nav-link" href="{{route('returnpolicy.index', 'return_policy')}}">{{__('Return Policy')}}</a>
                                            </li>
                                            <li class="{{ areActiveRoutes(['supportpolicy.index'])}}">
                                                <a class="nav-link" href="{{route('supportpolicy.index', 'support_policy')}}">{{__('Support Policy')}}</a>
                                            </li>
                                            <li class="{{ areActiveRoutes(['terms.index'])}}">
                                                <a class="nav-link" href="{{route('terms.index', 'terms')}}">{{__('Terms & Conditions')}}</a>
                                            </li>
                                            <li class="{{ areActiveRoutes(['privacypolicy.index'])}}">
                                                <a class="nav-link" href="{{route('privacypolicy.index', 'privacy_policy')}}">{{__('Privacy Policy')}}</a>
                                            </li>
                                        </ul>
    
                                    </li>
                                    <li class="{{ areActiveRoutes(['links.index', 'links.create', 'links.edit'])}}">
                                        <a class="nav-link" href="{{route('links.index')}}">{{__('Useful Link')}}</a>
                                    </li>
                                    <li class="{{ areActiveRoutes(['generalsettings.index'])}}">
                                        <a class="nav-link" href="{{route('generalsettings.index')}}">{{__('General Settings')}}</a>
                                    </li>
                                    <li class="{{ areActiveRoutes(['generalsettings.logo'])}}">
                                        <a class="nav-link" href="{{route('generalsettings.logo')}}">{{__('Logo Settings')}}</a>
                                    </li>
                                    <li class="{{ areActiveRoutes(['generalsettings.color'])}}">
                                        <a class="nav-link" href="{{route('generalsettings.color')}}">{{__('Color Settings')}}</a>
                                    </li>
                                </ul>
                            </li>
                        @endif

                        @if(Auth::user()->user_type == 'admin' || in_array('11', json_decode(Auth::user()->staff->role->permissions)))
                        <li>
                            <a href="#">
                                <i class="fa fa-desktop"></i>
                                <span class="menu-title">{{__('E-commerce Setup')}}</span>
                                <i class="arrow"></i>
                            </a>

                            <!--Submenu-->
                            <ul class="collapse">
                                <li>
                                    <li class="{{ areActiveRoutes(['coupon.index','coupon.create','coupon.edit',])}}">
                                        <a class="nav-link" href="{{route('coupon.index')}}">{{__('Coupon')}}</a>
                                    </li>
                                </li>
                            </ul>
                        </li>
                        @endif

                        @if(Auth::user()->user_type == 'admin' || in_array('12', json_decode(Auth::user()->staff->role->permissions)))
                            @php
                                $support_ticket = DB::table('tickets')->where('viewed', 0)->select('id')->count();
                            @endphp
                            <li class="{{ areActiveRoutes(['support_ticket.admin_index'])}}">
                                <a class="nav-link" href="{{ route('support_ticket.admin_index') }}">
                                    <i class="fa fa-support"></i>
                                    <span class="menu-title">{{__('Support Ticket')}} @if($support_ticket > 0)<span class="pull-right badge badge-info">{{ $support_ticket }}</span>@endif</span>
                                </a>
                            </li>
                            
                        @endif
                        
                        @if(Auth::user()->user_type == 'admin' || in_array('13', json_decode(Auth::user()->staff->role->permissions)))
                             @php
                                $request_counter = DB::table('referrals')->where('id_status', 'Requested')->select('id_status')->count();
                            @endphp
                            <li>
                                <a href="#">
                                    <i class="fa fa-users"></i>
                                    <span class="menu-title">{{__('Referral Program')}} @if($request_counter > 0)<span class="pull-right badge badge-info">{{ $request_counter }}</span>@endif </span>
                                    
                                </a>
                                <!--Submenu-->
                                <ul class="collapse">
                                    <li>
                                        <li class="{{ areActiveRoutes(['referral_program.admin_customer_index'])}}">
                                            <a class="nav-link" href="{{ route('referral_program.admin_customer_index') }}">{{__('Customers Referral')}}</a>
                                        </li>
                                    </li>
                                    <li>
                                        <li class="{{ areActiveRoutes(['referral_program.admin_seller_index'])}}">
                                            <a class="nav-link" href="{{ route('referral_program.admin_seller_index') }}">{{__('Sellers Referral')}}</a>
                                        </li>
                                    </li>
                                    <li>
                                        <li class="{{ areActiveRoutes(['referral_program.admin_id_verify'])}}">
                                            <a class="nav-link" href="{{ route('referral_program.admin_id_verify') }}">{{__('ID verification')}} @if($request_counter > 0)<span class="pull-right badge badge-info">{{ $request_counter }}</span>@endif</a>
                                        </li>
                                    </li>
                                </ul>
                            </li>
                        @endif
                        
                        @if(Auth::user()->user_type == 'admin' || in_array('14', json_decode(Auth::user()->staff->role->permissions)))
                            @php
                                $request_withdraw = DB::table('wallets')->where('payment_details', 'Processing Fund Transfer')->select('payment_details')->count();
                            @endphp
                            <li class="{{ areActiveRoutes(['wallet.admin_index'])}}">
                                <a class="nav-link" href="{{ route('wallet.admin_index') }}">
                                    <i class="fa fa-money"></i>
                                    <span class="menu-title">{{__('Withdrawal Requests')}} @if($request_withdraw > 0)<span class="pull-right badge badge-info">{{ $request_withdraw }}</span>@endif  </span>
                                </a>
                            </li>
                        @endif
                        
                        @if(Auth::user()->user_type == 'admin' || in_array('15', json_decode(Auth::user()->staff->role->permissions)))
                            @php
                                $count_fee = DB::table('wallets')->where([['viewed', '0'],['title', 'Products Sold']])->select('viewed')->count();
                            @endphp
                            <li class="{{ areActiveRoutes(['wallet.admin_wallet_index'])}}">
                                <a class="nav-link" href="{{ route('wallet.admin_wallet_index') }}">
                                    <i class="demo-pli-wallet-2"></i>
                                    <span class="menu-title">{{__('Jigijog Fee Wallet')}} @if($count_fee > 0)<span class="pull-right badge badge-info">{{ $count_fee }}</span>@endif  </span>
                                </a>
                            </li>
                        @endif
                        
                        @if(Auth::user()->user_type == 'admin' || in_array('20', json_decode(Auth::user()->staff->role->permissions)))
                        <li class="{{ areActiveRoutes(['receipt.index'])}}">
                            <a class="nav-link" href="{{ route('receipt.index') }}">
                                <i class="fa fa-book"></i>
                                <span class="menu-title">{{__('Upload Receipts')}}</span>
                            </a>
                        </li>
                        @endif
                        
                        @if(Auth::user()->user_type == 'admin' || in_array('16', json_decode(Auth::user()->staff->role->permissions)))
                            <li class="{{ areActiveRoutes(['seosetting.index'])}}">
                                <a class="nav-link" href="{{ route('seosetting.index') }}">
                                    <i class="fa fa-search"></i>
                                    <span class="menu-title">{{__('SEO Setting')}}</span>
                                </a>
                            </li>
                        @endif

                        @if(Auth::user()->user_type == 'admin' || in_array('17', json_decode(Auth::user()->staff->role->permissions)))
                            <li>
                                <a href="#">
                                    <i class="fa fa-user"></i>
                                    <span class="menu-title">{{__('Staffs')}}</span>
                                    <i class="arrow"></i>
                                </a>
    
                                <!--Submenu-->
                                <ul class="collapse">
                                    <li class="{{ areActiveRoutes(['staffs.index', 'staffs.create', 'staffs.edit'])}}">
                                        <a class="nav-link" href="{{ route('staffs.index') }}">{{__('All staffs')}}</a>
                                    </li>
                                    <li class="{{ areActiveRoutes(['roles.index', 'roles.create', 'roles.edit'])}}">
                                        <a class="nav-link" href="{{route('roles.index')}}">{{__('Staff permissions')}}</a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
        <!--================================-->
        <!--End menu-->

    </div>
</nav>

<div class="modal notif" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Notification</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p id="messageNotif"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>