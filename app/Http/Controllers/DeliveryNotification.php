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
use Mail;
use App\Mail\InvoiceEmailManager;

class DeliveryNotification extends Controller
{
    public function index(Request $request)
    {
        // 
    }  

    public function delivery_notification(Request $request)
    { 
            
        $order = Order::findOrFail($request->order_id);
        foreach($order->orderDetails->where('order_id', $request->order_id)->where('seller_id', $request->seller_id) as $key => $orderDetail){
            $orderDetail->payment_status = $request->status;
            //$orderDetail->save();
            //if($request->status == "paid"){
                            
                    $user = User::find($orderDetail->seller_id);
                                       
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
                    $array['email'] = $user->email;
                    $array['seller_id'] = $user->id;
                    $array['seller_name'] = $user->name;  
                    $array['order_user_id'] = $order->user_id;  

                    $array['order_status'] = $request->status;
                    $array['status'] = $request->status;
                    //$array['message'] = $request->message;
                    //$array['subject'] = 'Test Email';

                    if($array['order_status'] == 'delivered'){ 

                        $array['order_status'] = 'delivered'; 
                        $array['content'] = 'Your order has been delivered successfully.';

                    } 
                    if($array['order_status'] == 'pending'){ 

                        $array['order_status'] = 'pending'; 
                        $array['content'] = 'Your order is pending.';

                    } 

                    if($array['order_status'] == 'on_delivery'){ 

                        $array['order_status'] = 'on_delivery';
                        $array['content'] = 'Your order is on delivery.'; 

                    } 

                    if($array['order_status'] == 'on_review'){ 

                        $array['order_status'] = 'on_review'; 
                        $array['content'] = 'Your order is being reviewed.';

                    } 

                    if($array['order_status'] == 'returned_item'){ 

                        $array['order_status'] = 'returned_item'; 
                        $array['content'] = 'Your order is being returned.';

                    }

                    if($array['order_status'] == 'declined'){ 

                        $array['order_status'] = 'declined';
                        $array['content'] = 'Your order has been declined.';

                    }

                    if($user->email != null){   
                        
                        Mail::to($user->email)->queue(new InvoiceEmailManager($array));
                    
                    } 

                    //send email to customer/buyer 
                    $shipping_address = json_decode($order->shipping_address); 
                    if($request->status != 'delivered'){
                        //$array['customer_name'] = $shipping_address->name;  
                        Mail::to($shipping_address->email)->queue(new InvoiceEmailManager($array));

                   } 
                //} 
                
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
                                       
                   /** send email notification DELIVERED/PAID status to seller **/
                    if($orderDetail->payment_status == 'paid'){
                        if($user->email != null){   
                            $array['view'] = 'emails.delivery_paid_notification';
                            $array['subject'] = 'Paid Order with Order Code # '.$order->code;
                            $array['from'] = env('MAIL_USERNAME');
                            $array['from_name'] = env('MAIL_FROM_NAME');
                            $array['content'] = 'Hi. Your item has been paid successfully.';
                            
                            /**update invoice html**/             
                            $array['order_code'] = $order->code;            
                            $array['order_id'] = $order->id;         
                            $array['email'] = $user->email;
                            $array['seller_id'] = $user->id;
                            $array['seller_name'] = $user->name;  
                            $array['delivery_status'] = $orderDetail->delivery_status; 
                            $array['payment_status'] = 'paid';
                            
                            Mail::to($user->email)->queue(new InvoiceEmailManager($array));
                            //Mail::to('corilang2011@gmail.com')->queue(new InvoiceEmailManager($array));
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
