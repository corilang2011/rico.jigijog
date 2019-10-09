<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\user;
use Hash;
use Intervention\Image\ImageManagerStatic as Image;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('partials.admin_profile');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        if($request->new_password != null && ($request->new_password == $request->confirm_password)){
            $user->password = Hash::make($request->new_password);
        } 
        
        if($request->hasFile('photo')){
            //$user->avatar_original = $request->photo->store('uploads');
            $image = $request->file('photo');
            //get image filename 
            $img_filename = $image->getClientOriginalName();
            //call function to changes white space to underscore_
            $imgfilename = getShopImageFilename($img_filename);
            $imagename = pathinfo($imgfilename, PATHINFO_FILENAME);
            //$filename = $imagename. time().'.'.$image->getClientOriginalExtension();
            $filename = uniqid().'.'.$image->getClientOriginalExtension();
            $image_resize = Image::make($image->getRealPath());  
            $image_resize->resize(500, 500,function($constraint) {
                        $constraint->aspectRatio();
                    });
            $image_resize->save(public_path('uploads/' .$filename));

            $dir_dest = 'public/uploads';
            $handle = new image_Upload(public_path('uploads/' .$filename));
            // then we check if the file has been uploaded properly
            // in its *temporary* location in the server (often, it is /tmp)
            if ($handle->uploaded) {
                /** size an ration **/
                $handle->image_resize            = true;
                $handle->image_ratio_y           = true;
                $handle->image_x                 = 500;
                //$handle->image_y                 = 500;
                //$handle->image_reflection_height = '25%';
                //$handle->image_contrast          = 50;
                /** processing image **/
                $handle->process($dir_dest);
                /** cleaning **/ 
                $handle-> clean();
            } 
            //$image_name = $handle->file_dst_name;
            $user->avatar_original = 'uploads/' .$handle->file_dst_name; 

        }
        
        if($user->save()){
            flash(__('Your Profile has been updated successfully!'))->success();
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
}
