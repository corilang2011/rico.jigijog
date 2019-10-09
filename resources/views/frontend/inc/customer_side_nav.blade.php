<div class="sidebar sidebar--style-3 no-border stickyfill p-0">
    <div class="widget mb-0">
        <div class="widget-profile-box text-center p-3">
            <div class="image" style="background-image:url('{{ asset(Auth::user()->avatar_original) }}')"></div>
            <div class="name">{{ Auth::user()->name }}</div>
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
                        <i class="la la-support"></i>
                        <span class="category-name">
                            {{__('Referral Program')}}
                        </span>
                    </a>
                </li>
            </ul>
        </div>
        
        <div class="sidebar-widget-title py-3">
            <span>{{__('Wallet')}}</span>
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
                    <small class="d-block text-sm alpha-5 mb-2">{{__('Your wallet earnings (current month)')}}</small>
                    <span class="p-2 bg-base-1 rounded">{{ single_price(Auth::user()->balance) }}</span>
                </div>
                <table class="text-left mb-0 table w-75 m-auto">
                    <tr>
                        @php
                            $user_id = auth()->user()->id;
                            $title_two = "Referral Bonus";
                            $title = "Product Sale Deposit";
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
                            $orderDetails = \App\OrderDetail::where('seller_id', Auth::user()->id)->where('created_at', '>=', date('-60d'))->where('created_at', '<=', date('-30d'))->get();
                            $total = 0;
                            foreach ($orderDetails as $key => $orderDetail) {
                                if($orderDetail->order->payment_status == 'paid'){
                                    $total += $orderDetail->price;
                                }
                            }
                        @endphp
                        <td class="p-1 text-sm">
                            {{__('Last Month')}}:
                        </td>
                        <td class="p-1">
                            {{ single_price($total) }}
                        </td>
                    </tr>
                </table>
            </div>
            <table>

            </table>
        </div>
        
        @if (\App\BusinessSetting::where('type', 'vendor_system_activation')->first()->value == 1)
            <div class="widget-seller-btn pt-4">
                <a href="{{ route('shops.create') }}" class="btn btn-anim-primary w-100">{{__('Be A Seller')}}</a>
            </div>
        @endif
    </div>
</div>
