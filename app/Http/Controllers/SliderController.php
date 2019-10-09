<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Slider;
use Intervention\Image\ImageManagerStatic as Image;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sliders = Slider::all();
        return view('sliders.index', compact('sliders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('sliders.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->hasFile('photos')){

            foreach($request->photos as $key => $photo){
                $slider = new Slider;
                //convert filename 
                $img_filename = $photo->getClientOriginalName();
                $imgfilename = getShopImageFilename($img_filename);
                $imagename = pathinfo($imgfilename, PATHINFO_FILENAME);
                //$filename = $imagename. time().'.'.$photo->getClientOriginalExtension();
                $filename = uniqid().'.'.$photo->getClientOriginalExtension();
                //change size 
                $image_resize = Image::make($photo->getRealPath());  
                $image_resize->resize(850, 315,function($constraint) {
                    $constraint->aspectRatio();
                });
                $image_resize->save(public_path('uploads/sliders/' .$filename));

                //$slider->photo = $photo->store('uploads/sliders');
                //$slider->photo = 'uploads/shop/sliders/' .$filename; 
                $dir_dest = 'public/uploads/sliders';
                $handle = new image_Upload(public_path('uploads/sliders/' .$filename));
                // then we check if the file has been uploaded properly
                // in its *temporary* location in the server (often, it is /tmp)
                if ($handle->uploaded) {
                    /** size an ration **/
                    $handle->image_resize            = true;
                    $handle->image_ratio_y           = true;
                    $handle->image_x                 = 850;
                    $handle->image_y                 = 315;
                    //$handle->image_reflection_height = '25%';
                    //$handle->image_contrast          = 50;
                    /** processing image **/
                    $handle->process($dir_dest);
                    /** cleaning **/ 
                    $handle-> clean();
                } 
                /** this slider data will be inserted into the database **/
                $slider->photo = 'uploads/sliders/' .$handle->file_dst_name;  
                $slider->save();
                
            }
             

            flash(__('Slider has been inserted successfully'))->success();
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
        $slider = Slider::find($id);
        $slider->published = $request->status;
        if($slider->save()){
            return '1';
        }
        else {
            return '0';
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
        $slider = Slider::findOrFail($id);
        if(Slider::destroy($id)){
            //unlink($slider->photo);
            flash(__('Slider has been deleted successfully'))->success();
        }
        else{
            flash(__('Something went wrong'))->error();
        }
        return redirect()->route('home_settings.index');
    }
}
