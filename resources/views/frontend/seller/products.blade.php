@extends('frontend.layouts.app')
<title>JigiJog | Products</title>
@section('content')
    <style type="text/css">
        .plus-widget i {
            background: #ddd;
            height: 69px;
            width: 69px;
            line-height: 69px;
            font-size: 36px;
        }
        .prod_search {
            background-image: url({{ asset('frontend/images/icons/search.png') }});
            background-size: 25px;
            background-position: 10px center;
            background-repeat: no-repeat;
            border: 1px solid #ddd;
            padding: 7px 5px 7px 20px;
            text-indent: 20px;
        }
    </style>
    <section class="gry-bg py-4 profile">
        
        <div class="container">
            <div class="row cols-xs-space cols-sm-space cols-md-space">
                <div class="col-lg-3 d-none d-lg-block">
                    @include('frontend.inc.seller_side_nav')
                </div>

                <div class="col-lg-9">
                    <div class="main-content">
                        <!-- Page title -->
                        <div class="page-title">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h2 class="heading heading-6 text-capitalize strong-600 mb-0 d-inline-block">
                                        <!--{{__('Products')}}-->
                                    </h2>
                                </div>
                                <div class="col-md-6">
                                    <div class="float-md-right">
                                        <ul class="breadcrumb">
                                            <li><a href="{{ route('home') }}">{{__('Home')}}</a></li>
                                            <li><a href="{{ route('dashboard') }}">{{__('Dashboard')}}</a></li>
                                            <li><a href="{{ route('seller.products') }}">{{__('Products')}}</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 offset-md-4">
                                <a class="dashboard-widget text-center plus-widget mt-4 d-block" href="{{ route('seller.products.upload')}}">
                                    <i class="la la-plus"></i>
                                    <span class="d-block title heading-6 strong-400 c-base-1">{{ __('Add New Product') }}</span>
                                </a>
                            </div>
                        </div>

                        <!-- Order history table -->
                        <div class="card border mt-4">
                            <div style="background-color: #f9f9f9;" class="card-header py-3">
                                <h4 class="mb-0 h6 mt-2">Products</h4>
                                <div class="md-form mt-0">
                                    <input class="col-lg-4 form-control rounded prod_search" type="text" id="prod_search" name="q" 
                                    placeholder="Search your product here." autocomplete="off" style="float: right; margin-top: -25px;" >
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm table-hover table-responsive-md">
                                    <thead class="gone">
                                        <tr>
                                            <th>#</th>
                                            <th>{{__('Name')}}</th>
                                            <th>{{__('Sub Subcategory')}}</th>
                                            <th>{{__('Current Qty')}}</th>
                                            <th>{{__('Base Price')}}</th>
                                            <th>{{__('Published')}}</th>
                                            <th>{{__('Featured')}}</th>
                                            <th>{{__('Options')}}</th>
                                        </tr>
                                    </thead>
                                    <div id="prod-search-content">
                                        <tr class="not d-none">
                                            
                                        </tr>
                                    </div>
                                    <tbody class="gone">
                                    @if(count($products) > 0)
                                        @foreach ($products as $key => $product)
                                            <tr>
                                                <td>{{ $key+1 }}</td>
                                                <td><a href="{{ route('product', $product->slug) }}" target="_blank">{{ __($product->name) }}</a></td>
                                                <td>{{ $product->subsubcategory->name }}</td>
                                                <td>
                                                    @php
                                                        $qty = 0;
                                                        foreach (json_decode($product->variations) as $key => $variation) {
                                                            $qty += $variation->qty;
                                                        }
                                                        echo $qty;
                                                    @endphp
                                                </td>
                                                <td>{{ $product->unit_price }}</td>
                                                <td><label class="switch">
                                                    <input onchange="update_published(this)" value="{{ $product->id }}" type="checkbox" <?php if($product->published == 1) echo "checked";?> >
                                                    <span class="slider round"></span></label>
                                                </td>
                                                <td><label class="switch">
                                                    <input onchange="update_featured(this)" value="{{ $product->id }}" type="checkbox" <?php if($product->featured == 1) echo "checked";?> >
                                                    <span class="slider round"></span></label>
                                                </td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button class="btn" type="button" id="dropdownMenuButton-{{ $key }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i class="fa fa-ellipsis-v"></i>
                                                        </button>

                                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton-{{ $key }}">
                                                            <a href="{{route('seller.products.edit', encrypt($product->id))}}" class="dropdown-item">{{__('Edit')}}</a>
        					                                <button onclick="confirm_modal('{{route('products.destroy', $product->id)}}')" class="dropdown-item">{{__('Delete')}}</button>
                                                            <a href="{{route('products.duplicate', $product->id)}}" class="dropdown-item">{{__('Duplicate')}}</a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td class="text-center pt-5 h4" colspan="100%">
                                                <i class="la la-meh-o d-block heading-1 alpha-5"></i>
                                            <span class="d-block">{{ __('No products found.') }}</span>
                                            </td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="gone pagination-wrapper py-4">
                            <ul class="pagination justify-content-end">
                                {{ $products->links() }}
                            </ul>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('script')
    <script type="text/javascript">
        function update_featured(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('products.featured') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    showFrontendAlert('success', 'Featured products updated successfully');
                }
                else{
                    showFrontendAlert('danger', 'Something went wrong');
                }
            });
        }

        function update_published(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('products.published') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    showFrontendAlert('success', 'Published products updated successfully');
                }
                else{
                    showFrontendAlert('danger', 'Something went wrong');
                }
            });
        }
    </script>
@endsection
