<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

class AjaxController extends Controller {
   public function index(Request $request) {
        $provCodeID = $_POST['provCodeID'];
        $cities = DB::table('refcitymun')->where('provCode', $provCodeID)->orderBy('citymunDesc')->get();
        $output = '<option value="" selected="true" disabled="disabled">Select City</option>';

        foreach($cities as $c){
            $output .= '<option value="'.$c->citymunCode.'">'.$c->citymunDesc.'</option><br>';
        }

        return response()->json(array('output'=> $output), 200);
   }

   public function barangay(Request $request){
        $cityID = $_POST['cityID'];
        $brgy = DB::table('refbrgy')->where('citymunCode', $cityID)->orderBy('brgyDesc')->get();
        $output = '<option value="" selected="true" disabled="disabled">Select Barangay</option>';

        foreach($brgy as $b){
            $output .= '<option value="'.$b->brgyCode.'">'.$b->brgyDesc.'</option><br>';
        }

        return response()->json(array('output'=> $output), 200);
   }
}