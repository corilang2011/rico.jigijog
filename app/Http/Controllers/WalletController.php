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
use Mail;
use App\Mail\InvoiceEmailManager;

class WalletController extends Controller
{
    public function index()
    {
        $wallets = Wallet::where('user_id', Auth::user()->id)->orWhere('send_to_user_id', Auth::user()->id)->orderBy('id', 'DESC')->paginate(15);
        return view('frontend.wallet', compact('wallets'));
    }
    public function admin_index()
    {
        $wallets = Wallet::where('title', "Wallet Withdrawal")->orderBy('id', 'DESC')->get();
        return view('wallet.payouts', compact('wallets'));
    }
    public function admin_wallet_index()
    {
        $wallets = Wallet::where('title', "Products Sold")->orderBy('id', 'DESC')->get();
        $admin_view = Wallet::where('title', "Products Sold")->update(['viewed' => 1]);
        return view('wallet.admin_wallet', compact('wallets'));
    }

    public function recharge(Request $request)
    {
        $user = Auth::user();
        $max = $user->balance;
        $maintaining = $max - $request->amount; 
        if($request->amount > $max || $request->send_amount > $max){
            flash(__('Error, Please try again'))->warning();
            return redirect()->route('wallet.index');
        }
        if($request->amount != NULL){
            if($maintaining < 200){
                flash(__('You need to have ₱200 as your maintaining balance.'))->important();
                return redirect()->route('wallet.index');
            }
        }
        if ($request->ca_number != NULL && $request->amount != NULL) {
            $wallet = new Wallet;
            $wallet->user_id = $user->id;
            $wallet->title = "Wallet Withdrawal";
            $wallet->amount = $request->amount;
            $wallet->payment_method = "Bank";
            $wallet->bank_name = $request->ba_name;
            $wallet->card_number = $request->ca_number;
            $wallet->cardholders_name = $request->ca_name;
            $wallet->payment_details = "Processing Fund Transfer";
            $wallet->save();

            flash(__('Request sent successfully'))->success();
            return redirect()->route('wallet.index');
        }
        elseif ($request->send_amount != NULL) {
            $wallet = new Wallet;
            $wallet->user_id = $user->id;
            $wallet->title = "Send Funds To Seller";
            $wallet->send_to_user_id = $request->seller;
            $wallet->amount = $request->send_amount;
            $wallet->payment_details = $request->details;
            $wallet->save();

            $user = User::find($user->id);
            $user->balance -= $request->send_amount;
            $user->save();

            $user = User::find($request->seller);
            $user->balance += $request->send_amount;
            $user->save();

            flash(__('Request sent successfully'))->success();
            return redirect()->route('wallet.index');
        }
        else{
            flash(__('Pleasess Fill the form Properly'))->warning();
            return redirect()->route('wallet.index');
        }

    }
    public function wallet_payment_done($payment_data, $payment_details){
        $user = Auth::user();
        $user->balance = $user->balance + $payment_data['amount'];
        $user->save();

        $wallet = new Wallet;
        $wallet->user_id = $user->id;
        $wallet->amount = $payment_data['amount'];
        $wallet->payment_method = $payment_data['payment_method'];
        $wallet->payment_details = $payment_details;
        $wallet->save();

        Session::forget('payment_data');
        Session::forget('payment_type');

        flash(__('Payment completed'))->success();
        return redirect()->route('wallet.index');
    }

    public function payments(Request $request)
    {
        $date = strtotime('now');
        $wallet = $request->user_id;
        $reference = $request->ref_number;
        
        if($reference != null){

            $update_referrence = Wallet::where([['user_id', $wallet], ['payment_details', "Processing Fund Transfer"]])->update(['ref_number' => $reference]);
            $release_date = Wallet::where([['user_id', $wallet], ['payment_details', "Processing Fund Transfer"]])->update(['date_payment_released' => $date]);
            $update_payment_status = Wallet::where([['user_id', $wallet], ['payment_details', "Processing Fund Transfer"]])->update(['payment_details' => 'Outgoing Fund Transfer']);

            $user = User::find($wallet);
            $user->balance -= $request->amount_requested;
            $user->save();
            flash(__('Payment completed'))->success();
                        
            /**wallet send email view data**/ 
            $array['view'] = 'emails.wallet_payment_notification';
            /**wallet send email data**/
            $array['subject'] = 'Wallet Requested Fund Transfer Confirmation (Ref# ' . $reference . ')';
            $array['from'] = env('MAIL_USERNAME');         
            $array['wallet_id'] = $wallet;            
            $array['reference'] = $reference;            
            $array['requested_amount'] = $request->amount_requested;
            $array['release_date'] = $date;
            $array['content'] = 'Congratulations' . $user->name . '!' . ' Your requested wallet has been successfullly transfered to your bank';
            $array['user_name'] = $user->name;  
            $array['email'] = $user->email; 
            
            foreach(\App\Wallet::where('user_id', $wallet)->get() as $key => $w){ 
            $array['cardholders_name'] = $w->cardholders_name;
            $array['bank_name'] = $w->bank_name;
            $array['account_number'] = $w->card_number;
            }
            //sends email to customer with the invoice pdf attached
            if(env('MAIL_USERNAME') != null && env('MAIL_PASSWORD') != null){
                
               /**send wallet notification html**/   
                Mail::to($user->email)->queue(new InvoiceEmailManager($array));
                /**end send wallet notification html**/ 
            }

        }else{
            flash(__('Cannot process payment.'))->warning();
        }
        
        return redirect()->route('wallet.admin_index');
    }

    public function wallet_details(Request $request)
    {
        $wallet = Wallet::findOrFail($request->order_id);
        return view('frontend.partials.wallet_details', compact('wallet'));
    }

}
