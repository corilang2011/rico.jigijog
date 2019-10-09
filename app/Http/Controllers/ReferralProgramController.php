<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Referral;
use App\User;
use App\Wallet;
use Auth;
use DB;
use Session;
use ImageOptimizer;

class ReferralProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = auth()->user()->id;
        $users =  DB::table('users')->where('referred_by','LIKE',"%{$user_id}%")->paginate(25);
                
        return view('frontend.referral_program.index')->with(["users" => $users]);
        // echo "<pre>";
        // print_r($users);
    }
    public function admin_customer_index()
    {
        $users = User::all();
        return view('referral_program.customer_index')->with(["users" => $users]);
    }
    public function admin_seller_index()
    {
        $users = User::all();   
        return view('referral_program.seller_index')->with(["users" => $users]);
    }
    public function admin_id_verify()
    {
        $users =  DB::table('users')->orderBy('id', 'DESC')->get();
                
        return view('referral_program.id_verify')->with(["users" => $users]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->hasFile('id_image') && $request->id_status == "Requested"){
            $referral = new Referral;
            $referral->user_id = Auth::user()->id;
            $referral->id_status = $request->id_status;
            $referral->id_type = $request->id_type;
            $referral->id_number = $request->id_number;
            $referral->reward_points = $request->reward_points;
            $id_image = array();

            $id = auth()->user()->id;
            
            
            if($request->hasFile('id_image')){
                foreach ($request->id_image as $key => $photo) {
                    $path = $photo->store('uploads/id/photos');
                    array_push($id_image, $path);
                    // ImageOptimizer::optimize(base_path('public/').$path);
                }
                $referral->id_image = json_encode($id_image);
            }  
            if($referral->save()){
                if($referral->id_status == "Approved"){
                    $points = DB::table('users')
                        ->select('users.id','users.name', 'users.email', 'users.created_at', 'users.referred_by', 'refer_points')
                        ->join('referrals', 'referrals.user_id','=','users.id')
                        ->where([['referred_by','LIKE',"%{$id}%"],['email_verified_at', '!=', NULL]])
                        ->update(['refer_points' => '0']);
                        
                    $id = auth()->user()->id;
                    $user = User::find($id);
                    $payment_data = Referral::orderBy('id', 'desc')->where('user_id','=',$id)->first();
                    $user->balance += $payment_data->reward_points;
                    $user->save();
                    
                    $wallet = new Wallet;
                    $wallet->user_id = $user->id;
                    $wallet->amount = $payment_data->reward_points;
                    $wallet->title = "Referral Bonus";
                    $wallet->payment_details = "Incoming Fund Transfer";
                    $wallet->save();
                    
                    flash('Reward has been successfully claimed')->success();
                    return redirect()->route('referrals.index');
                }
                if($referral->id_status == "Requested"){
                    $refer_id_status = DB::table('users')
                        ->select('users.id','users.name', 'users.email', 'users.created_at', 'users.referred_by' , 'ref_id_status')
                        ->where('id','LIKE',"%{$id}%")
                        ->update(['ref_id_status' => 'Requested']);
                    flash('Request has been sent successfully')->success();
                    return redirect()->route('referrals.index');
                }
            }
            else{
                flash('Something went wrong')->error();
                return redirect()->route('referrals.index');
            }
        }
        elseif($request->id_status == "Approved"){
            $referral = new Referral;
            $referral->user_id = Auth::user()->id;
            $referral->id_status = $request->id_status;
            $referral->id_type = $request->id_type;
            $referral->id_number = $request->id_number;
            $referral->reward_points = $request->reward_points;
            $id_image = array();

            $id = auth()->user()->id;
            
            
            if($request->hasFile('id_image')){
                foreach ($request->id_image as $key => $photo) {
                    $path = $photo->store('uploads/id/photos');
                    array_push($id_image, $path);
                    // ImageOptimizer::optimize(base_path('public/').$path);
                }
                $referral->id_image = json_encode($id_image);
            }  
            if($referral->save()){
                if($referral->id_status == "Approved"){
                    $points = DB::table('users')
                        ->select('users.id','users.name', 'users.email', 'users.created_at', 'users.referred_by', 'refer_points')
                        ->join('referrals', 'referrals.user_id','=','users.id')
                        ->where([['referred_by','LIKE',"%{$id}%"],['email_verified_at', '!=', NULL]])
                        ->update(['refer_points' => '0']);
                        
                    $id = auth()->user()->id;
                    $user = User::find($id);
                    $payment_data = Referral::orderBy('id', 'desc')->where('user_id','=',$id)->first();
                    $user->balance += $payment_data->reward_points;
                    $user->save();
                    
                    $wallet = new Wallet;
                    $wallet->user_id = $user->id;
                    $wallet->amount = $payment_data->reward_points;
                    $wallet->title = "Referral Bonus";
                    $wallet->payment_details = "Incoming Fund Transfer";
                    $wallet->save();
                    
                    flash('Reward has been successfully claimed')->success();
                    return redirect()->route('referrals.index');
                }
                if($referral->id_status == "Requested"){
                    $refer_id_status = DB::table('users')
                        ->select('users.id','users.name', 'users.email', 'users.created_at', 'users.referred_by' , 'ref_id_status')
                        ->where('id','LIKE',"%{$id}%")
                        ->update(['ref_id_status' => 'Requested']);
                    flash('Request has been sent successfully')->success();
                    return redirect()->route('referrals.index');
                }
            }
            
            else{
                flash('Something went wrong')->error();
                return redirect()->route('referrals.index');
            }
        }
        else{
            flash('Image Required')->warning();
            return redirect()->route('referrals.index');
        }
    }
    /**
     *Show, Approved and Reject Request Claim Reward in admin panel.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show_verification_request($id)
    {
        $users =  DB::table('users')
                ->select('users.id','users.name', 'users.email', 'users.phone', 'users.address', 'referrals.id_type', 'referrals.id_number', 'referrals.id_image')
                ->join('referrals', 'referrals.user_id','=','users.id')
                ->where('user_id','LIKE',"%{$id}%")
                ->get()->toArray();
        return view('referral_program.verification')->with(["users" => $users]);
    }
    public function approve_referral($id)
    {
        
        $users =  DB::table('users')
                ->select('users.id', 'referrals.id_status', 'referrals.reward_status')
                ->join('referrals', 'referrals.user_id','=','users.id')
                ->where([['user_id','LIKE',"%{$id}%"], ['id_status', '!=', "Declined"]])
                ->update(['referrals.id_status' => 'Approved']);
        
        $points = DB::table('users')
                ->select('users.id','users.name', 'users.email', 'users.created_at', 'users.referred_by', 'refer_points')
                ->join('referrals', 'referrals.user_id','=','users.id')
                ->where([['referred_by','LIKE',"%{$id}%"],['email_verified_at', '!=', NULL]])
                ->update(['refer_points' => '0']);
                
        $refer_id_status = DB::table('users')
                ->select('users.id','users.name', 'users.email', 'users.created_at', 'users.referred_by' , 'ref_id_status')
                ->where('id','LIKE',"%{$id}%")
                ->update(['ref_id_status' => 'Approved']);
        
        $user = User::find($id);
        $payment_data = Referral::orderBy('id', 'desc')->where('user_id','=',$id)->first();
        $user->balance += $payment_data->reward_points;
        $user->save();
        
        $wallet = new Wallet;
        $wallet->user_id = $user->id;
        $wallet->amount = $payment_data->reward_points;
        $wallet->title = "Referral Bonus";
        $wallet->payment_details = "Incoming Fund Transfer";
        $wallet->save();
        
        if($user->save()){
            flash('ID Verification has been successfully Approved')->success();
            return redirect()->route('referral_program.admin_id_verify');
        }
        else{
            flash('Something went wrong')->error();
        };
    }

    public function reject_referral($id)
    {
        $users =  DB::table('users')
                ->select('users.id', 'referrals.id_status')
                ->join('referrals', 'referrals.user_id','=','users.id')
                ->where('user_id','LIKE',"%{$id}%")
                ->update(['referrals.id_status' => 'Declined']);
                
        $refer_id_status = DB::table('users')
                ->select('users.id','users.name', 'users.email', 'users.created_at', 'users.referred_by' , 'ref_id_status')
                ->where('id','LIKE',"%{$id}%")
                ->update(['ref_id_status' => 'Declined']);
                
        flash('ID Verification has been successfully Declined')->error();
        return redirect()->route('referral_program.admin_id_verify');
        
    }
}
