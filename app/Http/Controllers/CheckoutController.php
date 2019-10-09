<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Category;
use App\Http\Controllers\PaypalController;
use App\Http\Controllers\PayumoneyController;
use App\Http\Controllers\StripePaymentController;
use App\Http\Controllers\PublicSslCommerzPaymentController;
use App\Http\Controllers\OrderController;
use App\Order;
use App\Wallet;
use App\User;
use App\BusinessSetting;
use App\Coupon;
use App\CouponUsage;
use Session; 
use Mail;
use App\Mail\InvoiceEmailManager;

class CheckoutController extends Controller
{

    public function __construct()
    {
        //
    }

    //check the selected payment gateway and redirect to that controller accordingly
    public function checkout(Request $request)
    {
        $orderController = new OrderController;
        $orderController->store($request);

        $request->session()->put('payment_type', 'cart_payment');

        if($request->session()->get('order_id') != null){
            if($request->payment_option == 'paypal'){
                $paypal = new PaypalController;
                return $paypal->getCheckout();
            }
            elseif ($request->payment_option == 'stripe') {
                $stripe = new StripePaymentController;
                return $stripe->stripe();
            }
            elseif ($request->payment_option == 'sslcommerz') {
                $sslcommerz = new PublicSslCommerzPaymentController;
                return $sslcommerz->index($request);
            }
            elseif ($request->payment_option == 'payumoney') {
                $payumoney = new PayumoneyController;
                return $payumoney->index($request);
            }
            elseif ($request->payment_option == 'cash_on_delivery') {
                $order = Order::findOrFail($request->session()->get('order_id'));
                $commission_percentage = BusinessSetting::where('type', 'vendor_commission')->first()->value;
                foreach ($order->orderDetails as $key => $orderDetail) {
                    if($orderDetail->product->user->user_type == 'seller'){
                        $seller = $orderDetail->product->user->seller;
                        $seller->admin_to_pay = $seller->admin_to_pay - ($orderDetail->price*$commission_percentage)/100;
                        $seller->save();
                    }
                }
                
                //sending email...
                //Query on database
                $user = User::find($orderDetail->seller_id);
                $array['subject'] = 'Order Placed - '.$order->code;
                $array['from'] = env('MAIL_USERNAME');
                $array['from_name'] = env('MAIL_FROM_NAME');
                $array['content'] = 'Hi. You place an order to Jigijog.';

                /**update invoice html**/             
                $shipping_address = json_decode($order->shipping_address);
                $array['customer_email'] = $shipping_address->email;
                $array['customer_name'] = $shipping_address->name;
                $array['customer_address'] = $shipping_address->address;
                $array['customer_phone'] = $shipping_address->phone;
                
                /**update invoice html**/             
                $array['order_code'] = $order->code;            
                $array['order_id'] = $order->id;         
                $array['order_user_id'] = null;
                $array['seller_id'] = null;
                $array['payment_status'] = $order->payment_status;
                
                //sends email to customer
                if(env('MAIL_USERNAME') != null && env('MAIL_PASSWORD') != null){
                    $array['order_user_id'] = $order->user_id;
                    $array['view'] = 'emails.invoice';
                    Mail::to($array['customer_email'])->queue(new InvoiceEmailManager($array));
                }

                if($user->email != null){
                    foreach ($order->orderDetails as $key => $orderDetail) {
                    $array['view'] = 'emails.seller_invoice';
                    $array['email'] = $user->email;
                    $array['seller_id'] = $user->id;
                    $array['seller_name'] = $user->name;
                    $array['delivery_status'] = $orderDetail->delivery_status; 
                    $array['payment_status'] = $orderDetail->payment_status; 

                    Mail::to($user->email)->queue(new InvoiceEmailManager($array));

                    } 
                }

                $request->session()->put('cart', collect([]));
                $request->session()->forget('order_id');

                flash("Your order has been placed successfully")->success();
            	return redirect()->route('purchase-history.index');
            }
            elseif ($request->payment_option == 'wallet') {
                $user = Auth::user();
                $user->balance -= Order::findOrFail($request->session()->get('order_id'))->grand_total;
                $user->save();
                return $this->checkout_done($request->session()->get('order_id'), null);
            }
        }
    }

    //redirects to this method after a successfull checkout
    public function checkout_done($order_id, $payment)
    {
        $order = Order::findOrFail($order_id);
        $order->payment_status = 'paid';
        $order->payment_details = $payment;
        $order->save();

        $commission_percentage = BusinessSetting::where('type', 'vendor_commission')->first()->value;
        foreach ($order->orderDetails as $key => $orderDetail) {
            $orderDetail->payment_status = "paid";
            $orderDetail->save();
            
            if($orderDetail->seller_id != 186){
                $commission_percentage = BusinessSetting::where('type', 'vendor_commission')->first()->value;
                $com_deci = $commission_percentage / 100;
                $get_com = $orderDetail->price * $com_deci;
                $com_total = $orderDetail->price - $get_com;
            }else{
                $com_total = $orderDetail->price;
            }

            $wallet = new Wallet;
            $wallet->user_id = $orderDetail->seller_id;
            $wallet->w_order_id = $order->id;
            $wallet->w_order_detail_id = $orderDetail->id;
            $wallet->prod_orig_price = $orderDetail->price;
            $wallet->title = "Products Sold";
            $wallet->amount = $com_total;
            $wallet->payment_details = "Incoming Fund Transfer";
            $wallet->save();

            $user = User::find($orderDetail->seller_id);
            $user->balance += $com_total;
            $user->save(); 
            
            /** send email notification DELIVERED/PAID status to seller **/
            $user = User::find($orderDetail->seller_id);
            $array['from'] = env('MAIL_USERNAME');
            $array['from_name'] = env('MAIL_FROM_NAME');
            $array['content'] = 'Hi. You place an order to Jigijog.';

            /**update invoice html**/             
            $shipping_address = json_decode($order->shipping_address);
            $array['customer_email'] = $shipping_address->email;
            $array['customer_name'] = $shipping_address->name;
            $array['customer_address'] = $shipping_address->address;
            $array['customer_phone'] = $shipping_address->phone;
            
            /**update invoice html**/             
            $array['order_code'] = $order->code;            
            $array['order_id'] = $order->id;         
            $array['order_user_id'] = null;
            $array['seller_id'] = null;
            $array['email'] = $user->email;
            $array['seller_name'] = $user->name;
            $array['delivery_status'] = $orderDetail->delivery_status; 
            $array['payment_status'] = $orderDetail->payment_status; 
            
            if($user->email != null){
                $array['view'] = 'emails.seller_invoice';
                $array['subject'] = 'Order Placed - '.$order->code;
                $array['seller_id'] = $user->id;
                Mail::to($user->email)->queue(new InvoiceEmailManager($array));

                if($array['payment_status'] == 'paid'){
                    $array['view'] = 'emails.delivery_paid_notification';
                    $array['subject'] = 'Paid Order with Order Code # '.$order->code;
                    Mail::to($user->email)->queue(new InvoiceEmailManager($array)); 
                }
            }  

            //sends email to customer
            if(env('MAIL_USERNAME') != null && env('MAIL_PASSWORD') != null){
                
                $array['view'] = 'emails.invoice';
                $array['subject'] = 'Order Placed - '.$order->code;
                $array['order_user_id'] = $order->user_id;
                Mail::to($array['customer_email'])->queue(new InvoiceEmailManager($array));
                
                if($array['payment_status'] == 'paid'){
                    $array['view'] = 'emails.delivery_paid_notification';
                    $array['subject'] = 'Paid Order with Order Code # '.$order->code;
                    Mail::to($array['customer_email'])->queue(new InvoiceEmailManager($array)); 
                }
            }
            
            if($orderDetail->product->user->user_type == 'seller'){
                $seller = $orderDetail->product->user->seller;
                $seller->admin_to_pay = $seller->admin_to_pay + ($orderDetail->price*(100-$commission_percentage))/100;
                $seller->save();
            }
        }

        Session::put('cart', collect([]));
        Session::forget('order_id');
        Session::forget('payment_type');

        flash(__('Your order has been placed. Wallet Payment Completed'))->success();
        return redirect()->route('purchase-history.index');
    }

    public function get_shipping_info(Request $request)
    {
        $categories = Category::all();
        return view('frontend.shipping_info', compact('categories'));
    }

    public function store_shipping_info(Request $request)
    {
        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['address'] = $request->address;
        $data['country'] = $request->country;
        $data['city'] = $request->city;
        $data['postal_code'] = $request->postal_code;
        $data['phone'] = $request->phone;
        $data['checkout_type'] = $request->checkout_type;

        $shipping_info = $data;
        $request->session()->put('shipping_info', $shipping_info);

        $subtotal = 0;
        $tax = 0;
        $shipping = 0;

        $counter = 0;
        $jigijog = 0;
        $array = array();
        $shops = array();
        foreach (Session::get('cart') as $key => $cartItem){
            if($cartItem['shop'] == "Aliathus"){
                $jigijog += $cartItem['price'];
            }
            $subtotal += $cartItem['price']*$cartItem['quantity'];
            $tax += $cartItem['tax']*$cartItem['quantity'];
            //$shipping += $cartItem['shipping']*$cartItem['quantity'];

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
        }

        foreach($array as $arr){
            $weight = $arr['Weight'] * $arr['Quantity'];
            if( $arr['Shop'] == "Aliathus" && $jigijog >= 500 ){
                $partial_shipping = 0.00;
            }else{
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
            }

            $shipping += $partial_shipping;
        }
        $total = $subtotal + $tax + $shipping;

        if(Session::has('coupon_discount')){
                $total -= Session::get('coupon_discount');
        }

        return view('frontend.payment_select', compact('total'));
    }

    public function get_payment_info(Request $request)
    {
        $subtotal = 0;
        $tax = 0;
        $shipping = 0;

        $counter = 0;
        $array = array();
        $shops = array();
        foreach (Session::get('cart') as $key => $cartItem){
            $subtotal += $cartItem['price']*$cartItem['quantity'];
            $tax += $cartItem['tax']*$cartItem['quantity'];
            //$shipping += $cartItem['shipping']*$cartItem['quantity'];

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
        }

        foreach($array as $arr){
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
        }
        $total = $subtotal + $tax + $shipping;

        if(Session::has('coupon_discount')){
                $total -= Session::get('coupon_discount');
        }

        return view('frontend.payment_select', compact('total'));
    }

    public function apply_coupon_code(Request $request){
        //dd($request->all());
        $coupon = Coupon::where('code', $request->code)->first();

        if($coupon != null && strtotime(date('d-m-Y')) >= $coupon->start_date && strtotime(date('d-m-Y')) <= $coupon->end_date && CouponUsage::where('user_id', Auth::user()->id)->where('coupon_id', $coupon->id)->first() == null){
            $coupon_details = json_decode($coupon->details);

            if ($coupon->type == 'cart_base')
            {
                $subtotal = 0;
                $tax = 0;
                $shipping = 0;

                $counter = 0;
                $array = array();
                $shops = array();
                $prod_id = 0;
                foreach (Session::get('cart') as $key => $cartItem)
                {
                    $subtotal += $cartItem['price']*$cartItem['quantity'];
                    $tax += $cartItem['tax']*$cartItem['quantity'];
                    //$shipping += $cartItem['shipping']*$cartItem['quantity'];

                    if($counter == 0){
                        $array[$counter] = array('Shop' => ''.$cartItem['shop'], 'Weight' => ''.$cartItem['weight'], 'Quantity' => ''.$cartItem['quantity']);
                        $shops[$counter] = $cartItem['shop'];
                        $prod_id += $cartItem['id'];
                        $counter++;
                    }else{
                        $result = array_search(''.$cartItem['shop'], $shops);
                        if($result !== false){
                            $array[$result]['Weight'] += ((double)$cartItem['weight'] * $cartItem['quantity']);
                        }else{
                            $array[$counter] = array('Shop' => ''.$cartItem['shop'], 'Weight' => ''.$cartItem['weight'], 'Quantity' => ''.$cartItem['quantity']);
                            $shops[$counter] = $cartItem['shop'];
                            $counter++;
                        }
                    }
                }

                foreach($array as $arr){
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
                }

                $sum = $subtotal+$tax+$shipping;

                if ($sum > $coupon_details->min_buy) {
                    if ($coupon->discount_type == 'percent') {
                        $coupon_discount =  ($sum * $coupon->discount)/100;
                        if ($coupon_discount > $coupon_details->max_discount) {
                            $coupon_discount = $coupon_details->max_discount;
                        }
                    }
                    elseif ($coupon->discount_type == 'amount') {
                        $coupon_discount = $coupon->discount;
                    }

                    if(!Session::has('coupon_discount')){
                        $request->session()->put('coupon_id', $coupon->id);
                        $request->session()->put('product_id', $prod_id);
                        $request->session()->put('coupon_discount', $coupon_discount);
                        flash('Coupon has been applied')->success();
                    }
                    else{
                        flash('Coupon is already applied')->warning();
                    }
                }
            }
            elseif ($coupon->type == 'product_base')
            {
                $coupon_discount = 0;
                foreach (Session::get('cart') as $key => $cartItem){
                    foreach ($coupon_details as $key => $coupon_detail) {
                        if($coupon_detail->product_id == $cartItem['id']){
                            if ($coupon->discount_type == 'percent') {
                                $coupon_discount += $cartItem['price']*$coupon->discount/100;
                            }
                            elseif ($coupon->discount_type == 'amount') {
                                // $coupon_discount += $coupon->discount;
                                $coupon_discount = $coupon->discount;
                                $product_id = $coupon_detail->product_id;
                                break;
                            }
                        }
                    }
                }
                if(!Session::has('coupon_discount')){
                    $request->session()->put('coupon_id', $coupon->id);
                    $request->session()->put('product_id', $product_id);
                    $request->session()->put('coupon_discount', $coupon_discount);
                    flash('Coupon has been applied')->success();
                }
                else{
                    flash('Coupon is already applied')->warning();
                }
            }
        }
        else {
            flash('No Coupon Exists')->warning();
        }
        return back();
    }
}
