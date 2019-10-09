<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Customer;
use App\BusinessSetting;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Cookie;
use App\LVR\Digits;
use App\Http\Controllers\image_Upload;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|unique:users|min:11',
            'password' => 'required|string|min:6|confirmed',
			//'g-recaptcha-response' =>  'required|recaptcha',
            
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        
        $cookie = Cookie::get('referral');
        $referred_by = $cookie ? \Hashids::decode($cookie)[0] : null;
        $reward = '50';
        
        //$avatar_default = 'uploads/userdefaultlogo.png';

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
			'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'referred_by' => $referred_by,
            'avatar_original' => 'uploads/userdefaultlogo.png'
        ]);

        

        if(BusinessSetting::where('type', 'email_verification')->first()->value != 1){
            $user->email_verified_at = date('Y-m-d H:m:s');
            $user->save();
            flash(__('Registration successfull.'))->success();
        }
        else {
            flash(__('Registration successfull. Please verify your email.'))->success();
        }

        $customer = new Customer;
        $customer->user_id = $user->id;
        $customer->save();
        
        if($user->avatar_original == null){
            //$user->avatar_original = 'uploads/userdefaultlogo.png';
            /**start webp**/
            $path = public_path('uploads/userdefaultlogo.png');
            /**base_filename**/
            $filename = basename($path); 
            $user_pic = 'public/uploads/' .$filename; 

            $dir_dest = 'public/uploads';
            $handle = new image_Upload(public_path('uploads/userdefaultlogo.png'));
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

            $user->save();
        }

        return $user;
    }
}
