@php 			
	/*
	echo '<p> Wallet ID: ' . $array['wallet_id'] . '</p><br />';       
	echo '<p> Wallet Reference: ' . $array['reference'] . '</p><br />';       
	echo '<p> Wallet Requested Amount: ' . $array['requested_amount'] . '</p><br />';  
	echo '<p> Wallet Release Date: ' . $array['release_date'] . '</p><br />';  
	echo '<p> Wallet Message: ' . $array['content'] . '</p><br />';  
	echo '<p> Name: ' . $array['user_name'] . '</p><br />';  
	echo '<p> Email: ' . $array['email'] . '</p><br />';  
	echo '<p> Bank Name: ' . $array['bank_name'] . '</p><br />';  
	echo '<p> Bank Account Number: ' . $array['account_number'] . '</p><br />';  
	echo '<p> Bank Account Name: ' . $array['cardholders_name'] . '</p><br />';  
	*/
@endphp 

<div id=":30" class="ii gt"><div id=":2z" class="a3s aXjCH msg6763044414895180969"><u></u>
<div style="color:#565a5c;background-color:#f2f5f6;margin:0px 0px 0px 0px">
<div style="color:#565a5c;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;font-size:16px;border-collapse:collapse;max-width:800px;margin:0px auto;padding-top:25px;padding-left:25px;padding-right:25px">

    <div>

  <div>
    <div id="header-wrapper" style="margin-bottom:16px">
      <div>
        <a href="https://www.jigijog.com" style="text-decoration:none;outline:none" target="_blank" data-saferedirecturl="https://www.jigijog.com">
          <img style="width:209px;height:50px" src="https://www.jigijog.com/public/frontend/images/logo/jigifooter.png" class="CToWUd">
        </a>
      </div>
    </div>
  </div>

</div>

    <div id="body-wrapper" style="margin-bottom:20px">
        <div style="background-color:#ffffff;padding-top:1px; padding: 50px;">
            <div class="content">
                
                <div style="margin:0;padding:0">
                    <div style="margin:0;padding:0;margin-top:1em">
                        Hi <b>{{$array['user_name']}}</b>,
                    </div>

                    <div style="margin:0;padding:0;margin-top:1em">
                        Here is your requested wallet fund transfer confirmation.
                    </div>

                    <div style="margin:0;margin-bottom:2em;margin-top:1em">
                        <table style="width:100%;border-collapse:collapse;border:1px solid rgb(200,200,200)">
                            <thead style="background:#fa8a36;color:white">
                            <tr>
                                <td style="border:1px solid #fa8a36;padding:10px 20px">
                                    <span style="font-size:12px;margin:0">Reference Number:</span><br>
                                    <span>{{$array['reference']}}</span>
                                </td>
                                <td style="border:1px solid #fa8a36">
                                    <span style="font-size:12px">Transaction Date:</span><br>
                                    @php 
                                    //Transaction Date 
								       date_default_timezone_set('Asia/Manila');
								       //$release_date = date('F j, Y | g:i A  ', $array['release_date']);
                                    @endphp 
                                    <span>{{date('F j, Y | g:i A  ', $array['release_date'])}}</span>
                                </td>
                            </tr>
                            </thead>

                            <tbody>
                            <tr>
                                <td style="padding:10px 20px" colspan="2">
                                    <table>
                                        <tbody><tr>
                                            <td style="font-size:12px;width:100px">From Account:</td>
                                            <td style="padding:0 50px 0 10px">
                                                <div>WLC SUPPORT SALSES AND LEASING INC</div>
                                                <div><b>BPI</b></div>
                                                <div>********6102
                                                	<!--2931006102--></div>

                                            </td>
                                        </tr>
                                    </tbody></table>
                                </td>
                            </tr>

                            <tr style="background:#f4f5f4">
                                <td style="padding:10px 20px" colspan="2">
                                    <table>
                                        <tbody><tr>
                                            <td style="font-size:12px;width:100px">To Account:</td>
                                            <td style="padding:0 50px 0 10px">
                                                <div>{{$array['cardholders_name']}}</div>
                                                <div><b>{{$array['bank_name']}}</b></div>
                                                <div>{{$array['account_number']}}</div>
                                            </td>
                                        </tr>
                                    </tbody></table>
                                </td>
                            </tr>

                            <tr>
                                <td style="padding:10px 20px" colspan="2">
                                    <table>
                                        <tbody><tr>
                                            <td style="font-size:12px;width:100px">Amount of:</td>
                                            <td style="padding:0 50px 0 10px">
                                                <span style="font-size:10px;background:#211551;color:white;padding:1px 5px;border-radius:5px">PHP</span>
                                                <span>{{number_format($array['requested_amount'], 2)}}</span>
                                            </td>
                                        </tr>
                                    </tbody></table>
                                </td>
                            </tr>

                            

                            <tr style="background:#f4f5f4">
                                <td style="padding:10px 20px" colspan="2">
                                    <table>
                                        <tbody><tr>
                                            <td style="font-size:12px;width:100px">Message:</td>
                                            <td style="padding:0 50px 0 10px">
                                                <span>Wallet requested amount transfered succesfully.</span>
                                            </td>
                                        </tr>
                                    </tbody></table>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    

                    <div style="margin:0;padding:0;margin-top:1em">
                        <p>Please do not reply to this message. This is a system-generated email sent to <span><a href="mailto:{{$array['email']}}" target="_blank">{{$array['email']}}</a></span>.</p>
                        <p>Thank you for using Jigijog.</p>
                    </div>
                </div>
                
            </div>
        </div>
    </div>

    <div>

  <div>

  <div id="footer-wrapper">
    
    <div align="center">
      <table style="padding-top:30px;padding-bottom:30px;padding-left:30px;padding-right:30px;font-size:13px">
        <tbody>
        <tr>
          <td style="text-align:center">
            <div>
              <a href="https://www.jigijog.com/" style="text-decoration:none;outline:none" target="_blank" data-saferedirecturl="https://www.jigijog.com/">
                <img src="https://www.jigijog.com/public/frontend/images/logo/jigifooter.png" class="CToWUd" style="height:47px;margin-right:20px;border-radius:10px">
              </a>
            </div>
            <br>
            <div>
              <p style="margin-top:0;margin-bottom:0;color:#9b9b9b">
                <a href="https://www.jigijog.com/sellerpolicy" style="color:#9b9b9b;text-decoration:none" target="_blank" data-saferedirecturl="https://www.jigijog.com/sellerpolicy">Seller Policy</a>
|
<a href="https://www.jigijog.com/supportpolicy" style="color:#9b9b9b;text-decoration:none" target="_blank" data-saferedirecturl="https://www.jigijog.com/supportpolicy">Support Policy </a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="https://www.jigijog.com/returnpolicy" style="color:#9b9b9b;text-decoration:none" target="_blank" data-saferedirecturl="https://www.jigijog.com/returnpolicy">Return Policy</a>&nbsp; &nbsp;|&nbsp;&nbsp;<a href="https://www.jigijog.com/terms" style="color:#9b9b9b;text-decoration:none" target="_blank" data-saferedirecturl="https://www.jigijog.com/terms">Terms &amp; Conditions</a>
              </p>
            </div>
            <div style="margin-top:10px">
              <a href="https://www.jigijog.com" style="text-decoration:none;outline:none;color:#9b9b9b" target="_blank" data-saferedirecturl="https://www.jigijog.com">www.jigijog.com</a>
            </div>
            <div style="margin-top:20px">
              <p style="text-decoration:none;outline:none;color:#9b9b9b;text-align:center">This is an electronic information from Jigijog to keep you updated on the latest promotions and/or new products.</p>
            </div>
          </td>
        </tr>
        </tbody>
      </table><div class="yj6qo"></div><div class="adL">
    </div></div><div class="adL">
  </div></div><div class="adL">

  </div></div><div class="adL">

</div></div><div class="adL">

</div></div><div class="adL">

</div></div>
</div></div>