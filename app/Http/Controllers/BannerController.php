<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Banner;
use Intervention\Image\ImageManagerStatic as Image;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $banners = Banner::all();
        return view('banners.index', compact('banners'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($position)
    {
        return view('banners.create', compact('position'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->hasFile('photo')){
            $banner = new Banner;
            //$banner->photo = $request->photo->store('uploads/banners');
            
            $image = $request->file('photo');
            //get image filename 
            $img_filename = $image->getClientOriginalName();
            //call function to changes white space to underscore_
            $imgfilename = getShopImageFilename($img_filename);
            $imagename = pathinfo($imgfilename, PATHINFO_FILENAME);
            $filename = $imagename. time().'.'.$image->getClientOriginalExtension();
            $image_resize = Image::make($image->getRealPath());  
            $image_resize->resize(850, 420,function($constraint) {
                $constraint->aspectRatio();
            });

            $image_resize->save(public_path('uploads/banners/' .$filename));
            $dir_dest = 'public/uploads/banners';
            $handle = new image_Upload(public_path('uploads/banners/' .$filename));
            // then we check if the file has been uploaded properly
            // in its *temporary* location in the server (often, it is /tmp)
            if ($handle->uploaded) {
                /** size an ration **/
                $handle->image_resize            = true;
                $handle->image_ratio_y           = true;
                $handle->image_x                 = 850;
                $handle->image_y                 = 420;
                //$handle->image_reflection_height = '25%';
                //$handle->image_contrast          = 50;
                /** processing image **/
                $handle->process($dir_dest);
                /** cleaning **/ 
                $handle-> clean();
            } 
            $banner->photo = 'uploads/banners/' .$handle->file_dst_name; 
            
            $banner->url = $request->url;
            $banner->position = $request->position;
            $banner->save();
            flash(__('Banner has been inserted successfully'))->success();
        }
        return redirect()->route('home_settings.index');
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
        $banner = Banner::findOrFail($id);
        return view('banners.edit', compact('banner'));
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
        $banner = Banner::find($id);
        $banner->photo = $request->previous_photo;
        if($request->hasFile('photo')){
            $banner->photo = $request->photo->store('uploads/banners');
            
            $image = $request->file('photo');
            //get image filename 
            $img_filename = $image->getClientOriginalName();
            //call function to changes white space to underscore_
            $imgfilename = getShopImageFilename($img_filename);
            $imagename = pathinfo($imgfilename, PATHINFO_FILENAME);
            $filename = $imagename. time().'.'.$image->getClientOriginalExtension();
            $image_resize = Image::make($image->getRealPath());  
            $image_resize->resize(850, 420,function($constraint) {
                $constraint->aspectRatio();
            });

            $image_resize->save(public_path('uploads/banners/' .$filename));
            $dir_dest = 'public/uploads/banners';
            $handle = new image_Upload(public_path('uploads/banners/' .$filename));
            // then we check if the file has been uploaded properly
            // in its *temporary* location in the server (often, it is /tmp)
            if ($handle->uploaded) {
                /** size an ration **/
                $handle->image_resize            = true;
                $handle->image_ratio_y           = true;
                $handle->image_x                 = 850;
                $handle->image_y                 = 420;
                //$handle->image_reflection_height = '25%';
                //$handle->image_contrast          = 50;
                /** processing image **/
                $handle->process($dir_dest);
                /** cleaning **/ 
                $handle-> clean();
            } 
            $banner->photo = 'uploads/banners/' .$handle->file_dst_name; 
            
        }
        $banner->url = $request->url;
        $banner->save();
        flash(__('Banner has been updated successfully'))->success();
        return redirect()->route('home_settings.index');
    }


    public function update_status(Request $request)
    {
        $banner = Banner::find($request->id);
        $banner->published = $request->status;
        if($request->status == 1){
            if(count(Banner::where('published', 1)->where('position', $banner->position)->get()) < 4)
            {
                if($banner->save()){
                    return '1';
                }
                else {
                    return '0';
                }
            }
        }
        else{
            if($banner->save()){
                return '1';
            }
            else {
                return '0';
            }
        }

        return '0';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);
        if(Banner::destroy($id)){
            //unlink($banner->photo);
            flash(__('Banner has been deleted successfully'))->success();
        }
        else{
            flash(__('Something went wrong'))->error();
        }
        return redirect()->route('home_settings.index');
    }
}
