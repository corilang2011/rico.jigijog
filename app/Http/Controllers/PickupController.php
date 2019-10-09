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

class PickupController extends Controller
{
    //
    public function index()
    {
        // $orders = DB::table('orders')
        //             ->orderBy('orders.created_at', 'asc')
        //             ->join('order_details', 'orders.id', '=', 'order_details.order_id')
        //             ->where('order_details.seller_id', '!=', Auth::user()->id)
        //             ->where('pick_from', '!=', null)
        //             ->select('orders.id')
        //             ->distinct()->get();
        //             
        $orders = DB::table('orders')
                    ->orderBy('code', 'desc')
                    ->join('order_details', 'orders.id', '=', 'order_details.order_id')
                    //->where('order_details.seller_id', Auth::user()->id)
                    ->select('orders.id')
                    ->distinct()->get();

        return view('pickup_list.index', compact('orders'));
    }

    public function view($order_id, $seller_id)
    {
        $order = \App\Order::find(decrypt($order_id));
        $seller_id = decrypt($seller_id);
        return view('pickup_list.status', compact('order', 'seller_id'));
    }
}
