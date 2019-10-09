<?php



namespace App\Http\Controllers;



use Illuminate\Http\Request;

use App\ProductGo;
use App\Product;



class ScriptController extends Controller

{
    // public function prodTransferScript () {

    //     $products = Product::all();

    //     foreach ( $products as $product ) {

    //         $product_update = Product::find($product->id);
    //         $product_update->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $product_update->name)).'-'.str_random(5);
    //         $product_update->save();

    //     }

    //     return 'Check';
    // }
    
    // ====================================================================================================

    public function prodTransferScript () {

        $products_old = ProductGo::all();

        foreach ( $products_old as $product_old ) {

            $product_new = new Product();

            $product_new->name = $product_old->prod_name;  //-------------
            $product_new->added_by = 'admin';
            $product_new->user_id = 9;
            $product_new->category_id = 1;
            $product_new->subcategory_id = 1;
            $product_new->subsubcategory_id = 1;
            $product_new->brand_id = 1;
            $product_new->photos = json_encode(array('uploads/products/all/'.$product_old->prod_img));
            $product_new->thumbnail_img = 'uploads/products/all/'.$product_old->prod_img;
            $product_new->featured_img = 'uploads/products/all/'.$product_old->prod_img;
            $product_new->flash_deal_img = 'uploads/products/all/'.$product_old->prod_img;
            $product_new->video_provider = 'youtube';
            $product_new->video_link = NULL;
            $product_new->tags = 'All';
            $product_new->description = $product_old->prod_category; //----------------------------
            $product_new->unit_price = $product_old->prod_price;  //---------------------------
            $product_new->purchase_price = 0;
            $product_new->choice_options = json_encode(array());
            $product_new->colors = json_encode(array());
            $product_new->variations = json_encode(array());
            $product_new->todays_deal = 1;
            $product_new->published = 1;
            $product_new->featured = 1;
            $product_new->current_stock = 0;
            $product_new->unit = 'pc';
            $product_new->discount = 0.00;
            $product_new->discount_type = 'amount';
            $product_new->tax = 0.00;
            $product_new->tax_type = 'amount';
            $product_new->shipping_type = 'free';
            $product_new->shipping_cost = 0.00;
            $product_new->num_of_sale = 0;
            $product_new->meta_title = NULL;
            $product_new->meta_description = NULL;
            $product_new->meta_img = NULL;
            $product_new->pdf = NULL;
            $product_new->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $product_old->prod_name)).'-'.str_random(5);

            $product_new->save();
        }

        return 'Check';

    }

}

