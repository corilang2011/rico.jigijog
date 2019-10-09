<div class="card sticky-top">
    <div class="card-title">
        <div class="row align-items-center">
            <div class="col-6">
                <h3 class="heading heading-3 strong-400 mb-0">
                    <span>{{__('Summary')}}</span>
                </h3>
            </div>

            <div class="col-6 text-right">
                <span class="badge badge-md badge-success">{{ count(Session::get('cart')) }} {{__('Items')}}</span>
            </div>
        </div>
    </div>

    <div class="card-body">
        <table class="table-cart table-cart-review">
            <thead>
                <tr>
                    <th class="product-name">{{__('Product')}}</th>
                    <th class="product-total text-right">{{__('Total')}}</th>
                    <th class="product-total text-right">{{__('Shop')}}</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $subtotal = 0;
                    $tax = 0;
                    $shipping = 0;
                    $jigijog = 0;
                @endphp
                <!-- EDITED -->
                @foreach (Session::get('cart') as $key => $cartItem)
                    @php
                        $product = \App\Product::find($cartItem['id']);
                        if($cartItem['shop'] == "Aliathus"){
                            $jigijog += $cartItem['price'];
                        }
                        $subtotal += $cartItem['price']*$cartItem['quantity'];
                        $tax += $cartItem['tax']*$cartItem['quantity'];
                        $product_name_with_choice = $product->name;
                        if(isset($cartItem['color'])){
                            $product_name_with_choice .= ' - '.\App\Color::where('code', $cartItem['color'])->first()->name;
                        }
                        foreach (json_decode($product->choice_options) as $choice){
                            $str = $choice->name; // example $str =  choice_0
                            $product_name_with_choice .= ' - '.$cartItem[$str];
                        }
                        
                    @endphp
                    <!-- END EDITED -->
                    <tr class="cart_item">
                        <td class="product-name">
                            {{ $product_name_with_choice }}
                            <strong class="product-quantity">× {{ $cartItem['quantity'] }}</strong>
                        </td>
                        <td class="product-total text-right">
                            <span class="pl-4">{{ single_price($cartItem['price']*$cartItem['quantity']) }}</span>
                        </td>
                        <td class="product-total text-right">
                            <span class="pl-4">{{ $cartItem['shop'] }}</span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table class="table-cart table-cart-review my-4">
            <thead>
                <tr>
                    <th class="product-name">{{__('Product Shipping charge')}}</th>
                    <th class="product-total text-right">{{__('Amount')}}</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $counter = 0;
                    $array = array();
                    $shops = array();
                @endphp
                @foreach (Session::get('cart') as $key => $cartItem)
                    @php
                    if($counter == 0){
                        $array[$counter] = array('Shop' => ''.$cartItem['shop'], 'Weight' => ''.$cartItem['weight'], 'Quantity' => ''.$cartItem['quantity']);
                        $shops[$counter] = $cartItem['shop'];
                        $counter++;
                    }else{
                        $result = array_search(''.$cartItem['shop'], $shops);
                        if($result !== false){
                            $array[$result]['Weight'] += ((int)$cartItem['weight'] * $cartItem['quantity']);
                        }else{
                            $array[$counter] = array('Shop' => ''.$cartItem['shop'], 'Weight' => ''.$cartItem['weight'], 'Quantity' => ''.$cartItem['quantity']);
                            $shops[$counter] = $cartItem['shop'];
                            $counter++;
                        }
                    }
                    @endphp
                @endforeach

                @foreach($array as $arr)
                    @php
                        $weight = $arr['Weight'] * $arr['Quantity'];

                            if($weight <= 1){
                                $partial_shipping = 75.00;
                            }
                            else if($weight > 1 && $weight <= 3){
                                $partial_shipping = 120.00;
                            }
                            else if($weight > 3 && $weight <= 4){
                                $partial_shipping = 180.00;
                            }
                            else if($weight > 4){
                                $partial_shipping = 180.00;
                            }
                      
                        $shipping += $partial_shipping;
                    @endphp
                    <tr class="cart_item">
                        <td class="product-name">
                            {{ $arr['Shop'] }}
                        </td>
                        <td class="product-total text-right">
            
                            <span class="pl-4">{{ single_price($partial_shipping) }}</span>

                            <!-- <span class="pl-4">{{ $arr['Weight'] }}</span> -->
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table class="table-cart table-cart-review">

            <tfoot>
                <tr class="cart-subtotal">
                    <th>{{__('Subtotal')}}</th>
                    <td class="text-right">
                        <span class="strong-600">{{ single_price($subtotal) }}</span>
                    </td>
                </tr>

                <!-- <tr class="cart-shipping">
                    <th>{{__('Tax')}}</th>
                    <td class="text-right">
                        <span class="text-italic">{{ single_price($tax) }}</span>
                    </td>
                </tr> -->

                <tr class="cart-shipping">
                    <th>{{__('Total Shipping')}}</th>
                    <td class="text-right">
                        <span class="text-italic">{{ single_price($shipping) }}</span>
                    </td>
                </tr>

                @if (Session::has('coupon_discount'))
                    <tr class="cart-shipping">
                        <th>{{__('Coupon Discount')}}</th>
                        <td class="text-right">
                            <span class="text-italic">{{ single_price(Session::get('coupon_discount')) }}</span>
                        </td>
                    </tr>
                @endif

                @php
                    $total = $subtotal+$tax+$shipping;
                    if(Session::has('coupon_discount')){
                        $total -= Session::get('coupon_discount');
                    }
                @endphp

                <tr class="cart-total">
                    <th><span class="strong-600">{{__('Total')}}</span></th>
                    <td class="text-right">
                        <strong><span>{{ single_price($total) }}</span></strong>
                    </td>
                </tr>
            </tfoot>
        </table>

    </div>
</div>
