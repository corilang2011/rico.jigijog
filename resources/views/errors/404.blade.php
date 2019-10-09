@extends('frontend.layouts.app')

@section('content')
	<div class="text-center" style="margin: 50px 0 20px 0;">
	    <img src="{{ asset('frontend/images/icons/404-error.png') }}" alt="">
	    <h2>{{__('Page Not Found')}}</h2>
	    <div class="pad-btm">
	        {{__('Something went wrong.')}}
	    </div>
	    <div class="pad-btm">
	        {{__('Please check your connection or')}}
	    </div>
	    <div class="pad-btm">
	        <a href="http://jigijog.com/support-ticket">{{__('Click here')}}</a>{{__(' to Submit a ticket for assistance or call ( 032-2547246 )')}}
	    </div>
	    <div class="pt-4"><a class="btn btn-primary" href="{{env('APP_URL')}}">{{__('Return Home')}}</a></div>
	</div>
	<section class="mb-4">
        <div class="container">
            <div class="p-4 bg-white shadow-sm">
                <div class="section-title-1 clearfix">
                    <h3 class="heading-5 strong-700 mb-0 float-left">
                        <span class="mr-4">{{__('Best Deals For You')}}
                        	<span class="badge badge-danger" style="padding-top: -1px;"></span>
                    	</span>
                    </h3>
                </div>
                <div class="caorusel-box">
                    <div class="slick-carousel" data-slick-items="6" data-slick-xl-items="5" data-slick-lg-items="4"  data-slick-md-items="3" data-slick-sm-items="2" data-slick-xs-items="2">
                        @foreach (filter_products(\App\Product::where('published', 1)->where('featured', '1'))->limit(100)->inRandomOrder()->get() as $key => $product)
                        <div class="product-card-2 card card-product m-2 shop-cards shop-tech">
                            <div class="card-body p-0">

                                <div class="card-image p-1">
                                    <a href="{{ route('product', $product->slug) }}" class="d-block" style="background-image:url('{{ asset($product->featured_img) }}');">
                                    </a>
                                </div>

                                <div class="p-3">
                                    <div class="price-box">
                                        @if(home_base_price($product->id) != home_discounted_base_price($product->id))
                                            <del class="old-product-price strong-400">{{ home_base_price($product->id) }}</del>
                                        @endif
                                        <span class="product-price strong-600">{{ home_discounted_base_price($product->id) }}</span>
                                    </div>
                                    <div class="star-rating star-rating-sm mt-1">
                                        {{ renderStarRating($product->rating) }}
                                    </div>
                                    <h2 class="product-title p-0 text-truncate">
                                        <a href="{{ route('product', $product->slug) }}">{{ __($product->name) }}</a>
                                    </h2>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
