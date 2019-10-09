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

class JigijogSalesController extends Controller
{
    //
    public function index()
    {         
        $orders = DB::table('order_details')
                    ->orderBy('id', 'desc')
                    ->where('seller_id', 186)
                    ->where('payment_status', 'paid')->get();

        return view('jigijog_sales.index', compact('orders'));
    }
}
