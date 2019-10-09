<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <title>INVOICE {{ $array['order_code'] }}</title>
    <link rel="stylesheet" href="{{ asset('frontend/css/bootstrap.min.css') }}" type="text/css">

<!-- Icons -->
<link rel="stylesheet" href="{{ asset('frontend/css/font-awesome.min.css') }}" type="text/css">
<script src="{{ asset('frontend/js/vendor/jquery.min.js') }}"></script> 

    <style media="all">
      #invoice-POS {
  /*box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5);*/
  padding: 2mm;
  margin: 0 auto;
  width: 110mm;
  background: #FFF;
  border: 1px solid #ccc;
}
#invoice-POS ::selection {
  background: #f31544;
  color: #FFF;
}
#invoice-POS ::moz-selection {
  background: #f31544;
  color: #FFF;
}
#invoice-POS h1 {
  font-size: 1.5em;
  color: #222;
}
#invoice-POS h2 {
  font-size: 1.2em;
  color: #4b4f56;
}
#invoice-POS h3 {
  font-size: 1.2em;
  font-weight: 300;
  line-height: 2em;
}
#invoice-POS p {
  font-size: 1.0em;
  color: #666;
  line-height: 1.2em;
}
#invoice-POS #top, #invoice-POS #mid, #invoice-POS #bot {
  /* Targets all id with 'col-' */
  border-bottom: 0px dashed #EEE;
}
#invoice-POS #top {
 /* min-height: 100px;*/
}
#invoice-POS #mid {
  /*min-height: 80px;*/
}
#invoice-POS #bot {
  min-height: 50px;
}
#invoice-POS #top .logo {
  height: 50px;
  width: 80mm;
  /*background: url(images/jigifooter.png) no-repeat;*/
  background-size: 200px 50px;
}
#invoice-POS .clientlogo {
  float: left;
  height: 60px;
  width: 60px;
  /*background: url(http://michaeltruong.ca/images/client.jpg) no-repeat;*/
  background-size: 60px 60px;
  border-radius: 50px;
}
#invoice-POS .info {
  display: block;
  margin-left: 0;
}
#invoice-POS .title {
  float: right;
}
#invoice-POS .title p {
  text-align: right;
}
#invoice-POS table {
  width: 100%;
  border-collapse: collapse;
}
#invoice-POS .tabletitle {
  font-size: 1.0em;
  /*background: #EEE;*/
  border-top: 2px dashed #EEE;
  border-bottom: 2px dashed #EEE;
}
#invoice-POS .tabletitle_top {
  font-size: 1.0em;
  /*background: #EEE;*/
  border-bottom: 0px dashed #EEE;
}
#invoice-POS .tabletitle_bottom {
  font-size: 1.0em;
  /*background: #EEE;*/
  border-bottom: 2px dashed #EEE;
}

#invoice-POS .tabletitle_total {
  font-size: 1.0em;
  /*background: #EEE;*/
  border-top: 2px dashed #EEE;
}
#invoice-POS .tabletitle_subtotal {
  font-size: 1.0em;
  /*background: #EEE;*/
  border-bottom: 0px dashed #EEE;
}
#invoice-POS .tabletitle_tax {
  font-size: 1.0em;
  /*background: #EEE;*/
  border-bottom: 0px dashed #EEE;
}
#invoice-POS .service {
  border-bottom: 0px dashed #EEE;
}
#invoice-POS .item {
  width: 50mm;
}
#invoice-POS .itemtext {
  font-size: 1.0em;
}
#invoice-POS #legalcopy {
  margin-top: 5mm;
}

td, td.label {
    text-align: left;
    padding: 3px;
  }

#invoice-POS span {
    font-size: 1.0em;
    color: #666;
    line-height: 1.2em;

}
.info-span{
    padding: 6px;
    margin: 0 auto;
    color: #2fb54d;
  font-size: 1.3em;
}

.date-text-span{
    padding: 6px;
    margin: 0 auto;
    color: #2fb54d;
  font-size: 1.0em;
}

.text-total{
    text-align: right;
  font-size: 0.8em;
}

.total-text-span{
    padding: 6px;
    margin: 0 auto;
    color: #2fb54d;
  font-size: 1.6em;
}
img.center {
display: block;
margin-left: auto;
margin-right: auto;
margin-top: 10px;
}

.vcenter {
min-height: 12em;
display: table-cell;
vertical-align: middle;
}

.rate{
  width: 115px;
}

.payment{
  width: 115px;
}

.total-top-border{
border: 1px solid whitesmoke;
padding-top: 20px;
padding-bottom: 0px;
}

h1, h2, h3, h4, h5, h6 {
    color: #4b4f56;
    font-weight: 600;
    margin: 0;
    padding: 0;
}
.mid-width{
  border-bottom: 0px dashed #ccc; 
  width: 210px;
}

  *{
    margin: 0;
    padding: 0;
    line-height: 1.5;
    font-family: 'Open Sans', sans-serif;
    color: #333542;
  }
  div{
    font-size: 1rem;
  }
  .gry-color *,
  .gry-color{
    color:#878f9c;
  }*/
  
  @font-face {
  font-family: SourceSansPro;
  src: url({{ asset('css/example-css/SourceSansPro-Regular.ttf') }});

}

.clearfix:after {
  content: "";
  display: table;
  clear: both;
}

a {
  color: #0087C3;
  text-decoration: none;
}

body {
  position: relative;
  width: 21cm;  
  height: 29.7cm; 
  margin: 0 auto; 
  color: #555555;
  background: #FFFFFF; 
  font-family: Arial, sans-serif; 
  font-size: 14px; 
  font-family: SourceSansPro;
}

header {
  padding: 10px 0;
  margin-bottom: 20px;
  border-bottom: 1px solid #AAAAAA;
}

#logo {
  float: left;
  margin-top: 8px;
}

#logo img {
  height: 70px;
}

#company {
  float: right;
  text-align: right;
}


#details {
  margin-bottom: 50px;
}

#client {
  padding-left: 6px;
  border-left: 6px solid #0087C3;
  float: left;
}

#client .to {
  color: #777777;
}

h2.name {
  font-size: 1.4em;
  font-weight: normal;
  margin: 0;
}

#invoice {
  float: right;
  text-align: right;
}

#invoice h1 {
  color: #0087C3;
  font-size: 2.4em;
  line-height: 1em;
  font-weight: normal;
  margin: 0  0 10px 0;
}

#invoice .date {
  font-size: 1.1em;
  color: #777777;
}

#invoice .ocode {
  font-size: 1.1em;
  color: #777777;
}

#thanks{
  font-size: 2em;
  margin-bottom: 50px;
}

#notices{
  padding-left: 6px;
  border-left: 6px solid #0087C3;  
}

#notices .notice {
  font-size: 1.2em;
}

footer {
  color: #777777;
  width: 100%;
  height: 30px;
  position: absolute;
  bottom: 0;
  border-top: 1px solid #AAAAAA;
  padding: 8px 0;
  text-align: center;
}

.text-title-company{
  color:#5f6368;
  font-size: 1.2em;
  margin-top: -5px;
}

 
.picfbicon{
      width:30px;
      height:30px;
      opacity: 1;
      filter: alpha(opacity=100);
      background: url(https://jigijog.com/public/img/Facebook1567514818.png) no-repeat;
    background-size: 100% 100%;
    }
    .picfbicon:hover
    {
    width:30px;
      height:30px;
      opacity: 0.3;
      filter: alpha(opacity=30);
    background: url(https://jigijog.com/public/img/fb-icon-white-27.jpg) no-repeat;
    background-size: 100% 100%;
    }
    

</style>
</head>
<body>
@php
  //echo $array['content'];
    
  //get orders database 
  //$orders = DB::table('orders')->where('id', $array['order_id'])->get();
  
  foreach(\App\Order::where('id', $array['order_id'])->get() as $key => $order){
  
    //get orders 
    $shipping_address = json_decode($order->shipping_address);

    //Customer name
    $customer_name = $shipping_address->name;

    //Customer Address 
    $local_address = $shipping_address->address;
    $city_address = $shipping_address->city;
    $country_address = $shipping_address->country;

    //Customer Phone
    $phone = $shipping_address->phone;

    //Customer Email 
    $email = $shipping_address->email;

    //Order Code
    $order_code = $order->code;

    //GRAND TOTAL 
       $grand_amt = single_price($order->grand_total);
       $grand_amt = getAmount($grand_amt);

       //Order Date
       date_default_timezone_set('Asia/Manila');
       $order_date = date('F j, Y | g:i A  ', $order->date);

       //Payment method
       if($order->payment_type == 'cash_on_delivery'){
       $payment_method = 'COD';
      }else{
        $payment_method = $order->payment_type;
      }



  }

  //get order details
  $shipping_address = json_decode($order->shipping_address);

  $generalsetting = \App\GeneralSetting::first();
    
      $i=1;


/**query on order_details table**/
  //$order_details = DB::table('order_details')->where('order_id', $array['order_id'])->get();

         foreach(\App\OrderDetail::where('order_id', $array['order_id'])->get() as $key => $orderDetail){
         $seller_id = $orderDetail->seller_id;

         /** Shops where seller_id = user_id **/
           $shops = DB::table('shops')->where('user_id', $seller_id)->get();

           foreach($shops as $key => $shop){
             
             $user_id = $shop->user_id;
             $shop_name = $shop->name;

             /** count seller **/
            $count_seller = DB::table('order_details')
            ->where('order_id', $array['order_id'])
            ->distinct($seller_id)
            ->count('seller_id');

            /*** If in case need the shop need to show address and phone ***/
            /**
              $users = DB::table('users')->where('id', $shop->user_id)->get();

              foreach($users as $key => $user){

              $seller_name = $user->name;
              $phone = $user->phone;
              $email = $user->email;
              $address = $user->address;

            }
            **/

            /**Jigijog shop logo**/
              if($count_seller == 1){
               

                if($shop->logo != null){

                  /**Seller shop logo**/
                  $shop_info = 'user';
                  $shop_logo = url('/public') . '/' . $shop->logo;
                  $shop_name = $shop->name;
                  $img_style = "display:inline-block;";

                }else{
                  /**Jigijog shop logo**/
                  $shop_info = 'jigijog';
                  $shop_logo = asset('uploads/admin_logo/jigifooter.png');
                  $shop_name = $generalsetting->site_name;
                  $img_style = "display:inline-block;";
                }


                  
              }else{
                /**Show Jigijog shop logo**/
                $shop_info = 'jigijog';
                $shop_logo = asset('uploads/admin_logo/jigifooter.png');
                $shop_name = $generalsetting->site_name;
                $img_style = "display:inline-block;";

              

            }  

            }
         }
@endphp 


<p style="padding: 20px;">Hi {{ $customer_name }}. You place an order to Jigijog.</p>


<div class="container">



        <table border="0" cellspacing="0" cellpadding="0" style="width:800px;border:1px solid #ccc;-moz-border-radius: 10px;-webkit-border-radius:10px;border-radius:10px;">
        
<thead style="border:1px solid #ccc;background: #f5f5f5;">
          <tr style="font-size:1.0em;border-top:2px dashed #eee;border-bottom:2px dashed #eee;TEXT-ALIGN:LEFT;border:1px solid #ccc">
            
            <th class="desc" style="font-size:1.3em;color:#509605;padding:10px">
    
            <div class="row">
                <div class="col-xs-4 col-sm-4 col-md-4" style="text-align:left">
          <div id="mid" class="vertical" style="border-bottom:0px dashed #ccc;">
            
            <div class="vcenter" style="margin-bottom: -20px;">

            <img src="{{ $shop_logo }}" height="30" style="{{ $img_style }}"> 
			<h2 class="text-title-company" style="color:#5f6368;font-size: 1.2em;margin-top: -5px; display: inline-block">
                {{ $shop_name }}
              </h2>
            </div>
              
  
          </div>  

                </div>
                    
            </div>

            </th>
            

      </tr></thead>
    </table>
  
<table border="0" cellspacing="0" cellpadding="0" style="width:800px;border:1px solid #ccc;-moz-border-radius: 10px;-webkit-border-radius:10px;border-radius:10px;">
     


        <thead style="border:1px solid #ccc">
          <tr style="font-size:1.0em;border-top:2px dashed #eee;border-bottom:2px dashed #eee;TEXT-ALIGN:LEFT;border:1px solid #ccc">
            
            <th class="desc" style="width:200px;font-size:1.3em;color:#509605;padding:10px">
    <span style="color:#999;font-size:0.8em;font-weight:300">TOTAL</span><br>
    PHP{{ number_format($grand_amt, 2) }}</th>
            <th class="qty" style="font-size:1.3em;color:#509605;padding:10px">
    <span style="color:#999;font-size:0.8em;font-weight:300">
    DATE</span><br>{{ $order_date }} 
	
	<table valign="top" align="right" style="margin-right:5px;margin-top: -40px;"><tr><td>

      <div class="row" style="text-align:right;display: inline-block; text-align: right">
        
         @if($shop_info == 'user')

         <img src="{{ asset('uploads/admin_logo/jigifooter.png') }}" height="20" style="display: inline-block;"> 
        
         @endif
        


      </div>
      
      </td></tr></table>
    

  </th>

           
          </tr>
        </thead>

        <thead>
          <tr style="font-size:1.0em;background:#f5f5f5;border-top:2px dashed #eee;border-bottom:2px dashed #eee;TEXT-ALIGN:LEFT">
            
            <th class="desc" style="width:200px;font-size:1.3em;color:#509605;padding:10px">Billing Details</th>
            <th class="qty" style="font-size:1.3em;color:#509605;padding:10px">Receipt Summary</th>

           
          </tr>
        </thead>
        <tbody style="background:#f5f5f5">
          <tr><td valign="top" style="width:250px;padding:10px">
            
              <div id="invoice-POS" style="background:none;border:none;width:200px">
                  
          <div id="mid" style="border-bottom:0px dashed #ccc">
            <div class="info">
              <span>
  Issued to:
  </span>
              <h2 style="font-size: 1.0em;color:#5f6368;font-weight:300;">
                {{ $customer_name }}
              </h2>
              <p></p>
            </div>
          </div>
          
          <div id="mid" style="border-bottom:0px dashed #ccc">
            <div class="info">
              <span>
              Order #: 
              </span>
              <h2 style="font-size: 1.0em;color:#5f6368;font-weight:300;">
              <a href="{{url('/').'/track_your_order?order_code='.$order_code}}" style="text-decoration: none;" title="Click here to track your order. {{url('/').'/track_your_order?order_code='.$order_code}}">{{ $order_code }}
                <br />
                <span style="font-size: 0.8em;">(Click here to track your order)</span>
              </a>

              </h2>
              

              <p></p>
            </div>
          </div>
          
          <div id="mid" style="border-bottom:0px dashed #ccc">
            <div class="info">
              <span>
              Address:
              </span>
              <h2 style="font-size: 1.0em;color:#5f6368;font-weight:300;">
              {{ $local_address }}
            </h2>
              <p></p>
            </div>
          </div>
          
          <div id="mid" style="border-bottom:0px dashed #ccc">
            <div class="info">
              <span>
              Phone:
              </span>
              <h2 style="font-size: 1.0em;color:#5f6368;font-weight:300;">{{ $phone }}</h2>
              <p></p>
            </div>
          </div>

          <div id="mid" class="mid-width">
            <div class="info">
              <span class="">
              Email:
              </span>
              <h2 style="font-size: 1.0em;color:#5f6368;font-weight:300;">
              <a href="mailto:{{ $email }}" style="text-decoration: none;" title="{{ $email }}"> {{ $email }}</a></h2>
              <p></p>
            </div>
          </div>
          
        </div>
            </td>
            
            <td valign="top" style="margin-right:0px">
              <table border="0" cellspacing="0" cellpadding="0" style="width:550px;margin-right:0px;background:#fff;padding:20px;border:1px solid #ccc">
        <thead>
          <tr style="font-size:1.0em;background:#f5f5f5;border-top:2px dashed #eee;border-bottom:2px dashed #eee;TEXT-ALIGN:LEFT">
           
            <th class="desc" style="width:200px">DESCRIPTION</th>
            <th class="unit">UNIT PRICE</th>
            <th class="tax">TAX</th>
            <th class="total">TOTAL</th>
          </tr>
        </thead>
        
        @php 
         //$order_details = DB::table('order_details')->where('order_id', $array['order_id'])->get();

         @endphp  
         @foreach(\App\OrderDetail::where('order_id', $array['order_id'])->get() as $key => $orderDetail)

         @php 
         //$products = DB::table('products')->where('id', $orderDetail->product_id)->get();
    if($orderDetail->variation != "" ){
          $show_style = "";
          }else{
          $show_style = "display:none"; 
          }

        $unit_amt = single_price($orderDetail->price/$orderDetail->quantity);
        $unit_amt = getAmount($unit_amt);

        $total_unit_amt = single_price($orderDetail->price+$orderDetail->tax);
        $total_unit_amt = getAmount($total_unit_amt);

        $stax_amt = single_price($orderDetail->tax/$orderDetail->quantity);
        $stax_amt = getAmount($stax_amt);

    
    @endphp

    
    @foreach(\App\Product::where('id', $orderDetail->product_id)->get() as $key => $product)
        <tbody>
          

          <tr class="service">
          <td class="tableitem"><p class="itemtext">{{ $orderDetail->quantity }}x {{$product->name }} <span style="{{$show_style}}">({{ $orderDetail->variation }})</p></td>

          <td class="tableitem"><p class="itemtext">Php{{ number_format($unit_amt, 2) }}</p></td>
          <td class="payment"><p class="itemtext">Php{{ number_format($stax_amt, 2) }}</p></td>
          <td class="payment"><p class="itemtext">Php{{ number_format($total_unit_amt, 2) }}</p></td>
          </tr>

        </tbody>
        @endforeach 
          
    @endforeach 
    @php 
    /*
    //price sum
    $sum_price = DB::table('order_details')
            ->where('order_id', $orderDetail->order_id)
            ->sum('price');
    //tax sum
    $sum_tax = DB::table('order_details')
            ->where('order_id', $orderDetail->order_id)
            ->sum('tax');
    //shipping cost sum
    $sum_shipping_cost = DB::table('order_details')
            ->where('order_id', $orderDetail->order_id)
            ->sum('shipping_cost');

    //SUBTOTAL
     $subtotal_unit_amt = $sum_price;
     $subtotal_unit_amt = getAmount($subtotal_unit_amt);

     //SHIPPING COST
     $shcost_amt = $sum_shipping_cost;
     $shcost_amt = getAmount($shcost_amt);

     //DISCOUNT 
     $discount_amt = $order->coupon_discount;
     $discount_amt = getAmount($discount_amt);
     
     //TOTAL TAX
     $totaltax_amt = $sum_tax;
     $totaltax_amt = getAmount($totaltax_amt);
     */
    
    //price sum
    $sum_price = \App\OrderDetail::where('order_id', $array['order_id'])
            //->where('seller_id',$array['seller_id'])
            ->sum('price');
    //tax sum
    $sum_tax = \App\OrderDetail::where('order_id', $array['order_id']) 
            //->where('seller_id',$array['seller_id'])
            ->sum('tax');
    //shipping cost sum
    $sum_shipping_cost = \App\OrderDetail::where('order_id', $array['order_id']) 
            //->where('seller_id',$array['seller_id'])
            ->sum('shipping_cost');
    //shipping cost sum
    $sum_coupon_discount = \App\OrderDetail::where('order_id', $array['order_id']) 
            //->where('seller_id',$array['seller_id'])
            ->sum('coupon_discount');            

    //SUBTOTAL
     $subtotal_unit_amt = $sum_price;
     $subtotal_unit_amt = getAmount($subtotal_unit_amt);

     //SHIPPING COST
     $shcost_amt = $sum_shipping_cost;
     $shcost_amt = getAmount($shcost_amt);

     //DISCOUNT 
     $discount_amt = $sum_coupon_discount;
     $discount_amt = getAmount($discount_amt);
     
     //TOTAL TAX
     $totaltax_amt = $sum_tax;
     $totaltax_amt = getAmount($totaltax_amt);

     //GRAND TOTAL 
    $grand_amt = $sum_price + $sum_tax + $sum_shipping_cost - $sum_coupon_discount;
    $grand_amt = getAmount($grand_amt);

    @endphp  
           
        <tfoot>
          <tr>
            
            <td colspan="3"><!--SPACING--></td>
            <td>&nbsp;</td>
          </tr>
          <tr class="tabletitle_subtotal">
      <td colspan="3" style="color: #5f6368;text-align:right;padding-right:20px;font-weight:300;font-size:1.2em;">Subtotal</td>
      <td style="color: #5f6368;font-size: 1.2em;font-weight: 300;">Php{{ number_format($subtotal_unit_amt, 2) }}</td>
      
      </tr>
      <tr class="tabletitle_subtotal">
      <td colspan="3" style="color: #5f6368;text-align:right;padding-right:20px;font-weight:300;font-size:1.2em;">Shipping Cost</td>
      <td style="color: #5f6368;font-size: 1.2em;font-weight: 300;">Php{{ number_format($shcost_amt, 2) }}</td>
      </tr>

      <tr class="tabletitle_subtotal">
      <td colspan="3" style="color: #5f6368;text-align:right;padding-right:20px;font-weight:300;font-size:1.2em;">Discount</td>
      <td style="color: #5f6368;font-size: 1.2em;font-weight: 300;">Php{{ number_format($discount_amt, 2) }}</td>
      </tr>
      
      <tr class="tabletitle_tax">

      <td colspan="3" style="color: #5f6368;text-align:right;padding-right:20px;font-weight:300;font-size:1.2em;">Total Tax</td>
      <td style="color: #5f6368;font-size: 1.2em;font-weight: 300;">Php{{ number_format($totaltax_amt, 2) }}</td>
      </tr>
          <tr style="background:#f5f5f5">
            
            <td colspan="3" style="color: #509605;text-align:right;padding-right:20px;font-weight:600;font-size:1.2em;">GRAND TOTAL</td>            
      <td style="color: #509605;/*border-top: 4px dotted #509605;*/font-size: 1.2em;font-weight: 600;">Php{{ number_format($grand_amt, 2) }}</td>
          </tr>
        </tfoot>
      </table>
             
          <!-- Show shop logo -->
          <table align="right"><tr><td>

      @php 
      /**query on order_details table**/
      $order_details = DB::table('order_details')
                      ->where('order_id', $array['order_id'])
                      ->join('shops', 'order_details.seller_id', '=', 'shops.user_id')
                      ->select('order_details.seller_id', 'shops.logo', 'shops.name')
                      ->groupBy('seller_id')
                      ->get();

        //print_r($order_details);

        foreach ($order_details as $key => $orderDetail){
            //echo '<p>' .$orderDetail->seller_id. '</p>';
            //echo '<p>' . $orderDetail->logo. '</p>';
            //echo '<p>' . $orderDetail->name. '</p>';
            /** count seller **/
                      
            @endphp 
            <div class="row" style="text-align:right;display: inline-block;margin-right: 0px;">
            <img src="{{ url('/public') . '/' . $orderDetail->logo }}" height="20" alt="{{ $orderDetail->name }}" style="display:inline-block;">  

            </div>
            @php 
             
        }
     
        @endphp 
    
      </td></tr></table>
          <!-- End show shop logo -->

            </td>

          </tr></tbody></table>
  
  <!--End Thank You! -->

<div class="row">
        <div class="col-xs-10 col-sm-10 col-md-6 col-xs-offset-1 col-sm-offset-1 col-md-offset-3">
    <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
          <div id="mid" class="vertical" style="border-bottom: 0px dashed #ccc;">
            <div id="legalcopy">
              <p class="legal"><strong>Thank you.</strong>&nbsp;
              </p>
              <p class="legal"><strong>Jigijog Team</strong>&nbsp;
              </p>
            </div>
          </div>
        
                </div>
                
            </div>
      
            
        </div>
    </div>

    <div class="row">
        <div class="col-xs-10 col-sm-10 col-md-6 col-xs-offset-1 col-sm-offset-1 col-md-offset-3">
      <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
          <div id="mid" class="vertical" style="border-bottom: 0px dashed #ccc;">
            <div id="" style="text-align:center;width:800px;padding-top:10px;font-size: 1.2em;">
                          
              <p class="legal">Connect us: <a href="https://www.facebook.com/jigijogphilippines/" class="fb" style="text-decoration: none;" title="Connect us" target="_blank" data-toggle="tooltip" data-original-title="Facebook">
                  <img src="https://jigijog.com/public/img/Facebook1567514818.png" class="&nbsp;picfbicon" style="width: 30px; height: 30px; display: inline-block; text-align:center;"/>
                </a>
              </p>
              <p class="legal">Need help? 24/7 submit a ticket: 
              <a href="{{url('/').'/support-ticket'}}" class="support-ticket" style="text-decoration: none;" title="Need help? 24/7 submit a ticket: {{url('/').'/support-ticket'}}" style="text-decoration: none;" target="_blank" data-toggle="tooltip" data-original-title="support-ticket">
                  jigijog.com/support-ticket
                </a>
              </p>
              <p class="legal">Or call: 032-254-7246 
              </p>
              <hr style="width: 800px;text-align: left;margin-left: 0px;border: 1px solid #f5f5f5;">
              <p class="legal"><h2>REFER & EARN</h2>
                Share this link: 
              <a href="{{url('/').'/users/registration?ref='.\Hashids::encode(auth()->user()->id)}}" style="text-decoration: none;" class="referal" title="Share this link {{url('/').'/users/registration?ref='.\Hashids::encode(auth()->user()->id)}}" target="_blank" data-toggle="tooltip" data-original-title="Refer & Earn">
                  {{url('/').'/users/registration?ref='.\Hashids::encode(auth()->user()->id)}}
                </a>
              </p>
            </div>
          </div>

        </div>
                
            </div>
      
      
      
            
        </div>
    </div>

</div>
</body>
</html>
