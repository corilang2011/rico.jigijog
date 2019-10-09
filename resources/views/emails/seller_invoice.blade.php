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
  /*
  echo "Hi, pls be patient with us. This is just a test receipt on our offline end. <p>Thank you! </p>";
  echo 'Order Code: ' .$array['order_code'];    
  echo '<br />';        
  echo 'Order ID: ' .$array['order_id'];      
  echo '<br />';         
  echo 'Order Customer ID: ' .$array['order_user_id']; 
  echo '<br />';   
  echo 'User Email: ' .$array['user_email']; 
  echo '<br />';   
  echo 'User ID: ' .$array['user_id'];
  echo '<br />';   
  echo 'Seller ID: ' .$array['seller_id'];
  echo '<br />';  
  echo 'Product ID: ' .$array['product_id'];
  echo '<br />';   
  echo '<hr />';   
  */
    
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

    
       //Order Date
       date_default_timezone_set('Asia/Manila');
       $order_date = date('F j, Y | g:i A  ', $order->date);

       //Payment method
       if($order->payment_type == 'cash_on_delivery'){
       $payment_method = 'COD';
      }else{
        $payment_method = $order->payment_type;
      }

  //get order details
  $shipping_address = json_decode($order->shipping_address);

  $generalsetting = \App\GeneralSetting::first();
    
      $i=1;


/**query on order_details table**/
            //$order_details = DB::table('order_details')->where('order_id', $array['order_id'])->get();

    foreach(\App\OrderDetail::where('order_id', $array['order_id'])->get() as $key => $orderDetail){
    $seller_id = $orderDetail->seller_id; 

    //price sum
    $sum_price = \App\OrderDetail::where('order_id', $array['order_id'])
            ->where('seller_id',$array['seller_id'])
            ->sum('price');
    //tax sum
    $sum_tax = \App\OrderDetail::where('order_id', $array['order_id']) 
            ->where('seller_id',$array['seller_id'])
            ->sum('tax');
    //shipping cost sum
    $sum_shipping_cost = \App\OrderDetail::where('order_id', $array['order_id']) 
            ->where('seller_id',$array['seller_id'])
            ->sum('shipping_cost');
    //shipping cost sum
    $sum_coupon_discount = \App\OrderDetail::where('order_id', $array['order_id']) 
            ->where('seller_id',$array['seller_id'])
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

    
      }
    }

// $orderDetail = \App\OrderDetail::findOrFail($array['order_id']);

// if(isset($array['payment_status']) == 'paid' ){
//   $array['payment_status'] = 'paid';
// }elseif( isset($array['delivery_status']) == 'delivered' ){
//   $array['delivery_status'] = 'delivered';
// }else{
//   $array['payment_status'] = \App\OrderDetail::where('order_id', $array['order_id'])->where('payment_status' ,$orderDetail->payment_status)->get();
//   $array['delivery_status'] = \App\OrderDetail::where('order_id', $array['order_id'])->where('delivery_status' , $orderDetail->delivery_status)->get();
// }

  @endphp  

<div class="container">  

      <div class="row">
        <div class="col-xs-10 col-sm-10 col-md-6 col-xs-offset-1 col-sm-offset-1 col-md-offset-3">
          <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
              <div id="mid" class="vertical" style="border-bottom: 0px dashed #ccc;">
                <div id="" style="text-align:center;width:800px;padding-top:10px;font-size: 1.2em;">

        <table border="0" cellspacing="0" cellpadding="0" align="center" id="email_table" style="border-collapse:collapse; width:800px;">
          <tbody>
            <tr>
              <td id="email_content" style="font-family:Helvetica Neue,Helvetica,Lucida Grande,tahoma,verdana,arial,sans-serif;background:#ffffff">
                
                <table border="0" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:collapse">
  <tbody>
    <tr>
      <td height="15" style="line-height:15px" colspan="3">&nbsp;</td></tr><tr><td width="32" align="left" valign="middle" style="height:32;line-height:0px">
  <img src="{{url('/').'/public/frontend/images/logo/jigifooter.png'}}" width="150" height="32" style="border:0" class="CToWUd">
</td>
<td width="15" style="display:block;width:15px">&nbsp;&nbsp;&nbsp;</td><td width="100%" style="text-align: left;"><span style="font-family:Helvetica Neue,Helvetica,Lucida Grande,tahoma,verdana,arial,sans-serif;font-size:19px;line-height:32px;color:#3b5998"><!--{{ $array['from_name'] }}--></span></td></tr>
<tr style="border-bottom:solid 1px #e5e5e5">
  <td height="15" style="line-height:15px" colspan="3">&nbsp;</td>
</tr>
</tbody>
</table>
</td><td width="15" style="display:block;width:15px">&nbsp;&nbsp;&nbsp;</td>
<tr><td>
  <table border="0" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:collapse">
    <tbody>
      <tr>
        <td height="28" style="line-height:28px">&nbsp;
        </td>
      </tr>
      <tr>
        <td>
          <table border="0" cellspacing="0" cellpadding="0" style="border-collapse:collapse">
            <tbody>
              <tr>
                <!--
                <td valign="top" style="padding-right:10px;font-size:0px">
                  <img src="user-profile-image" style="border:0" class="CToWUd">
                </td>
              -->
          <td valign="top" style="width:100%" col="2">
            <table border="0" cellspacing="0" cellpadding="0" style="border-collapse:collapse;font-size:14px;color:#3d4452;width:100%"><tbody><tr><td style="font-size:14px;font-family:LucidaGrande,tahoma,verdana,arial,sans-serif;color:#3d4452;padding-bottom:6px; text-align: left;">
              @foreach(\App\User::where('id', $array['seller_id'])->get() as $key => $user)
                @if($array['order_user_id'] != $array['seller_id']) 
                  
                  Hi {{ $user->name }}, 
                </td></tr>
                <tr><td style="font-size:14px;font-family:LucidaGrande,tahoma,verdana,arial,sans-serif;color:#3d4452;padding-top:6px;padding-bottom:6px; text-align: left;">

                  Your customer has placed an order 

                    @if($array['payment_status'] == 'paid')

                      and has been <b>paid</b> successfully already

                    @endif 

                  to {{ $array['from_name'] }}.

                @else

                  Hi {{ Auth::user()->name }},
                </td>

              </tr><tr><td style="font-size:14px;font-family:LucidaGrande,tahoma,verdana,arial,sans-serif;color:#3d4452;padding-top:6px;padding-bottom:6px; text-align: left;">

                  You placed an order 

                    @if($array['payment_status'] == 'paid')

                      and has been <b>paid</b> successfully already

                    @endif 

                  to {{ $array['from_name'] }}.
                 
                @endif 

              @endforeach 

            &nbsp;</td>
            </tr>
                <tr>

                  </tr><tr><td style="font-size:14px;font-family:LucidaGrande,tahoma,verdana,arial,sans-serif;color:#3d4452;padding-top:6px;padding-bottom:6px; text-align: left;">

                  <p>
                  
                  <table style="width:100%;border-collapse:collapse;border:1px solid rgb(200,200,200)">
                                <tbody>
                                <tr>
                                    <td style="padding:10px 20px" colspan="2">
                                        <table>
                                            <tbody><tr>
                                                <td style="font-size:12px;width:100px">Date and Time:</td>
                                                <td style="padding:0 50px 0 10px">
                                                    <div><b>{{ $order_date }}</b></div>
                                                </td>
                                            </tr>
                                        </tbody></table>
                                    </td>
                                </tr>

                                <tr style="background:#f4f5f4">
                                    <td style="padding:10px 20px" colspan="2">
                                        <table>
                                            <tbody><tr>
                                                <td style="font-size:12px;width:100px">Order Code:</td>
                                                <td style="padding:0 50px 0 10px">
                                                    <div><b>{{ $array['order_code'] }}</b></div>
                                                </td>
                                            </tr>
                                        </tbody></table>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding:10px 20px" colspan="2">
                                        <table>
                                            <tbody><tr>
                                                <td style="font-size:12px;width:100px">Total Amount:</td>
                                                <td style="padding:0 50px 0 10px">
                                                    <div><b>PHP{{ number_format($grand_amt, 2) }}
                                                    </b></div>
                                                </td>
                                            </tr>
                                        </tbody></table>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
 
                </p>

            &nbsp;</td>
            </tr>
                <tr>

                      <td style="font-size:14px;font-family:LucidaGrande,tahoma,verdana,arial,sans-serif;color:#3d4452;padding-top:6px;padding-bottom:6px; text-align: left;">
                        Please confirm this incoming orders of your products from your account at {{ $array['from_name'] }} dashboard.
                      </td>
                    </tr>

                    <tr>
                      <td style="font-size:14px;font-family:LucidaGrande,tahoma,verdana,arial,sans-serif;color:#3d4452;padding-top:6px;padding-bottom:6px">
                        <table style="margin-top:30" border="0" cellspacing="0" cellpadding="0" align="center">
                          <tbody>
                          <tr>
                          <td style="padding: 10px 18px 10px 18px;border-radius:50px;" align="center" bgcolor="#FF5B00"><a style="font-size:18px;font-family:'Lato',sans-serif;font-weight:strong;color:#ffffff;text-decoration:none;display:inline-block" href="{{url('/').'/orders'}}" target="_blank" data-saferedirecturl="{{url('/').'/orders'}}">CONFIRM</a></td>
                          </tr>
                          </tbody>
                          </table>
                      </td>
                    </tr>

                    <tr>
                      <td style="font-size:14px;font-family:LucidaGrande,tahoma,verdana,arial,sans-serif;color:#3d4452;padding-top:6px;padding-bottom:6px; text-align: left;">
                        Thanks,<br>{{ $array['from_name'] }} Team
                      </td>
                    </tr>
                      <tr>
                        <td style="font-size:14px;font-family:LucidaGrande,tahoma,verdana,arial,sans-serif;color:#3d4452;padding-top:6px">&nbsp;</td></tr>
                      </tbody></table></td></tr></tbody>
                    </table></td></tr>
                    <tr style="border-bottom:solid 1px #e5e5e5">
                      <td height="15" style="line-height:15px" colspan="3">&nbsp;</td>
                    </tr>
                    
                  </tbody>
                    </table>
                  

            </td></tr>
          </tbody>
        </table>

      </div></div></div></div></div></div>
  

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



