<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Category;
use App\Language;
use App\Adjustment;
use Auth;
use App\SubSubCategory;
use Session;
use ImageOptimizer;
use Intervention\Image\ImageManagerStatic as Image;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function admin_products()
    {
        $type = 'In House';
        $products = Product::where('added_by', 'admin')->orderBy('created_at', 'desc')->get();
        return view('products.index', compact('products','type'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     
    public function jigijog_products()
    {
        $type = 'Seller';
        $products = Product::where('user_id', 186)->orderBy('created_at', 'desc')->get();
        return view('products.jigijog', compact('products','type'));
    }
    
    public function seller_products()
    {
        $type = 'Seller';
        $products = Product::where('added_by', 'seller')->orderBy('created_at', 'desc')->get();
        return view('products.index', compact('products','type'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request->all());
        $product = new Product;
        $product->name = $request->name;
        $product->added_by = $request->added_by;
        $product->user_id = Auth::user()->id;
        $product->category_id = $request->category_id;
        $product->subcategory_id = $request->subcategory_id;
        $product->subsubcategory_id = $request->subsubcategory_id;
        $product->brand_id = $request->brand_id;

        if( Auth::user()->id == 186 )
            $product->purchase_price = $request->purchase_price;
            
        $photos = array();

        if($request->hasFile('photos')){
            /*foreach ($request->photos as $key => $photo) {
                $path = $photo->store('uploads/products/photos');
                array_push($photos, $path);
                //ImageOptimizer::optimize(base_path('public/').$path);
            }*/

            foreach ($request->photos as $key => $photo) {
                
                $img_filename = $photo->getClientOriginalName();
                $imgfilename = getShopImageFilename($img_filename);
                $imagename = pathinfo($imgfilename, PATHINFO_FILENAME);
                //$filename = $imagename. time().'.'.$photo->getClientOriginalExtension();
                $filename = uniqid().'.'.$photo->getClientOriginalExtension();
                $image_resize = Image::make($photo->getRealPath());  
                $image_resize->resize(1080, 1080,function($constraint) {
                    $constraint->aspectRatio();
                });
                $image_resize->save(public_path('uploads/products/photos/' .$filename));

                $dir_dest = 'public/uploads/products/photos';
                $handle = new image_Upload(public_path('uploads/products/photos/' .$filename));
                // then we check if the file has been uploaded properly
                // in its *temporary* location in the server (often, it is /tmp)
                if ($handle->uploaded) {
                    /** size an ration **/
                    $handle->image_resize            = true;
                    $handle->image_ratio_y           = true;
                    $handle->image_x                 = 1080;
                    //$handle->image_reflection_height = '25%';
                    //$handle->image_contrast          = 50;
                    /** processing image **/
                    $handle->process($dir_dest);
                    /** cleaning **/ 
                    $handle-> clean();
                } 
                /**image output**/
                $path = 'uploads/products/photos/'.$handle->file_dst_name; 
            
                
                array_push($photos, $path);
                }
                       

            //$product->photos = json_encode($photos);
        }

        $product->photos = json_encode($photos);

        /*if($request->hasFile('thumbnail_img')){
            $product->thumbnail_img = $request->thumbnail_img->store('uploads/products/thumbnail');
            //ImageOptimizer::optimize(base_path('public/').$product->thumbnail_img);
        }

        if($request->hasFile('featured_img')){
            $product->featured_img = $request->featured_img->store('uploads/products/featured');
            //ImageOptimizer::optimize(base_path('public/').$product->featured_img);
        }

        if($request->hasFile('flash_deal_img')){
            $product->flash_deal_img = $request->flash_deal_img->store('uploads/products/flash_deal');
            //ImageOptimizer::optimize(base_path('public/').$product->flash_deal_img);
        }*/

        if($request->hasFile('thumbnail_img')){
            //$product->thumbnail_img = $request->thumbnail_img->store('uploads/products/thumbnail');
            //ImageOptimizer::optimize(base_path('public/').$product->thumbnail_img);
            $image = $request->file('thumbnail_img');
            //get image filename 
            $img_filename = $image->getClientOriginalName();
            //call function to changes white space to underscore_
            $imgfilename = getShopImageFilename($img_filename);
            $imagename = pathinfo($imgfilename, PATHINFO_FILENAME);
            //$filename = $imagename. time().'.'.$image->getClientOriginalExtension();
            $filename = uniqid().'.'.$image->getClientOriginalExtension();
            $image_resize = Image::make($image->getRealPath());  
            $image_resize->resize(290, 300,function($constraint) {
                $constraint->aspectRatio();
            });

            $image_resize->save(public_path('uploads/products/thumbnail/' .$filename));
            //$product->thumbnail_img = 'uploads/products/thumbnail/' .$filename;

            $dir_dest = 'public/uploads/products/thumbnail';
            $handle = new image_Upload(public_path('uploads/products/thumbnail/' .$filename));
            // then we check if the file has been uploaded properly
            // in its *temporary* location in the server (often, it is /tmp)
            if ($handle->uploaded) {
                /** size an ration **/
                $handle->image_resize            = true;
                $handle->image_ratio_y           = true;
                $handle->image_x                 = 290;
                $handle->image_x                 = 300;
                //$handle->image_reflection_height = '25%';
                //$handle->image_contrast          = 50;
                /** processing image **/
                $handle->process($dir_dest);
                /** cleaning **/ 
                $handle-> clean();
            } 
            
            /**image output**/
            $product->thumbnail_img = 'uploads/products/thumbnail/'.$handle->file_dst_name;
   
        }

        if($request->hasFile('featured_img')){
            //$product->featured_img = $request->featured_img->store('uploads/products/featured');
            //ImageOptimizer::optimize(base_path('public/').$product->featured_img);
            $image = $request->file('featured_img');
            //get image filename 
            $img_filename = $image->getClientOriginalName();
            //call function to changes white space to underscore_
            $imgfilename = getShopImageFilename($img_filename);
            $imagename = pathinfo($imgfilename, PATHINFO_FILENAME);
            //$filename = $imagename. time().'.'.$image->getClientOriginalExtension();
            $filename = uniqid().'.'.$image->getClientOriginalExtension();
            $image_resize = Image::make($image->getRealPath());  
            $image_resize->resize(290, 300,function($constraint) {
                $constraint->aspectRatio();
            });

            $image_resize->save(public_path('uploads/products/featured/' .$filename));
            //$product->featured_img = 'uploads/products/featured/' .$filename; 

            $dir_dest = 'public/uploads/products/featured';
            $handle = new image_Upload(public_path('uploads/products/featured/' .$filename));
            // then we check if the file has been uploaded properly
            // in its *temporary* location in the server (often, it is /tmp)
            if ($handle->uploaded) {
                /** size an ration **/
                $handle->image_resize            = true;
                $handle->image_ratio_y           = true;
                $handle->image_x                 = 290;
                $handle->image_x                 = 300;
                //$handle->image_reflection_height = '25%';
                //$handle->image_contrast          = 50;
                /** processing image **/
                $handle->process($dir_dest);
                /** cleaning **/ 
                $handle-> clean();
            } 
          
            /**image output**/
            $product->featured_img = 'uploads/products/featured/'.$handle->file_dst_name;
            
        }

        if($request->hasFile('flash_deal_img')){
            //$product->flash_deal_img = $request->flash_deal_img->store('uploads/products/flash_deal');
            //ImageOptimizer::optimize(base_path('public/').$product->flash_deal_img);

            $image = $request->file('flash_deal_img');
            //get image filename 
            $img_filename = $image->getClientOriginalName();
            //call function to changes white space to underscore_
            $imgfilename = getShopImageFilename($img_filename);
            $imagename = pathinfo($imgfilename, PATHINFO_FILENAME);
            //$filename = $imagename. time().'.'.$image->getClientOriginalExtension();
            $filename = uniqid().'.'.$image->getClientOriginalExtension();
            $image_resize = Image::make($image->getRealPath());  
            $image_resize->resize(290, 300,function($constraint) {
                $constraint->aspectRatio();
            });

            $image_resize->save(public_path('uploads/products/flash_deal/' .$filename));
            //$product->flash_deal_img = 'uploads/products/flash_deal/' .$filename; 
            
            $dir_dest = 'public/products/flash_deal';
            $handle = new image_Upload(public_path('uploads/products/flash_deal/' .$filename));
            // then we check if the file has been uploaded properly
            // in its *temporary* location in the server (often, it is /tmp)
            if ($handle->uploaded) {
                /** size an ration **/
                $handle->image_resize            = true;
                $handle->image_ratio_y           = true;
                $handle->image_x                 = 290;
                $handle->image_x                 = 300;
                //$handle->image_reflection_height = '25%';
                //$handle->image_contrast          = 50;
                /** processing image **/
                $handle->process($dir_dest);
                /** cleaning **/ 
                $handle-> clean();
            } 
          
            /**image output**/
            $product->flash_deal_img = 'uploads/products/flash_deal/'.$handle->file_dst_name;
            
        }

        $product->unit = $request->unit;
        $product->tags = implode('|',$request->tags);
        $product->description = $request->description;
        $product->video_provider = $request->video_provider;
        $product->video_link = $request->video_link;
        $product->unit_price = $request->unit_price;
        $product->purchase_price = $request->purchase_price;
        $product->tax = $request->tax;
        $product->tax_type = $request->tax_type;
        $product->discount = $request->discount;
        $product->discount_type = $request->discount_type;
        $product->shipping_type = $request->shipping_type;
        $product->weight = $request->weight;
        $product->parcel_size = $request->length.",".$request->width.",".$request->height;
        if($request->shipping_type == 'free'){
            $product->shipping_cost = 0;
        }
        elseif ($request->shipping_type == 'local_pickup') {
            $product->shipping_cost = $request->local_pickup_shipping_cost;
        }
        elseif ($request->shipping_type == 'flat_rate') {
            //$product->shipping_cost = $request->flat_shipping_cost;
            $product->shipping_cost = $request->shipping_cost;
        }
        $product->meta_title = $request->meta_title;
        $product->meta_description = $request->meta_description;

        if($request->hasFile('meta_img')){
            //$product->meta_img = $request->meta_img->store('uploads/products/meta');
            //ImageOptimizer::optimize(base_path('public/').$product->meta_img);
            $image = $request->file('meta_img');
            //get image filename 
            $img_filename = $image->getClientOriginalName();
            //call function to changes white space to underscore_
            $imgfilename = getShopImageFilename($img_filename);
            $imagename = pathinfo($imgfilename, PATHINFO_FILENAME);
            //$filename = $imagename. time().'.'.$image->getClientOriginalExtension();
            $filename = uniqid().'.'.$image->getClientOriginalExtension();
            $image_resize = Image::make($image->getRealPath());  
            $image_resize->resize(290, 300,function($constraint) {
                $constraint->aspectRatio();
            });

            $image_resize->save(public_path('uploads/products/meta/' .$filename));
            //$product->meta_img = 'uploads/products/meta/' .$filename; 
            $dir_dest = 'public/uploads/products/meta';
            $handle = new image_Upload(public_path('uploads/products/meta/' .$filename));
            // then we check if the file has been uploaded properly
            // in its *temporary* location in the server (often, it is /tmp)
            if ($handle->uploaded) {
                /** size an ration **/
                $handle->image_resize            = true;
                $handle->image_ratio_y           = true;
                $handle->image_x                 = 290;
                $handle->image_x                 = 300;
                //$handle->image_reflection_height = '25%';
                //$handle->image_contrast          = 50;
                /** processing image **/
                $handle->process($dir_dest);
                /** cleaning **/ 
                $handle-> clean();
            } 
            
            /**image output**/
            $product->meta_img = 'uploads/products/meta/'.$handle->file_dst_name;
        }

        if($request->hasFile('pdf')){
            $product->pdf = $request->pdf->store('uploads/products/pdf');
        }

        $product->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)).'-'.str_random(5);

        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
            $product->colors = json_encode($request->colors);
        }
        else {
            $colors = array();
            $product->colors = json_encode($colors);
        }

        $choice_options = array();

        if($request->has('choice')){
            foreach ($request->choice_no as $key => $no) {
                $str = 'choice_options_'.$no;
                $item['name'] = 'choice_'.$no;
                $item['title'] = $request->choice[$key];
                $item['options'] = explode(',', implode('|', $request[$str]));
                array_push($choice_options, $item);
            }
        }

        $product->choice_options = json_encode($choice_options);

        $variations = array();

        //combinations start
        $options = array();
        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
            $colors_active = 1;
            array_push($options, $request->colors);
        }

        if($request->has('choice_no')){
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_'.$no;
                $my_str = implode('|',$request[$name]);
                array_push($options, explode(',', $my_str));
            }
        }

        //Generates the combinations of customer choice options
        $combinations = combinations($options);
        if(count($combinations[0]) > 0){
            foreach ($combinations as $key => $combination){
                $str = '';
                foreach ($combination as $key => $item){
                    if($key > 0 ){
                        $str .= '-'.str_replace(' ', '', $item);
                    }
                    else{
                        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
                            $color_name = \App\Color::where('code', $item)->first()->name;
                            $str .= $color_name;
                        }
                        else{
                            $str .= str_replace(' ', '', $item);
                        }
                    }
                }
                $item = array();
                $item['price'] = $request['price_'.str_replace('.', '_', $str)];
                $item['sku'] = $request['sku_'.str_replace('.', '_', $str)];
                $item['qty'] = $request['qty_'.str_replace('.', '_', $str)];
                $variations[$str] = $item;
            }
        }
        //combinations end

        $product->variations = json_encode($variations);

        $data = openJSONFile('en');
        $data[$product->name] = $product->name;
        saveJSONFile('en', $data);

        if($product->save()){
            flash(__('Product has been inserted successfully'))->success();
            if(Auth::user()->user_type == 'admin'){
                return redirect()->route('products.admin');
            }
            else{
                return redirect()->route('seller.products');
            }
        }
        else{
            flash(__('Something went wrong'))->error();
            return back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function admin_product_edit($id)
    {
        $product = Product::findOrFail(decrypt($id));
        //dd(json_decode($product->price_variations)->choices_0_S_price);
        $tags = json_decode($product->tags);
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories', 'tags'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function seller_product_edit($id)
    {
        $product = Product::findOrFail(decrypt($id));
        //dd(json_decode($product->price_variations)->choices_0_S_price);
        $tags = json_decode($product->tags);
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        if($request->prev_price != $request->unit_price && Auth::user()->id == 186){
            $adjustment = new Adjustment;
            $adjustment->product_id = $id;
            $adjustment->from = $request->prev_price;
            $adjustment->to = $request->unit_price;
            $adjustment->save();
        }
        
        $product = Product::findOrFail($id);
        $product->name = $request->name;
        $product->category_id = $request->category_id;
        $product->subcategory_id = $request->subcategory_id;
        $product->subsubcategory_id = $request->subsubcategory_id;
        $product->brand_id = $request->brand_id;
        
        if( Auth::user()->id == 186 )
            $product->purchase_price = $request->purchase_price;
            
        if($request->has('previous_photos')){
            $photos = $request->previous_photos;
        }
        else{
            $photos = array();
        }

        if($request->hasFile('photos')){
            /*foreach ($request->photos as $key => $photo) {
                $path = $photo->store('uploads/products/photos');
                array_push($photos, $path);
                //ImageOptimizer::optimize(base_path('public/').$path);
            }*/

            foreach ($request->photos as $key => $photo) {
                
                $img_filename = $photo->getClientOriginalName();
                $imgfilename = getShopImageFilename($img_filename);
                $imagename = pathinfo($imgfilename, PATHINFO_FILENAME);
                //$filename = $imagename. time().'.'.$photo->getClientOriginalExtension();
                $filename = uniqid().'.'.$photo->getClientOriginalExtension();
                $image_resize = Image::make($photo->getRealPath());  
                $image_resize->resize(1080, 1080,function($constraint) {
                    $constraint->aspectRatio();
                });
                $image_resize->save(public_path('uploads/products/photos/' .$filename));

                $dir_dest = 'public/uploads/products/photos';
                $handle = new image_Upload(public_path('uploads/products/photos/' .$filename));
                // then we check if the file has been uploaded properly
                // in its *temporary* location in the server (often, it is /tmp)
                if ($handle->uploaded) {
                    /** size an ration **/
                    $handle->image_resize            = true;
                    $handle->image_ratio_y           = true;
                    $handle->image_x                 = 1080;
                    //$handle->image_reflection_height = '25%';
                    //$handle->image_contrast          = 50;
                    /** processing image **/
                    $handle->process($dir_dest);
                    /** cleaning **/ 
                    $handle-> clean();
                } 
                /**image output**/
                $path = 'uploads/products/photos/'.$handle->file_dst_name; 
            
                
                array_push($photos, $path);
                }
                       

            //$product->photos = json_encode($photos);
        }
        $product->photos = json_encode($photos);

        $product->thumbnail_img = $request->previous_thumbnail_img;
        //update thumbnail 
        if($request->hasFile('thumbnail_img')){
            //$product->thumbnail_img = $request->thumbnail_img->store('uploads/products/thumbnail');
            //ImageOptimizer::optimize(base_path('public/').$product->thumbnail_img);
            $image = $request->file('thumbnail_img');
            //get image filename 
            $img_filename = $image->getClientOriginalName();
            //call function to changes white space to underscore_
            $imgfilename = getShopImageFilename($img_filename);
            $imagename = pathinfo($imgfilename, PATHINFO_FILENAME);
            //$filename = $imagename. time().'.'.$image->getClientOriginalExtension();
            $filename = uniqid().'.'.$image->getClientOriginalExtension();
            $image_resize = Image::make($image->getRealPath());  
            $image_resize->resize(290, 300,function($constraint) {
                $constraint->aspectRatio();
            });

            $image_resize->save(public_path('uploads/products/thumbnail/' .$filename));
            //$product->thumbnail_img = 'uploads/products/thumbnail/' .$filename;

            $dir_dest = 'public/uploads/products/thumbnail';
            $handle = new image_Upload(public_path('uploads/products/thumbnail/' .$filename));
            // then we check if the file has been uploaded properly
            // in its *temporary* location in the server (often, it is /tmp)
            if ($handle->uploaded) {
                /** size an ration **/
                $handle->image_resize            = true;
                $handle->image_ratio_y           = true;
                $handle->image_x                 = 290;
                $handle->image_x                 = 300;
                //$handle->image_reflection_height = '25%';
                //$handle->image_contrast          = 50;
                /** processing image **/
                $handle->process($dir_dest);
                /** cleaning **/ 
                $handle-> clean();
            } 
            
            /**image output**/
            $product->thumbnail_img = 'uploads/products/thumbnail/'.$handle->file_dst_name;
   
        }

        $product->featured_img = $request->previous_featured_img;
        //update feature_img 
        if($request->hasFile('featured_img')){
            //$product->featured_img = $request->featured_img->store('uploads/products/featured');
            //ImageOptimizer::optimize(base_path('public/').$product->featured_img);
            $image = $request->file('featured_img');
            //get image filename 
            $img_filename = $image->getClientOriginalName();
            //call function to changes white space to underscore_
            $imgfilename = getShopImageFilename($img_filename);
            $imagename = pathinfo($imgfilename, PATHINFO_FILENAME);
            //$filename = $imagename. time().'.'.$image->getClientOriginalExtension();
            $filename = uniqid().'.'.$image->getClientOriginalExtension();
            $image_resize = Image::make($image->getRealPath());  
            $image_resize->resize(290, 300,function($constraint) {
                $constraint->aspectRatio();
            });

            $image_resize->save(public_path('uploads/products/featured/' .$filename));
            //$product->featured_img = 'uploads/products/featured/' .$filename; 

            $dir_dest = 'public/uploads/products/featured';
            $handle = new image_Upload(public_path('uploads/products/featured/' .$filename));
            // then we check if the file has been uploaded properly
            // in its *temporary* location in the server (often, it is /tmp)
            if ($handle->uploaded) {
                /** size an ration **/
                $handle->image_resize            = true;
                $handle->image_ratio_y           = true;
                $handle->image_x                 = 290;
                $handle->image_x                 = 300;
                //$handle->image_reflection_height = '25%';
                //$handle->image_contrast          = 50;
                /** processing image **/
                $handle->process($dir_dest);
                /** cleaning **/ 
                $handle-> clean();
            } 
          
            /**image output**/
            $product->featured_img = 'uploads/products/featured/'.$handle->file_dst_name;
            
        }

        
        $product->flash_deal_img = $request->previous_flash_deal_img;
        //update flash_deal_img  
        if($request->hasFile('flash_deal_img')){
            //$product->flash_deal_img = $request->flash_deal_img->store('uploads/products/flash_deal');
            //ImageOptimizer::optimize(base_path('public/').$product->flash_deal_img);

            $image = $request->file('flash_deal_img');
            //get image filename 
            $img_filename = $image->getClientOriginalName();
            //call function to changes white space to underscore_
            $imgfilename = getShopImageFilename($img_filename);
            $imagename = pathinfo($imgfilename, PATHINFO_FILENAME);
            //$filename = $imagename. time().'.'.$image->getClientOriginalExtension();
            $filename = uniqid().'.'.$image->getClientOriginalExtension();
            $image_resize = Image::make($image->getRealPath());  
            $image_resize->resize(290, 300,function($constraint) {
                $constraint->aspectRatio();
            });

            $image_resize->save(public_path('uploads/products/flash_deal/' .$filename));
            //$product->flash_deal_img = 'uploads/products/flash_deal/' .$filename; 
            
            $dir_dest = 'public/products/flash_deal';
            $handle = new image_Upload(public_path('uploads/products/flash_deal/' .$filename));
            // then we check if the file has been uploaded properly
            // in its *temporary* location in the server (often, it is /tmp)
            if ($handle->uploaded) {
                /** size an ration **/
                $handle->image_resize            = true;
                $handle->image_ratio_y           = true;
                $handle->image_x                 = 290;
                $handle->image_x                 = 300;
                //$handle->image_reflection_height = '25%';
                //$handle->image_contrast          = 50;
                /** processing image **/
                $handle->process($dir_dest);
                /** cleaning **/ 
                $handle-> clean();
            } 
          
            /**image output**/
            $product->flash_deal_img = 'uploads/products/flash_deal/'.$handle->file_dst_name;
            
        }

        $product->unit = $request->unit;
        $product->tags = implode('|',$request->tags);
        $product->description = $request->description;
        $product->video_provider = $request->video_provider;
        $product->video_link = $request->video_link;
        $product->unit_price = $request->unit_price;
        $product->purchase_price = $request->purchase_price;
        $product->tax = $request->tax;
        $product->tax_type = $request->tax_type;
        $product->discount = $request->discount;
        $product->shipping_type = $request->shipping_type;
        $product->weight = $request->weight;
        $product->parcel_size = $request->length.",".$request->width.",".$request->height;
        if($request->shipping_type == 'free'){
            $product->shipping_cost = 0;
        }
        elseif ($request->shipping_type == 'local_pickup') {
            $product->shipping_cost = $request->local_pickup_shipping_cost;
        }
        elseif ($request->shipping_type == 'flat_rate') {
            //$product->shipping_cost = $request->flat_shipping_cost;
            $product->shipping_cost = $request->shipping_cost;
        }
        $product->discount_type = $request->discount_type;
        $product->meta_title = $request->meta_title;
        $product->meta_description = $request->meta_description;

        $product->meta_img = $request->previous_meta_img;
        //update meta_img
        if($request->hasFile('meta_img')){
            //$product->meta_img = $request->meta_img->store('uploads/products/meta');
            //ImageOptimizer::optimize(base_path('public/').$product->meta_img);
            $image = $request->file('meta_img');
            //get image filename 
            $img_filename = $image->getClientOriginalName();
            //call function to changes white space to underscore_
            $imgfilename = getShopImageFilename($img_filename);
            $imagename = pathinfo($imgfilename, PATHINFO_FILENAME);
            //$filename = $imagename. time().'.'.$image->getClientOriginalExtension();
            $filename = uniqid().'.'.$image->getClientOriginalExtension();
            $image_resize = Image::make($image->getRealPath());  
            $image_resize->resize(290, 300,function($constraint) {
                $constraint->aspectRatio();
            });

            $image_resize->save(public_path('uploads/products/meta/' .$filename));
            //$product->meta_img = 'uploads/products/meta/' .$filename; 
            $dir_dest = 'public/uploads/products/meta';
            $handle = new image_Upload(public_path('uploads/products/meta/' .$filename));
            // then we check if the file has been uploaded properly
            // in its *temporary* location in the server (often, it is /tmp)
            if ($handle->uploaded) {
                /** size an ration **/
                $handle->image_resize            = true;
                $handle->image_ratio_y           = true;
                $handle->image_x                 = 290;
                $handle->image_x                 = 300;
                //$handle->image_reflection_height = '25%';
                //$handle->image_contrast          = 50;
                /** processing image **/
                $handle->process($dir_dest);
                /** cleaning **/ 
                $handle-> clean();
            } 
            
            /**image output**/
            $product->meta_img = 'uploads/products/meta/'.$handle->file_dst_name;

            
        }

        if($request->hasFile('pdf')){
            $product->pdf = $request->pdf->store('uploads/products/pdf');
        }

        $product->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)).'-'.substr($product->slug, -5);

        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
            $product->colors = json_encode($request->colors);
        }
        else {
            $colors = array();
            $product->colors = json_encode($colors);
        }

        $choice_options = array();

        if($request->has('choice')){
            foreach ($request->choice_no as $key => $no) {
                $str = 'choice_options_'.$no;
                $item['name'] = 'choice_'.$no;
                $item['title'] = $request->choice[$key];
                $item['options'] = explode(',', implode('|', $request[$str]));
                array_push($choice_options, $item);
            }
        }

        $product->choice_options = json_encode($choice_options);

        foreach (Language::all() as $key => $language) {
            $data = openJSONFile($language->code);
            unset($data[$product->name]);
            $data[$request->name] = "";
            saveJSONFile($language->code, $data);
        }

        $variations = array();

        //combinations start
        $options = array();
        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
            $colors_active = 1;
            array_push($options, $request->colors);
        }

        if($request->has('choice_no')){
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_'.$no;
                $my_str = implode('|',$request[$name]);
                array_push($options, explode(',', $my_str));
            }
        }

        $combinations = combinations($options);
        if(count($combinations[0]) > 0){
            foreach ($combinations as $key => $combination){
                $str = '';
                foreach ($combination as $key => $item){
                    if($key > 0 ){
                        $str .= '-'.str_replace(' ', '', $item);
                    }
                    else{
                        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
                            $color_name = \App\Color::where('code', $item)->first()->name;
                            $str .= $color_name;
                        }
                        else{
                            $str .= str_replace(' ', '', $item);
                        }
                    }
                }
                $item = array();
                $item['price'] = $request['price_'.str_replace('.', '_', $str)];
                $item['sku'] = $request['sku_'.str_replace('.', '_', $str)];
                $item['qty'] = $request['qty_'.str_replace('.', '_', $str)];
                $variations[$str] = $item;
            }
        }
        //combinations end

        $product->variations = json_encode($variations);

        if($product->save()){
            flash(__('Product has been updated successfully'))->success();
            if(Auth::user()->user_type == 'admin'){
                return redirect()->route('products.admin');
            }
            else{
                return redirect()->route('seller.products');
            }
        }
        else{
            flash(__('Something went wrong'))->error();
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        if(Product::destroy($id)){
            foreach (Language::all() as $key => $language) {
                $data = openJSONFile($language->code);
                unset($data[$product->name]);
                saveJSONFile($language->code, $data);
            }
            if($product->thumbnail_img != null){
                //unlink($product->thumbnail_img);
            }
            if($product->featured_img != null){
                //unlink($product->featured_img);
            }
            if($product->flash_deal_img != null){
                //unlink($product->flash_deal_img);
            }
            foreach (json_decode($product->photos) as $key => $photo) {
                //unlink($photo);
            }
            flash(__('Product has been deleted successfully'))->success();
            if(Auth::user()->user_type == 'admin'){
                return redirect()->route('products.admin');
            }
            else{
                return redirect()->route('seller.products');
            }
        }
        else{
            flash(__('Something went wrong'))->error();
            return back();
        }
    }

    /**
     * Duplicates the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function duplicate($id)
    {
        $product = Product::find($id);
        $product_new = $product->replicate();
        $product_new->slug = substr($product_new->slug, 0, -5).str_random(5);

        if($product_new->save()){
            flash(__('Product has been duplicated successfully'))->success();
            if(Auth::user()->user_type == 'admin'){
                return redirect()->route('products.admin');
            }
            else{
                return redirect()->route('seller.products');
            }
        }
        else{
            flash(__('Something went wrong'))->error();
            return back();
        }
    }

    public function get_products_by_subsubcategory(Request $request)
    {
        $products = Product::where('subsubcategory_id', $request->subsubcategory_id)->get();
        return $products;
    }

    public function get_products_by_brand(Request $request)
    {
        $products = Product::where('brand_id', $request->brand_id)->get();
        return view('partials.product_select', compact('products'));
    }

    public function updateTodaysDeal(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->todays_deal = $request->status;
        if($product->save()){
            return 1;
        }
        return 0;
    }

    public function updatePublished(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->published = $request->status;
        if($product->save()){
            return 1;
        }
        return 0;
    }

    public function updateFeatured(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->featured = $request->status;
        if($product->save()){
            return 1;
        }
        return 0;
    }

    public function sku_combination(Request $request)
    {
        $options = array();
        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
            $colors_active = 1;
            array_push($options, $request->colors);
        }
        else {
            $colors_active = 0;
        }

        $unit_price = $request->unit_price;
        $product_name = $request->name;

        if($request->has('choice_no')){
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_'.$no;
                $my_str = implode('|', $request[$name]);
                array_push($options, explode(',', $my_str));
            }
        }

        $combinations = combinations($options);
        return view('partials.sku_combinations', compact('combinations', 'unit_price', 'colors_active', 'product_name'));
    }

    public function sku_combination_edit(Request $request)
    {
        $product = Product::findOrFail($request->id);

        $options = array();
        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
            $colors_active = 1;
            array_push($options, $request->colors);
        }
        else {
            $colors_active = 0;
        }

        $product_name = $request->name;
        $unit_price = $request->unit_price;

        if($request->has('choice_no')){
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_'.$no;
                $my_str = implode('|', $request[$name]);
                array_push($options, explode(',', $my_str));
            }
        }

        $combinations = combinations($options);
        return view('partials.sku_combinations_edit', compact('combinations', 'unit_price', 'colors_active', 'product_name', 'product'));
    }

}
