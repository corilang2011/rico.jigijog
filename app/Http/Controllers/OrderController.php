<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use App\Product;
use App\Color;
use App\OrderDetail;
use App\CouponUsage;
use App\BusinessSetting;
use App\Wallet;
use App\User;
use Auth;
use Session;
use DB;
use PDF;
use Mail;
use App\Mail\InvoiceEmailManager;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource to seller.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = DB::table('orders')
                    ->orderBy('code', 'desc')
                    ->join('order_details', 'orders.id', '=', 'order_details.order_id')
                    ->where('order_details.seller_id', Auth::user()->id)
                    ->select('orders.id')
                    ->distinct()
                    ->paginate(15);

        return view('frontend.seller.orders', compact('orders'));
    }

    /**
     * Display a listing of the resource to admin.
     *
     * @return \Illuminate\Http\Response
     */
    public function admin_orders(Request $request)
    {
        $orders = DB::table('orders')
                    ->orderBy('id', 'desc')
                    ->join('order_details', 'orders.id', '=', 'order_details.order_id')
                    ->where('order_details.seller_id', 53)->orWhere('order_details.seller_id', 186)
                    ->select('orders.id')
                    ->distinct()->get();

        return view('orders.index', compact('orders'));
    }
    
    public function general_orders(Request $request)
    {
        $orders = DB::table('orders')
                    ->orderBy('id', 'desc')
                    ->join('order_details', 'orders.id', '=', 'order_details.order_id')
                    ->where('order_details.seller_id', '!=', Auth::user()->id)->where('order_details.seller_id', '!=', 186)->where('order_details.seller_id', '!=', 53)
                    ->select('orders.id')
                    ->distinct()->get();

        return view('orders.general_index', compact('orders'));
    }


    /**
     * Display a listing of the sales to admin.
     *
     * @return \Illuminate\Http\Response
     */
    public function sales(Request $request)
    {
        $orders = Order::orderBy('id', 'desc')->get();
        return view('sales.index', compact('orders'));
    }

    /**
     * Display a single sale to admin.
     *
     * @return \Illuminate\Http\Response
     */
    public function sales_show($id)
    {
        $order = Order::findOrFail(decrypt($id));
        return view('sales.show', compact('order'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $order = new Order;
        if(Auth::check()){
            $order->user_id = Auth::user()->id;
        }
        else{
            $order->guest_id = mt_rand(100000, 999999);
        }

        $order->shipping_address = json_encode($request->session()->get('shipping_info'));
        $order->payment_type = $request->payment_option;
        $order->code = date('Ymd-his');
        $order->date = strtotime('now');

        if($order->save()){
            $subtotal = 0;
            $tax = 0;
            $jigijog = 0;
            $shipping = 0;
            $counter = 0;
            $counterShip = 0;
            $c = 0;
            $d = 0;
            $couponCounter = 0;
            $partial_shipping = 0;
            $array = array();
            $shops = array();
            $shipC = array();
            $shops2 = array();

            foreach (Session::get('cart') as $key => $cartItem){
                $product = Product::find($cartItem['id']);

                $subtotal += $cartItem['price']*$cartItem['quantity'];
                $tax += $cartItem['tax']*$cartItem['quantity'];
                //$shipping += $cartItem['shipping']*$cartItem['quantity'];
                
                if($counter == 0){
                    $count = 0;  

                    foreach (Session::get('cart') as $cartItems){
                        // if($cartItems['shop'] == "Aliathus"){
                        //     $jigijog += $cartItems['price'];
                        // }
                        if($count == 0){
                            $array[$count] = array('Shop' => ''.$cartItems['shop'], 'Weight' => ''.$cartItems['weight'], 'Quantity' => ''.$cartItems['quantity']);
                            $shops[$count] = $cartItems['shop'];
                            $count++;
                        }else{
                            $result = array_search(''.$cartItems['shop'], $shops);
                            if($result !== false){
                                $array[$result]['Weight'] += ((int)$cartItems['weight'] * $cartItems['quantity']);
                            }else{
                                $array[$count] = array('Shop' => ''.$cartItems['shop'], 'Weight' => ''.$cartItems['weight'], 'Quantity' => ''.$cartItems['quantity']);
                                $shops[$count] = $cartItems['shop'];
                                $count++;
                            }
                        }
                    }

                    foreach($array as $arr){
                        $weight = $arr['Weight'] * $arr['Quantity'];

                        // if( $arr['Shop'] == "Aliathus" && $jigijog >= 500 ){
                        //     $partial_shipping = 0.00;
                        // }else{
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
                        // }
                        $shipC[$counterShip] = $partial_shipping;
                        $counterShip++;
                        $exactShipping = $shipping += $partial_shipping;
                    }
                    ++$counter;
                }else{
                    $shipping = 0;
                }
                

                $product_variation = null;
                if(isset($cartItem['color'])){
                    $product_variation .= Color::where('code', $cartItem['color'])->first()->name;
                }
                foreach (json_decode($product->choice_options) as $choice){
                    $str = $choice->name; // example $str =  choice_0
                    if ($product_variation != null) {
                        $product_variation .= '-'.str_replace(' ', '', $cartItem[$str]);
                    }
                    else {
                        $product_variation .= str_replace(' ', '', $cartItem[$str]);
                    }
                }

                if($product_variation != null){
                    $variations = json_decode($product->variations);
                    $variations->$product_variation->qty -= $cartItem['quantity'];
                    $product->variations = json_encode($variations);
                    $product->save();
                }

                $order_detail = new OrderDetail;
                $order_detail->order_id  =$order->id;
                $order_detail->seller_id = $product->user_id;
                $order_detail->product_id = $product->id;
                $order_detail->variation = $product_variation;
                $order_detail->base_price = $product->purchase_price;
                $order_detail->selling_price = $cartItem['price'];   
                $order_detail->price = $cartItem['price'] * $cartItem['quantity'];
                $order_detail->tax = $cartItem['tax'] * $cartItem['quantity'];
                if(Session::has('coupon_discount')){
                    if(Session::get('product_id') == $product->id && $couponCounter == 0){
                        $order_detail->coupon_discount = Session::get('coupon_discount');
                        $couponCounter++;
                    }
                }

                if(count($shops) != 1){
                    if($counter == 1){
                        $shops2[$c] = $cartItem['shop'];
                        $order_detail->shipping_cost = $shipC[$d];
                        $d++;
                        $c++;
                        $counter++;
                    }else{
                        $result = array_search(''.$cartItem['shop'], $shops2);
                        if($result !== false){
                            $order_detail->shipping_cost = 0.00;
                        }else{
                            $shops2[$c] = $cartItem['shop'];
                            $order_detail->shipping_cost = $shipC[$d];
                            $d++;
                            $c++;
                        }
                    }
                    // if($cartItem['shop'] == $shops[$c]){
                    //     $order_detail->shipping_cost = $shipC[$d];
                    //     $d++;
                    //     $c++;
                    // }else{
                    //     $order_detail->shipping_cost = 0.00;
                    // }
                }else{
                    $order_detail->shipping_cost = $shipping;
                }

                $order_detail->quantity = $cartItem['quantity'];
                $order_detail->save();

                $product->num_of_sale++;
                $product->save();
            }

            $order->grand_total = $subtotal + $tax + $exactShipping;

            if(Session::has('coupon_discount')){
                $order->grand_total -= Session::get('coupon_discount');
                $order->coupon_discount = Session::get('coupon_discount');

                $coupon_usage = new CouponUsage;
                $coupon_usage->user_id = Auth::user()->id;
                $coupon_usage->coupon_id = Session::get('coupon_id');
                $coupon_usage->save();
            }

            $order->save();
            
            
//TO BE FIX           
//              //stores the pdf for invoice
//             $pdf = PDF::setOptions([
//                             'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true,
//                             'logOutputFile' => storage_path('logs/log.htm'),
//                             'tempDir' => storage_path('logs/')
//                         ])->loadView('invoices.customer_invoice', compact('order'));
//             $output = $pdf->output();
//     		file_put_contents('public/invoices/'.'Order#'.$order->code.'.pdf', $output);

//             //$array['view'] = 'emails.invoice';
//             $array['subject'] = 'Order Placed - '.$order->code;
//             $array['from'] = env('MAIL_USERNAME');
//             $array['from_name'] = env('MAIL_FROM_NAME'); 
//             $array['content'] = 'Hi. Your order has been placed';
//             $array['file'] = 'public/invoices/Order#'.$order->code.'.pdf';
//             $array['file_name'] = 'Order#'.$order->code.'.pdf';

// 			/**update invoice html**/             
//             $array['order_code'] = $order->code;            
//             $array['order_id'] = $order->id;            
//             $array['order_user_id'] = $order->user_id; 
//             $array['payment_status'] = $order->payment_status;
			
//             //sends email to customer with the invoice pdf attached
//             if(env('MAIL_USERNAME') != null && env('MAIL_PASSWORD') != null){
//                 $array['view'] = 'emails.invoice';
//                 Mail::to($request->session()->get('shipping_info')['email'])->queue(new InvoiceEmailManager($array));
//             }
            
//             /**query on order_details table**/
//             //$order_details = DB::table('order_details')->where('order_id', $array['order_id'])->get();

//             foreach(\App\OrderDetail::where('order_id', $array['order_id'])->get() as $key => $orderDetail){
//             $seller_id = $orderDetail->seller_id; 

//             //Query Products 
//             foreach(\App\Product::where('id', $orderDetail->product_id)->get() as $key => $product){
//                 //Query Shops
//                 foreach(\App\Shop::where('user_id', $seller_id)->get() as $key => $shop){

//                     $user_id = $shop->user_id;
//                     $shop_name = $shop->name;

//                     /** count seller **/
//                     $count_seller = DB::table('order_details')
//                     ->where('order_id', $array['order_id'])
//                     ->distinct($seller_id)
//                     ->count('seller_id');
//                     //Query Users  
//                     foreach(\App\User::where('id', $shop->user_id)->get() as $key => $user){  

//                         $seller_name = $user->name;
//                         $phone = $user->phone;
//                         $email = $user->email;
//                         $address = $user->address;

//                         }

//                         //echo '<p> Seller Email: ' . $user->email . '</p>';
//                     }     
//                 }
//             }  

//             //Send email to seller 
//             //$order->payment_type = $request->payment_option;
//             if($order->payment_type == 'cash_on_delivery'){
//                 //$order->payment_status = 'unpaid';
//                 if($user->email != null){ 

//                     $array['view'] = 'emails.seller_invoice';
//                     /**update invoice html**/             
//                     $array['order_code'] = $order->code;            
//                     $array['order_id'] = $order->id;            
//                     $array['order_user_id'] = $order->user_id;
//                     $array['email'] = $user->email;
//                     $array['user_id'] = $user->id;
//                     $array['seller_id'] = $orderDetail->seller_id;  
//                     $array['product_id'] = $product->id; 
//                     /**send invoice to seller**/
//                     Mail::to($user->email)->queue(new InvoiceEmailManager($array));
//                 }
//             }
            
//             unlink($array['file']);

            $request->session()->put('order_id', $order->id);
            Session::forget('product_id');
            Session::forget('coupon_id');
            Session::forget('coupon_discount');
        }
    }

    public function add_pickup_date(Request $request)
    {
        $flag = false;
        $order = Order::find($request->ids);
        foreach($order->orderDetails->where('order_id', $request->ids)->where('seller_id', $request->seller_id) as $orderDet){
            $orderDet->pick_from = $request->from_date;
            $orderDet->pick_to = $request->to_date;
            $orderDet->save();

            $flag = true;
        }

        if($flag){
            flash(__('Pickup date was added successfully!'))->success();
            return back();
        }

        flash(__('Sorry! Something went wrong.'))->error();
        return back();
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::findOrFail(decrypt($id));
        $order->viewed = 1;
        $order->save();
        return view('orders.show', compact('order'));
    }
    
    public function general_show($id)
    {
        $order = Order::findOrFail(decrypt($id));
        $order->viewed = 1;
        $order->save();
        return view('orders.all_show', compact('order'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        if($order != null){
            foreach($order->orderDetails as $key => $orderDetail){
                $orderDetail->delete();
            }
            $order->delete();
            flash('Order has been deleted successfully')->success();
        }
        else{
            flash('Something went wrong')->error();
        }
        return back();
    }

    public function order_details(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        return view('frontend.partials.order_details_seller', compact('order'));
    }

    public function update_delivery_status(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        foreach($order->orderDetails->where('seller_id', Auth::user()->id) as $key => $orderDetail){
            $orderDetail->delivery_status = $request->status;
            $orderDetail->save();
        }
        foreach($order->orderDetails->where('seller_id', '!=', Auth::user()->id) as $key => $orderDetail){
            $orderDetail->delivery_status = $request->status;
            $orderDetail->save();
        }
        return 1;
    }

    public function update_payment_status(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        foreach($order->orderDetails->where('seller_id', Auth::user()->id) as $key => $orderDetail){
            $orderDetail->payment_status = $request->status;
            $orderDetail->save();
        }
        foreach($order->orderDetails->where('seller_id', '!=', Auth::user()->id) as $key => $orderDetail){
            $orderDetail->payment_status = $request->status;
            $orderDetail->save();
            
                if($request->status == "paid"){
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
                }

        }
        $status = 'paid';
        foreach($order->orderDetails as $key => $orderDetail){
            if($orderDetail->payment_status != 'paid'){
                $status = 'unpaid';
            }
            if($orderDetail->payment_status == 'declined'){
                $status = 'declined';
                $qtyy = $orderDetail->quantity;
                $prod_id = $orderDetail->product_id;

                $product = Product::findOrFail($prod_id);
                $product_variation = null;
                $product_variation .= $orderDetail->variation;
                $variations = json_decode($product->variations);
                $variations->$product_variation->qty += (int)$qtyy;
                $product->variations = json_encode($variations);
                $product->save();  
            }
        }
        $order->payment_status = $status;
        $order->save();
        return 1;
    }
    
    public function update_delivery(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        foreach($order->orderDetails->where('order_id', $request->order_id)->where('seller_id', $request->seller_id) as $key => $orderDetail){
            $orderDetail->delivery_status = $request->status;
            $orderDetail->save(); 
            
        }
        return 1;
    }

    public function update_payment(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        foreach($order->orderDetails->where('order_id', $request->order_id)->where('seller_id', $request->seller_id) as $key => $orderDetail){
            $orderDetail->payment_status = $request->status;
            $orderDetail->save();
        }
        foreach($order->orderDetails->where('order_id', $request->order_id)->where('seller_id', $request->seller_id) as $key => $orderDetail){
            $orderDetail->payment_status = $request->status;
            $orderDetail->save();
            
                if($request->status == "paid"){
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
                    
                }
        }
        $status = 'paid';
        foreach($order->orderDetails as $key => $orderDetail){
            if($orderDetail->payment_status != 'paid'){
                $status = 'unpaid';
            }
            if($orderDetail->payment_status == 'declined'){
                $status = 'declined';
                $qtyy = $orderDetail->quantity;
                $prod_id = $orderDetail->product_id;

                $product = Product::findOrFail($prod_id);
                $product_variation = null;
                $product_variation .= $orderDetail->variation;
                $variations = json_decode($product->variations);
                $variations->$product_variation->qty += (int)$qtyy;
                $product->variations = json_encode($variations);
                $product->save();  
            }
        }
        // $order->payment_status = $status;
        // $order->save();
        return 1;
    }
    
    public function decline_order(Request $request)
    {
        $flag = false;
        $order = Order::findOrFail($request->decline);
        foreach($order->orderDetails->where('order_id', $request->decline)->where('seller_id', $request->seller) as $key => $orderDetail){
            $orderDetail->payment_status = 'declined';
            $orderDetail->delivery_status = 'declined';
            $orderDetail->save();

            $flag = true;
        }

        if($flag){
            flash(__('The order was declined successfully!'))->success();
            return back();
        }

        flash(__('Sorrys! Something went wrong.'))->error();
        return back();
    } 
    
    public function delivery_notification(Request $request)
    { 
            
        $order = Order::findOrFail($request->order_id);
        foreach($order->orderDetails->where('order_id', $request->order_id)->where('seller_id', $request->seller_id) as $key => $orderDetail){
            $orderDetail->payment_status = $request->status;
                           
                    $user = User::find($orderDetail->seller_id);
                    $shipping_address = json_decode($order->shipping_address);
                                       
                    /** send email notification DELIVERED/PAID status to seller **/
                    $array['view'] = 'emails.delivery_notification';  
                    $array['subject'] = 'Paid Order with Order Code # '.$order->code;
                    $array['from'] = env('MAIL_USERNAME');
                    $array['from_name'] = env('MAIL_FROM_NAME');
                    $delivery_status = str_replace( '_', ' ', $request->status);
                    $array['subject'] = ucfirst($delivery_status) . ' order with code # '.$order->code;  
                                        
                    /**update invoice html**/             
                    $array['order_code'] = $order->code;            
                    $array['order_id'] = $order->id;  
                    $array['order_user_id'] = null;   
                    $array['seller_id'] = null;
                    $array['email'] = $user->email;
                    $array['seller_name'] = $user->name;  
                    $array['delivery_status'] = $orderDetail->delivery_status; 
                    $array['payment_status'] = 'paid';
                    $array['customer_name'] = $shipping_address->name; 
                    $array['grand_total'] = $order->grand_total; 
                    $array['order_date'] = $order->date;
                    $array['order_status'] = $request->status;
                    $array['status'] = $request->status;

                    //send email to seller 
                    if($user->email != null){   
                        $array['seller_id'] = $user->id; 
                                            
                        Mail::to($user->email)->queue(new InvoiceEmailManager($array)); 
                                            
                    } 

                    //send email to customer/buyer 
                    
                    if(env('MAIL_USERNAME') != null && env('MAIL_PASSWORD') != null){
                        $array['order_user_id'] = $order->user_id;
                        //$array['customer_name'] = $shipping_address->name;  
                        Mail::to($shipping_address->email)->queue(new InvoiceEmailManager($array));
                       

                   } 
               
                
        }
         
        return 1;
                  
           
    } 

    public function delivery_paid_notification(Request $request)
    { 
        
        $order = Order::findOrFail($request->order_id);
        
        foreach($order->orderDetails->where('order_id', $request->order_id)->where('seller_id', $request->seller_id) as $key => $orderDetail){
            $orderDetail->payment_status = $request->status;
            //$orderDetail->save();
            
                if($request->status == "paid"){
                            
                    $user = User::find($orderDetail->seller_id);
                    $shipping_address = json_decode($order->shipping_address);                    
                   /** send email notification DELIVERED/PAID status to seller **/
                    if($orderDetail->payment_status == 'paid'){
                          
                            $array['view'] = 'emails.delivery_paid_notification';
                            $array['subject'] = 'Paid Order with Order Code # '.$order->code;
                            $array['from'] = env('MAIL_USERNAME');
                            $array['from_name'] = env('MAIL_FROM_NAME');
                            $array['content'] = 'Hi. Your item has been paid successfully.';
                            
                            /**update invoice html**/             
                            $array['order_code'] = $order->code;            
                            $array['order_id'] = $order->id;  
                            $array['order_user_id'] = null;   
                            $array['seller_id'] = null;
                            $array['email'] = $user->email;
                            $array['seller_name'] = $user->name;  
                            $array['delivery_status'] = $orderDetail->delivery_status; 
                            $array['payment_status'] = 'paid';
                            $array['customer_name'] = $shipping_address->name; 
                            $array['grand_total'] = $order->grand_total; 
                            $array['order_date'] = $order->date;

                         if($user->email != null){  
                         $array['seller_id'] = $user->id;                                  
                            Mail::to($user->email)->queue(new InvoiceEmailManager($array));
                          
                        } 

                        if(env('MAIL_USERNAME') != null && env('MAIL_PASSWORD') != null){ 

                        $array['order_user_id'] = $order->user_id;      
                            $array['content'] = 'Thank you for shopping ' . $array['from_name'];
                        
                            Mail::to($shipping_address->email)->queue(new InvoiceEmailManager($array));
                           
                       }
                         
                    } 
                }
        } 
        return 1;
                     
           
    }  

    public function new_delivery_notification(Request $request){ 

        //$order = new Order; 
        $order_id = $request->session()->get('order_id');
        $order = Order::findOrFail($order_id);
        if(Auth::check()){
            $order->user_id = Auth::user()->id;
        } 

        
        $array['view'] = 'emails.test_email';
        $array['subject'] = 'Order user id: ';
        $array['from'] = env('MAIL_USERNAME');
        $array['from_name'] = env('MAIL_FROM_NAME');
        $array['content'] = 'Hi your new order is... ';
        
        /**update invoice html**/             
        //$array['user_id'] = $request->user_id;            
        $array['order_id'] = $order_id; 
        //$array['name'] = $shipping_address->name;                 
        //$array['email'] = $user->email;
        //$array['seller_id'] = $user->id;
        //$array['seller_name'] = $user->name;  
        //$array['delivery_status'] = $orderDetail->delivery_status; 
        //$array['payment_status'] = 'paid';
        
        //Mail::to($user->email)->queue(new InvoiceEmailManager($array));
        Mail::to('corilang2011@gmail.com')->queue(new InvoiceEmailManager($array));

    }  
}
