@if (count($products) > 0)
    <table class="table table-sm table-hover table-responsive-md">
        <thead>
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
        <tbody>
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
        </tbody>
    </table>
@endif
