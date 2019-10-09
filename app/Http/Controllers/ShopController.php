<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Shop;
use App\User;
use App\Seller;
use App\BusinessSetting;
use Auth;
use Hash;use App\LVR\Digits;
use Intervention\Image\ImageManagerStatic as Image;
//use App\includes\getImgFilename; 

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shop = Auth::user()->shop;
        return view('frontend.seller.shop', compact('shop'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('frontend.seller_form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $user = null;
        if(!Auth::check()){
            if(User::where('email', $request->email)->first() != null){
                flash(__('Email already exists!'))->error();
                return back();
            }
            
            if(User::where('phone', $request->phone)->first() != null){
                flash(__('Phone already exists!'))->error();
                return back();
            }
            
            if($request->password == $request->password_confirmation){                              
                $this->validate($request,[                
                    'phone' => ['required', new Digits],                
                ]);
                $user = new User;
                $user->name = $request->name;
                $user->email = $request->email;
                $user->phone = $request->Input(['phone']);
                $user->user_type = "seller";
                $user->password = Hash::make($request->password);
                $user->save();
            }
            else{
                flash(__('Sorry! Password did not match.'))->error();
                return back();
            }
        }
        else{
            $user = Auth::user();
            if($user->customer != null){
                $user->customer->delete();
            }
            $user->user_type = "seller";
            $user->save();
        }

        if(BusinessSetting::where('type', 'email_verification')->first()->value != 1){
            $user->email_verified_at = date('Y-m-d H:m:s');
            $user->save();
        }

        $seller = new Seller;
        $seller->user_id = $user->id;
        $seller->save();

        if(Shop::where('user_id', $user->id)->first() == null){
            $shop = new Shop;
            $shop->user_id = $user->id;
            $shop->name = $request->name;
            $shop->address = $request->address;
            $shop->slug = preg_replace('/\s+/', '-', $request->name).'-'.$shop->id;
            
            if($request->hasFile('logo')){
                $image = $request->file('logo');
                //get image filename 
                //get image filename 
                $img_filename = $image->getClientOriginalName();
                //call function to changes white space to underscore_
                $imgfilename = getShopImageFilename($img_filename);
                $imagename = pathinfo($imgfilename, PATHINFO_FILENAME);
                $filename = $imagename. time().'.'.$image->getClientOriginalExtension();
                $image_resize = Image::make($image->getRealPath());  
                $image_resize->resize(500, 500,function($constraint) {
                    $constraint->aspectRatio();
                });

                $image_resize->save(public_path('uploads/hop/logo/' .$filename));
                $dir_dest = 'public/uploads/hop/logo';
                $handle = new image_Upload(public_path('uploads/hop/logo/' .$filename));
                // then we check if the file has been uploaded properly
                // in its *temporary* location in the server (often, it is /tmp)
                if ($handle->uploaded) {
                    /** size an ration **/
                    $handle->image_resize            = true;
                    $handle->image_ratio_y           = true;
                    $handle->image_x                 = 500;
                    //$handle->image_reflection_height = '25%';
                    //$handle->image_contrast          = 50;
                    /** processing image **/
                    $handle->process($dir_dest);
                    /** cleaning **/ 
                    $handle-> clean();
                } 
                $shop->logo = 'uploads/hop/logo/' .$handle->file_dst_name; 
                //$shop->logo = 'uploads/hop/logo/' .$filename; 
                $shop->save();
                $shop_logo_temp = $dir_dest.'/' . $handle->file_dst_name;
                $avatar_temp = public_path('uploads/' . $handle->file_dst_name);
                //copy shop logo as default profile pic 
                if(copy($shop_logo_temp, $avatar_temp)){
                    //$user = Auth::user();
                    if($user->avatar_original == null){
                    //default userlogo
                    $user->avatar_original = 'uploads/' . $handle->file_dst_name;
                    $user->save();

                    }    
                }
            }
            //Enable this else funnction if not required on registration form view to set default logo 
            /*else{ 
                $user = Auth::user();
                //Create default shop profile logo
                $shop->logo = 'uploads/hop/logo/userdefaultlogo.png'; 
                $shop->save();
                //Create default user profile pic
                $user->avatar_original = 'uploads/hop/logo/userdefaultlogo.png'; 
                $user->save();  
                

            }*/

            if($shop->save()){
                auth()->login($user, false);
                flash(__('Your Shop has been created successfully!'))->success();
                return redirect()->route('shops.index');
            }
        }

        flash(__('Sorry! Something went wrong.'))->error();
        return back();
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
    public function edit($id)
    {
        //
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
        //include(app_path() . '\includes\getImgFilename.php');

        
        
        $shop = Shop::find($id);

        if($request->has('name') && $request->has('address')){
            $shop->name = $request->name;
            $shop->address = $request->address;
            $shop->slug = preg_replace('/\s+/', '-', $request->name).'-'.$shop->id;

            if($request->hasFile('logo')){
                $image = $request->file('logo');
                //get image filename 
                $img_filename = $image->getClientOriginalName();
                //call function to changes white space to underscore_
                $imgfilename = getShopImageFilename($img_filename);
                $imagename = pathinfo($imgfilename, PATHINFO_FILENAME);
                $filename = $imagename. time().'.'.$image->getClientOriginalExtension();
                $image_resize = Image::make($image->getRealPath());  
                $image_resize->resize(500, 500,function($constraint) {
                    $constraint->aspectRatio();
                });

                $image_resize->save(public_path('uploads/hop/logo/' .$filename));
                //$shop->logo = 'uploads/hop/logo/' .$filename; 
                //$shop->save();

                $dir_dest = 'public/uploads/hop/logo';
                $handle = new image_Upload(public_path('uploads/hop/logo/' .$filename));
                // then we check if the file has been uploaded properly
                // in its *temporary* location in the server (often, it is /tmp)
                if ($handle->uploaded) {
                    /** size an ration **/
                    $handle->image_resize            = true;
                    $handle->image_ratio_y           = true;
                    $handle->image_x                 = 500;
                    //$handle->image_reflection_height = '25%';
                    //$handle->image_contrast          = 50;
                    /** processing image **/
                    $handle->process($dir_dest);
                    /** cleaning **/ 
                    $handle-> clean();
                } 
                $shop->logo = 'uploads/hop/logo/' .$handle->file_dst_name; 

                //$shop->logo = 'uploads/hop/logo/' .$filename; 
                $shop->save();
                    
               }
        }

        elseif($request->has('facebook') || $request->has('google') || $request->has('twitter') || $request->has('youtube') || $request->has('instagram')){
            $shop->facebook = $request->facebook;
            $shop->google = $request->google;
            $shop->twitter = $request->twitter;
            $shop->youtube = $request->youtube;
            $shop->instagram = $request->instagram;
        }

        else{
            if($request->has('previous_sliders')){
                $sliders = $request->previous_sliders;
            }
            else{
                $sliders = array();
            }

            if($request->hasFile('sliders')){

                /*foreach ($request->sliders as $key => $slider) {
                    array_push($sliders, $slider->store('uploads/shop/sliders'));
                }*/

                foreach($request->sliders as $key => $slider){
                    
                    $img_filename = $slider->getClientOriginalName();
                    $img_filename = $slider->getClientOriginalName();
                    $imgfilename = getShopImageFilename($img_filename);
                    $imagename = pathinfo($imgfilename, PATHINFO_FILENAME);
                    $filename = $imagename. time().'.'.$slider->getClientOriginalExtension();
                    
                    $image_resize = Image::make($slider->getRealPath());  
                    $image_resize->resize(1100, 300,function($constraint) {
                        $constraint->aspectRatio();
                    });
                    $image_resize->save(public_path('uploads/shop/sliders/' .$filename));

                    //$slider->photo = $photo->store('uploads/sliders');
                    //$slider->photo = 'uploads/shop/sliders/' .$filename; 
                    $dir_dest = 'public/uploads/shop/sliders';
                    $handle = new image_Upload(public_path('uploads/shop/sliders/' .$filename));
                    // then we check if the file has been uploaded properly
                    // in its *temporary* location in the server (often, it is /tmp)
                    if ($handle->uploaded) {
                        /** size an ration **/
                        $handle->image_resize            = true;
                        $handle->image_ratio_y           = true;
                        $handle->image_x                 = 1100;
                        $handle->image_y                 = 300;
                        //$handle->image_reflection_height = '25%';
                        //$handle->image_contrast          = 50;
                        /** processing image **/
                        $handle->process($dir_dest);
                        /** cleaning **/ 
                        $handle-> clean();
                    } 
                    /** slider photo will be inserted into the database **/
                    $slider->photo = 'uploads/shop/sliders/' .$handle->file_dst_name; 
                    //$slider->photo = $photo->store('uploads/sliders');
                    //$slider->save();
                    array_push($sliders, $slider->photo);
                }

            }

            $shop->sliders = json_encode($sliders);
        }

        if($shop->save()){
            flash(__('Your Shop has been updated successfully!'))->success();
            return back();
        }

        flash(__('Sorry! Something went wrong.'))->error();
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function verify_form(Request $request)
    {
        if(Auth::user()->seller->verification_info == null){
            $shop = Auth::user()->shop;
            return view('frontend.seller.verify_form', compact('shop'));
        }
        else {
            flash(__('Sorry! You have sent verification request already.'))->error();
            return back();
        }
    }

    public function verify_form_store(Request $request)
    {
        $data = array();
        $i = 0;
        foreach (json_decode(BusinessSetting::where('type', 'verification_form')->first()->value) as $key => $element) {
            $item = array();
            if ($element->type == 'text') {
                $item['type'] = 'text';
                $item['label'] = $element->label;
                $item['value'] = $request['element_'.$i];
            }
            elseif ($element->type == 'select' || $element->type == 'radio') {
                $item['type'] = 'select';
                $item['label'] = $element->label;
                $item['value'] = $request['element_'.$i];
            }
            elseif ($element->type == 'multi_select') {
                $item['type'] = 'multi_select';
                $item['label'] = $element->label;
                $item['value'] = json_encode($request['element_'.$i]);
            }
            elseif ($element->type == 'file') {
                $item['type'] = 'file';
                $item['label'] = $element->label;
                $item['value'] = $request['element_'.$i]->store('uploads/verification_form');
            }
            array_push($data, $item);
            $i++;
        }
        $seller = Auth::user()->seller;
        $seller->verification_info = json_encode($data);
        if($seller->save()){
            flash(__('Your shop verification request has been submitted successfully!'))->success();
            return redirect()->route('dashboard');
        }

        flash(__('Sorry! Something went wrong.'))->error();
        return back();
    }
}
