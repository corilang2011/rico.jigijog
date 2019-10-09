<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\PaypalController;
use App\Http\Controllers\StripePaymentController;
use App\Http\Controllers\PublicSslCommerzPaymentController;
use Auth;
use Session;
use App\Wallet;
use App\User;

class ReceiptController extends Controller
{
    //
    public function index()
    {    
        // $wallets = Wallet::where([ ['title', '=', 'Wallet Withdrawal'], ['receipt', '=', null]])->orderBy('id', 'DESC')->get();
        $wallets = Wallet::where('title', "Wallet Withdrawal")->orderBy('id', 'DESC')->get();
        return view('receipt.index', compact('wallets'));
    }

    public function image_upload(Request $request)
    {
    	$receipt = Wallet::findOrFail($request->id);
        $id_image = array();

        if($request->hasFile('id_image')){
            foreach ($request->id_image as $key => $photo) {
                $path = $photo->store('uploads/id/photos');
                array_push($id_image, $path);
            }
            $receipt->receipt = json_encode($id_image);
            $receipt->date_of_trans = $request->DoT;

            if(!empty($request->DoT)){
		        if($receipt->save()){
		            flash('Image has been uploaded successfully')->success();
		            return redirect()->route('receipt.index');
		        }else{
		            flash('Something went wrong')->error();
		            return redirect()->route('receipt.index');
		        }
            }else{
	        	flash('Please input date of transaction')->error();
	        	return redirect()->route('receipt.index');
            }
        }else{
        	flash('Something went wrong')->error();
        	return redirect()->route('receipt.index');
        }
    }
}
