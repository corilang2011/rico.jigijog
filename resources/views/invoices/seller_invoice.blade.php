<!DOCTYPE html><html lang="en">  <head>    
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>    
<title>INVOICE {{ $order->code }}</title>    
<style media="all">  *{    margin: 0;    padding: 0;    line-height: 1.5;    font-family: 'Open Sans', sans-serif;    color: #333542;  }  div{    font-size: 1rem;  }  .gry-color *,  .gry-color{    color:#878f9c;  }*/    @font-face {  font-family: SourceSansPro;  src: url({{ asset('css/example-css/SourceSansPro-Regular.ttf') }});}.clearfix:after {  content: "";  display: table;  clear: both;}a {  color: #0087C3;  text-decoration: none;}body {  position: relative;  width: 21cm;    height: 29.7cm;   margin: 0 auto;   color: #555555;  background: #FFFFFF;   font-family: Arial, sans-serif;   font-size: 14px;   font-family: SourceSansPro;}header {  padding: 10px 0;  margin-bottom: 20px;  border-bottom: 1px solid #AAAAAA;}#logo {  float: left;  margin-top: 8px;}#logo img {  height: 70px;}#company {  float: right;  text-align: right;}#details {  margin-bottom: 50px;}#client {  padding-left: 6px;  border-left: 6px solid #0087C3;  float: left;}#client .to {  color: #777777;}h2.name {  font-size: 1.4em;  font-weight: normal;  margin: 0;}#invoice {  float: right;  text-align: right;}#invoice h1 {  color: #0087C3;  font-size: 2.4em;  line-height: 1em;  font-weight: normal;  margin: 0  0 10px 0;}#invoice .date {  font-size: 1.1em;  color: #777777;}#invoice .ocode {  font-size: 1.1em;  color: #777777;}table {  width: 100%;  border-collapse: collapse;  border-spacing: 0;  margin-bottom: 20px;}table th,table td {  /*padding: 20px;*/  padding: 10px;  background: #EEEEEE;  text-align: center;  border-bottom: 1px solid #FFFFFF;}table th {  white-space: nowrap;          font-weight: normal;}table td {  text-align: right;}table td h3{  color: #57B223;  font-size: 1.2em;  font-weight: normal;  margin: 0 0 0.2em 0;}table .no {  color: #FFFFFF;  font-size: 1.6em;  background: #ff4e00; }table .desc {  text-align: left;}table .unit {  /*background: #DDDDDD;*/}table .qty {  background: #DDDDDD;}table .tax {   background: #DDDDDD;}table .total {  background: #ff4e00;  color: #FFFFFF;}table td.unit,table td.qty,table td.total {  font-size: 1.2em;}table tbody tr:last-child td {  border: none;}table tfoot td {  padding: 10px 20px;  background: #FFFFFF;  border-bottom: none;  font-size: 1.2em;  white-space: nowrap;   border-top: 1px solid #AAAAAA; }table tfoot tr:first-child td {  border-top: none; }table tfoot tr:last-child td {  color: #57B223;  font-size: 1.4em;  border-top: 1px solid #57B223; }table tfoot tr td:first-child {  border: none;}#thanks{  font-size: 2em;  margin-bottom: 50px;}#notices{  padding-left: 6px;  border-left: 6px solid #0087C3;  }#notices .notice {  font-size: 1.2em;}footer {  color: #777777;  width: 100%;  height: 30px;  position: absolute;  bottom: 0;  border-top: 1px solid #AAAAAA;  padding: 8px 0;  text-align: center;}</style>
	@php    
		$generalsetting = \App\GeneralSetting::first();  
		$logo = $generalsetting->logo;  
		if( $logo != NULL ){    
		$invlogo = $generalsetting->logo;    
	}elseif( $logo != "" ){    
		$invlogo = $generalsetting->logo;    
	}else{    
		$invlogo = asset('uploads/admin_logo/jigifooter.png');  
	}

	/**Get Seller Shop name and logo **/
    if(Auth::user()->user_type == 'seller'){
      	
      	if(Auth::user()->shop->logo != null){

	      	/**Seller shop logo**/
	      	$shop_logo = Auth::user()->shop->logo;
	      	$shop_name = Auth::user()->shop->name;
	      	$shop_address = Auth::user()->shop->address;
	      	$phone = Auth::user()->phone;
	      	$visible = "display: block;";

		}else{
			/**Jigijog shop logo**/
			$shop_logo = asset('uploads/admin_logo/jigifooter.png');
			$shop_name = $generalsetting->site_name;
			$shop_address = $generalsetting->address;
			$phone = Auth::user()->phone;
			$visible = "display: none;";
		}
		

  	}else{

  		/**Jigijog shop logo**/
  		if($generalsetting->logo != null){
  			$shop_logo = $generalsetting->logo;
  			$shop_name = $generalsetting->site_name;
  			$shop_address = $generalsetting->address;
  			$phone = $generalsetting->phone;
  			$visible = "display: none;";
  		}else{

	  		$shop_logo = asset('uploads/admin_logo/jigifooter.png');
	  		$shop_name = $generalsetting->site_name;
	  		$shop_address = $generalsetting->address;
	  		$phone = $generalsetting->phone;
	  		$visible = "display: none;";
	  	}

  	}

	@endphp  
</head>  
<body>    
	<header class="clearfix" style="">      
	<div id="logo">      	
	<img src="{{ url('/public') }}/{{ $shop_logo }}" height="100" style="display:inline-block;">	           
</div>      

<div id="company" style="width: 300px;">        
	<h2 class="name">{{ $shop_name }}</h2>        
	<div style="{{ $visible }}">{{ $shop_address }}</div>        
	<div style="{{ $visible }}">Phone: {{ $phone }}</div>        
	    
</div>      
	   
</div>    
</header>    
<main>      
		@php      
		$shipping_address = json_decode($order->shipping_address);      
		$i=1;          
		@endphp      
		<div id="details" class="clearfix">        
			<div id="client">          
				<div class="to">INVOICE TO:</div>          
				<h2 class="name">{{ $shipping_address->name }}</h2>          
				<div class="address">{{ $shipping_address->address }}, {{ $shipping_address->city }}, {{ $shipping_address->country }}</div>          
				<div class="email"><a href="mailto:{{ $shipping_address->email }}">Email: {{ $shipping_address->email }}</a></div>          
				<div class="phone">Phone: {{ $shipping_address->phone }}</div>        
			</div>        
			<div id="invoice" style="width: 300px;">          
			<h1>RECEIPT</h1>          
			<div class="ocode">Order ID: <span class=" strong">{{ $order->code }}</span></div>          <div class="date">Order Date: <span class=" strong">{{ date('d-m-Y', $order->date) }}</span></div>          
			<div class="date"><!--// Due Date: 30/06/2014 //--></div>        
		</div>      </div>      
		<table border="0" cellspacing="0" cellpadding="0">        
			<thead>          
				<tr>            
					<th class="no">#</th>            
					<th class="desc">DESCRIPTION</th>            
					<th class="qty">QUANTITY</th>            
					<th class="unit">UNIT PRICE</th>            
					<th class="tax">TAX</th>            
					<th class="total">TOTAL</th>          
				</tr>        
			</thead>        
			<tbody>                    
				@foreach ($order->orderDetails->where('seller_id', Auth::user()->id) as $key => $orderDetail)  

				@php                    
				if($orderDetail->variation != "" ){            
				$show_style = "";          
					}else{            
					$show_style = "display:none";           
				}   

				//GRAND TOTAL 
			     $grand_amt = single_price($order->grand_total);
			     $grand_amt = getAmount($grand_amt);

			     //Order Date
			     $order_date = date('m/d/Y | h:i:s', $order->date);

			     //Payment method
			     if($order->payment_type == 'cash_on_delivery'){
					 $payment_method = 'COD';
				  }else{
					  $payment_method = $order->payment_type;
				  }
				         
				@endphp                    
				<tr>            
					<td class="no">{{ $i++ }}</td>            
					<td class="desc">{{ $orderDetail->product->name }} <span style="{{$show_style}}">({{ $orderDetail->variation }})</span></td>            
					<td class="qty">{{ $orderDetail->quantity }}</td>            
					<td class="unit">{{ single_price($orderDetail->price/$orderDetail->quantity) }}</td> 
					<td class="tax">{{ single_price($orderDetail->tax/$orderDetail->quantity) }}</td>
					<td class="total">{{ single_price($orderDetail->price+$orderDetail->tax) }}</td>
					</tr>                    
				@endforeach                  
			</tbody>        
			<tfoot>          
				<tr>                        
					<td colspan="5">SUBTOTAL</td>            
					<td>{{ single_price($order->orderDetails->where('seller_id', Auth::user()->id)->sum('price')) }}</td>          
				</tr>          
				<tr>                       
					<td colspan="5">SHIPPING COST</td>            
				<td>{{ single_price($order->orderDetails->where('seller_id', Auth::user()->id)->sum('shipping_cost')) }}</td>          
			</tr>          
			<tr>                        
				<td colspan="5">TOTAL TAX 25%</td>            
				<td>{{ single_price($order->orderDetails->where('seller_id', Auth::user()->id)->sum('tax')) }}
				</td>          
			</tr>          
				<tr>                        
				<td colspan="5" style="color: #0087C3;">GRAND TOTAL</td>            
				<td style="color: #0087C3; border-top: 4px dotted #0087C3;">{{ single_price($order->grand_total) }}</td>          
			</tr>        
		</tfoot>      
	</table>      
	<div id="thanks">Thank you!</div>      
	<!--
      <div id="notices">
        <div>NOTICE:</div>
        <div class="notice">A finance charge will be made on unpaid balances after 30 days.</div>
      </div>
	  //-->  
</main>    
<footer>      Invoice was created on a computer and is valid without the signature and seal.    
</footer>  
</body>
</html>