<!DOCTYPE html>
@if(\App\Language::where('code', Session::get('locale', Config::get('app.locale')))->first()->rtl == 1)
    <html dir="rtl">
@else
    <html>
@endif
<head>

@php
    $seosetting = \App\SeoSetting::first();
@endphp

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="index, follow">
<meta name="description" content="@yield('meta_description', $seosetting->description)" />
<meta name="keywords" content="@yield('meta_keywords', $seosetting->keyword)">
<meta name="author" content="{{ $seosetting->author }}">
<meta name="sitemap_link" content="{{ $seosetting->sitemap_link }}">
<meta name="csrf-token" content="{{ csrf_token() }}">
@yield('meta')

<link rel="canonical" href="https://jigijog.com">
<!-- Favicon -->
<link name="favicon" type="image/x-icon" href="{{ asset(\App\GeneralSetting::first()->favicon) }}" rel="shortcut icon" />

<title>@yield('meta_title', config('app.name', 'Laravel'))</title>

<!-- Fonts -->
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet">

<!-- Bootstrap -->
<link rel="stylesheet" href="{{ asset('frontend/css/bootstrap.min.css') }}" type="text/css">

<!-- Icons -->
<link rel="stylesheet" href="{{ asset('frontend/css/font-awesome.min.css') }}" type="text/css">
<link rel="stylesheet" href="{{ asset('frontend/css/line-awesome.min.css') }}" type="text/css">

<link type="text/css" href="{{ asset('frontend/css/bootstrap-tagsinput.css') }}" rel="stylesheet">
<link type="text/css" href="{{ asset('frontend/css/jodit.min.css') }}" rel="stylesheet">
<link type="text/css" href="{{ asset('frontend/css/sweetalert2.min.css') }}" rel="stylesheet">
<link type="text/css" href="{{ asset('frontend/css/slick.css') }}" rel="stylesheet">
<link type="text/css" href="{{ asset('frontend/css/xzoom.css') }}" rel="stylesheet">
<link type="text/css" href="{{ asset('frontend/css/jquery.share.css') }}" rel="stylesheet">


<!-- Global style (main) -->
<link type="text/css" href="{{ asset('frontend/css/active-shop.css') }}" rel="stylesheet" media="screen">

<!--Spectrum Stylesheet [ REQUIRED ]-->
<link href="{{ asset('css/spectrum.css')}}" rel="stylesheet">

<!-- Custom style -->
<link type="text/css" href="{{ asset('frontend/css/custom-style.css') }}" rel="stylesheet">

@if(\App\Language::where('code', Session::get('locale', Config::get('app.locale')))->first()->rtl == 1)
     <!-- RTL -->
    <link type="text/css" href="{{ asset('frontend/css/active.rtl.css') }}" rel="stylesheet">
@endif

<!-- Facebook Chat style -->
<link href="{{ asset('frontend/css/fb-style.css')}}" rel="stylesheet">

<!-- color theme -->
<link href="{{ asset('frontend/css/colors/'.\App\GeneralSetting::first()->frontend_color.'.css')}}" rel="stylesheet">

<!-- jQuery -->
<script src="{{ asset('frontend/js/vendor/jquery.min.js') }}"></script>

@if (\App\BusinessSetting::where('type', 'google_analytics')->first()->value == 1)
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-143090608-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
    
      gtag('config', 'UA-143090608-1');
    </script>
@endif

<!--fbProfile css -->
<!--<link href="{{ asset('css/fb/OheBcUbaHty.css')}}" rel="stylesheet">-->
<!--<link href="{{ asset('css/fb/Wpnw3FlhGcA.css')}}" rel="stylesheet">-->
<!--<link href="{{ asset('css/fb/fblikeprofile_update.css')}}" rel="stylesheet">-->
<!--End fbProfile css -->

</head>

<style>

#myBtn {
  position: fixed;
  right: 30px;
  z-index: 99;
  font-size: 18px;
  border: none;
  outline: none;
  color: white;
  cursor: pointer;
  padding: 15px;
  border-radius: 4px;
  margin-top: 50px;

  -webkit-transition: background 0.5s ease-in-out;
    -ms-transition:     background 0.5s ease-in-out;
    transition:         background 0.5s ease-in-out;
}

#myBtn:hover {
  background-color: #555;
}

.background{
    background: #ff4e00;
}
</style>


<body>


<!-- MAIN WRAPPER -->
<div class="body-wrap shop-default shop-cards shop-tech gry-bg">

    <!-- Header -->
    @include('frontend.inc.nav')
    
    @php
        $flag = false;
        if(!Auth::guest()){
            $tickets = DB::table('tickets')->where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();
            foreach ($tickets as $key => $ticket){
                if($ticket->replied == 1){
                    $flag = true;
                    break;
                }
            }
        }
    @endphp

    @if($flag)
    <a href="support-ticket">
        <div id="myBtn" >
            <i class="fa fa-comments"></i>&nbsp;
            <span>You have a message!</span>
        </div>
    </a>
    @endif
    
    @yield('content')

    @include('frontend.inc.footer')

    @include('frontend.partials.modal')

    <div class="modal fade" id="addToCart" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-zoom product-modal" id="modal-size" role="document">
            <div class="modal-content position-relative">
                <div class="c-preloader">
                    <i class="fa fa-spin fa-spinner"></i>
                </div>
                <button type="button" class="close absolute-close-btn" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div id="addToCart-modal-body">

                </div>
            </div>
        </div>
    </div>

    @if (\App\BusinessSetting::where('type', 'facebook_chat')->first()->value == 1)
        <div id="fb-root"></div>
        <!-- Your customer chat code -->
        <div class="fb-customerchat"
          attribution=setup_tool
          page_id="{{ env('FACEBOOK_PAGE_ID') }}">
        </div>
    @endif

</div><!-- END: body-wrap -->

<!-- SCRIPTS -->
<a href="#" class="back-to-top btn-back-to-top"></a>

<!-- Core -->

<script src="{{ asset('frontend/js/vendor/popper.min.js') }}"></script>
<script src="{{ asset('frontend/js/vendor/bootstrap.min.js') }}"></script>

<!-- Plugins: Sorted A-Z -->
<script src="{{ asset('frontend/js/spartan-multi-image-picker-min.js') }}"></script>
<script src="{{ asset('frontend/js/jquery.countdown.min.js') }}"></script>
<script src="{{ asset('frontend/js/select2.min.js') }}"></script>
<script src="{{ asset('frontend/js/nouislider.min.js') }}"></script>


<script src="{{ asset('frontend/js/sweetalert2.min.js') }}"></script>
<script src="{{ asset('frontend/js/slick.min.js') }}"></script>

<script src="{{ asset('frontend/js/jquery.share.js') }}"></script>

<script type="text/javascript">
    function showFrontendAlert(type, message){
        swal({
            position: 'top-end',
            type: type,
            title: message,
            showConfirmButton: false,
            timer: 5500
        });
    }
</script>

@foreach (session('flash_notification', collect())->toArray() as $message)
    <script type="text/javascript">
        showFrontendAlert('{{ $message['level'] }}', '{{ $message['message'] }}');
    </script>
@endforeach

<script>

    $(document).ready(function() {
        var $div2blink = $("#myBtn");
        var backgroundInterval = setInterval(function(){
            $div2blink.toggleClass("background");
        },1500)
        
        if ($('#lang-change').length > 0) {
            $('#lang-change .dropdown-item a').each(function() {
                $(this).on('click', function(e){
                    e.preventDefault();
                    var $this = $(this);
                    var locale = $this.data('flag');
                    $.post('{{ route('language.change') }}',{_token:'{{ csrf_token() }}', locale:locale}, function(data){
                        location.reload();
                    });

                });
            });
        }

        if ($('#currency-change').length > 0) {
            $('#currency-change .dropdown-item a').each(function() {
                $(this).on('click', function(e){
                    e.preventDefault();
                    var $this = $(this);
                    var currency_code = $this.data('currency');
                    $.post('{{ route('currency.change') }}',{_token:'{{ csrf_token() }}', currency_code:currency_code}, function(data){
                        location.reload();
                    });

                });
            });
        }

        $(document).on('mouseenter', '#submitButton', function(){
            var province = $("#province option:selected").text();
            var city = $("#city option:selected").text();
            var barangay = $("#barangay option:selected").text();
            var landmark = $("#landmark").val();

            var address = ""+landmark+", "+barangay+", "+city+", "+province;
            document.getElementById('address').value = address;
            document.getElementById('address').innerHTML = address;
        });

        $(document).on('mouseenter', 'body', function(){
            var province = $("#province option:selected").text();
            var city = $("#city option:selected").text();
            var barangay = $("#barangay option:selected").text();
            var landmark = $("#landmark").val();

            var address = ""+landmark+", "+barangay+", "+city+", "+province;
            document.getElementById('address').value = address;
            document.getElementById('address').innerHTML = address;
        });

        $(document).on('keyup', '#landmark', function(){
            var province = $("#province option:selected").text();
            var city = $("#city option:selected").text();
            var barangay = $("#barangay option:selected").text();
            var landmark = $("#landmark").val();

            var address = ""+landmark+", "+barangay+", "+city+", "+province;
            document.getElementById('address').value = address;
            document.getElementById('address').innerHTML = address;
        });

        $(document).on('keyup', '.weight', function(){
            var weight = $(".weight").val();

            if(weight <= 1){
                document.getElementById('sc').value = 75.00;
                document.getElementById('displayy').innerHTML = 75.00;
            }
            else if(weight > 1 && weight <= 3){
                document.getElementById('sc').value = 120.00;
                document.getElementById('displayy').innerHTML = 120.00;
            }
            else if(weight > 3 && weight <= 4){
                document.getElementById('sc').value = 180.00;
                document.getElementById('displayy').innerHTML = 180.00;
            }
            else if(weight > 4){
                document.getElementById('sc').value = 180.00;
                document.getElementById('displayy').innerHTML = 180.00;
            }
        });
        
        $(document).on('change', '#province', function(){
            var provCodeID = $("#province").val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url:"/ajax/province",
                method:"POST",
                data:{provCodeID:provCodeID},
                dataType:"json",
                success:function(data){
                    var city = $("#city");
                    var brgy = $("#barangay");
                    brgy.empty();
                    city.empty();
                    var temp = '<option selected="true" disabled="disabled" value="">Select Barangay</option>'
                    brgy.append(temp);
                    city.append(data.output);
                }
            })
        });

        $(document).on('change', '#city', function(){
            var cityID = $("#city").val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url:"/ajax/barangay",
                method:"POST",
                data:{cityID:cityID},
                dataType:"json",
                success:function(data){
                    var brgy = $("#barangay");
                    brgy.empty();
                    brgy.append(data.output);
                }
            })
        });
    });

    $('#search').on('keyup', function(){
        search();
    });

    $('#search').on('focus', function(){
        search();
    });
    
    $('#prod_search').on('keyup', function(){
        prod_search();
    });

    $('#prod_search').on('focus', function(){
        prod_search();
    });

    function search(){
        var search = $('#search').val();
        if(search.length > 0){
            $('body').addClass("typed-search-box-shown");

            $('.typed-search-box').removeClass('d-none');
            $('.search-preloader').removeClass('d-none');
            $.post('{{ route('search.ajax') }}', { _token: '{{ @csrf_token() }}', search:search}, function(data){
                if(data == '0'){
                    // $('.typed-search-box').addClass('d-none');
                    $('#search-content').html(null);
                    $('.typed-search-box .search-nothing').removeClass('d-none').html('Sorry, nothing found for <strong>"'+search+'"</strong>');
                    $('.search-preloader').addClass('d-none');

                }
                else{
                    $('.typed-search-box .search-nothing').addClass('d-none').html(null);
                    $('#search-content').html(data);
                    $('.search-preloader').addClass('d-none');
                }
            });
        }
        else {
            $('.typed-search-box').addClass('d-none');
            $('body').removeClass("typed-search-box-shown");
        }
    }
    
    function prod_search(){
        var search = $('#prod_search').val();
        if(search.length > 0){
            $('#prod-search-content').removeClass('d-none');
            $('.gone').addClass('d-none');
            $('.prod-typed-search-box').removeClass('d-none');
            $('.prod-search-preloader').removeClass('d-none');
            $.post('{{ route('prod_search.ajax') }}', { _token: '{{ @csrf_token() }}', search:search}, function(data){
                if(data == '0'){
                    // $('.typed-search-box').addClass('d-none');
                    $('#prod-search-content').html(null);
                    $('.not').removeClass('d-none').html('<i class="la la-meh-o d-block heading-1 alpha-5"></i> <br>  Sorry, nothing found for <strong>"'+search+'"</strong>');
                }
                else{
                    $('.not').addClass('d-none');
                    $('.prod-typed-search-box .prod-search-nothing').addClass('d-none').html(null);
                    $('#prod-search-content').html(data);
                    $('.prod-search-preloader').addClass('d-none');
                }
            });
        }
        else {
            $('.not').addClass('d-none');
            $('#prod-search-content').addClass('d-none');
            $('.gone').removeClass('d-none');
            $('.prod-typed-search-box').addClass('d-none');
        }
    }

    function updateNavCart(){
        $.post('{{ route('cart.nav_cart') }}', {_token:'{{ csrf_token() }}'}, function(data){
            $('#cart_items').html(data);
        });
    }

    function removeFromCart(key){
        $.post('{{ route('cart.removeFromCart') }}', {_token:'{{ csrf_token() }}', key:key}, function(data){
            updateNavCart();
            $('#cart-summary').html(data);
            showFrontendAlert('success', 'Item has been removed from cart');
            $('#cart_items_sidenav').html(parseInt($('#cart_items_sidenav').html())-1);
        });
    }

    function addToCompare(id){
        $.post('{{ route('compare.addToCompare') }}', {_token:'{{ csrf_token() }}', id:id}, function(data){
            $('#compare').html(data);
            showFrontendAlert('success', 'Item has been added to compare list');
            $('#compare_items_sidenav').html(parseInt($('#compare_items_sidenav').html())+1);
        });
    }

    function addToWishList(id){
        @if (Auth::check())
            $.post('{{ route('wishlists.store') }}', {_token:'{{ csrf_token() }}', id:id}, function(data){
                if(data != 0){
                    $('#wishlist').html(data);
                    showFrontendAlert('success', 'Item has been added to wishlist');
                }
                else{
                    showFrontendAlert('warning', 'Please login first');
                }
            });
        @else
            showFrontendAlert('warning', 'Please login first');
        @endif
    }

    function showAddToCartModal(id){
        if(!$('#modal-size').hasClass('modal-lg')){
            $('#modal-size').addClass('modal-lg');
        }
        $('#addToCart-modal-body').html(null);
        $('#addToCart').modal();
        $('.c-preloader').show();
        $.post('{{ route('cart.showCartModal') }}', {_token:'{{ csrf_token() }}', id:id}, function(data){
            $('.c-preloader').hide();
            $('#addToCart-modal-body').html(data);
            $('.xzoom, .xzoom-gallery').xzoom({
                Xoffset: 20,
                bg: true,
                tint: '#000',
                defaultScale: -1
            });
        });
    }

    $('#option-choice-form input').on('change', function(){
        getVariantPrice();
    });

    function getVariantPrice(){
        if($('#option-choice-form input[name=quantity]').val() > 0 && checkAddToCartValidity()){
            $.ajax({
               type:"POST",
               url: '{{ route('products.variant_price') }}',
               data: $('#option-choice-form').serializeArray(),
               success: function(data){
                   $('#option-choice-form #chosen_price_div').removeClass('d-none');
                   $('#option-choice-form #chosen_price_div #chosen_price').html(data);
               }
           });
        }
    }

    function checkAddToCartValidity(){
        var names = {};
        $('#option-choice-form input:radio').each(function() { // find unique names
              names[$(this).attr('name')] = true;
        });
        var count = 0;
        $.each(names, function() { // then count them
              count++;
        });
        if($('input:radio:checked').length == count){
            return true;
        }
        return false;
    }

    function addToCart(){
        if(checkAddToCartValidity()) {
            $('#addToCart').modal();
            $('.c-preloader').show();
            $.ajax({
               type:"POST",
               url: '{{ route('cart.addToCart') }}',
               data: $('#option-choice-form').serializeArray(),
               success: function(data){
                   $('#addToCart-modal-body').html(null);
                   $('.c-preloader').hide();
                   $('#modal-size').removeClass('modal-lg');
                   $('#addToCart-modal-body').html(data);
                   updateNavCart();
                   $('#cart_items_sidenav').html(parseInt($('#cart_items_sidenav').html())+1);
               }
           });
        }
        else{
            showFrontendAlert('warning', 'Please choose all the options');
        }
    }

    function buyNow(){
        if(checkAddToCartValidity()) {
            $('#addToCart').modal();
            $('.c-preloader').show();
            $.ajax({
               type:"POST",
               url: '{{ route('cart.addToCart') }}',
               data: $('#option-choice-form').serializeArray(),
               success: function(data){
                   //$('#addToCart-modal-body').html(null);
                   //$('.c-preloader').hide();
                   //$('#modal-size').removeClass('modal-lg');
                   //$('#addToCart-modal-body').html(data);
                   updateNavCart();
                   $('#cart_items_sidenav').html(parseInt($('#cart_items_sidenav').html())+1);
                   window.location.replace("{{ route('checkout.shipping_info') }}");
               }
           });
        }
        else{
            showFrontendAlert('warning', 'Please choose all the options');
        }
    }

    function show_purchase_history_details(order_id)
    {
        $('#order-details-modal-body').html(null);

        if(!$('#modal-size').hasClass('modal-lg')){
            $('#modal-size').addClass('modal-lg');
        }

        $.post('{{ route('purchase_history.details') }}', { _token : '{{ @csrf_token() }}', order_id : order_id}, function(data){
            $('#order-details-modal-body').html(data);
            $('#order_details').modal();
            $('.c-preloader').hide();
        });
    }

    function show_order_details(order_id)
    {
        $('#order-details-modal-body').html(null);

        if(!$('#modal-size').hasClass('modal-lg')){
            $('#modal-size').addClass('modal-lg');
        }

        $.post('{{ route('orders.details') }}', { _token : '{{ @csrf_token() }}', order_id : order_id}, function(data){
            $('#order-details-modal-body').html(data);
            $('#order_details').modal();
            $('.c-preloader').hide();
        });
    }
    function show_wallet_details(order_id)
    {
        $('#order-details-modal-body').html(null);

        if(!$('#modal-size').hasClass('modal-lg')){
            $('#modal-size').addClass('modal-lg');
        }

        $.post('{{ route('wallet.details') }}', { _token : '{{ @csrf_token() }}', order_id : order_id}, function(data){
            $('#order-details-modal-body').html(data);
            $('#order_details').modal();
            $('.c-preloader').hide();
        });
    }

    function cartQuantityInitialize(){
        $('.btn-number').click(function(e) {
            e.preventDefault();

            fieldName = $(this).attr('data-field');
            type = $(this).attr('data-type');
            var input = $("input[name='" + fieldName + "']");
            var currentVal = parseInt(input.val());

            if (!isNaN(currentVal)) {
                if (type == 'minus') {

                    if (currentVal > input.attr('min')) {
                        input.val(currentVal - 1).change();
                    }
                    if (parseInt(input.val()) == input.attr('min')) {
                        $(this).attr('disabled', true);
                    }

                } else if (type == 'plus') {

                    if (currentVal < input.attr('max')) {
                        input.val(currentVal + 1).change();
                    }
                    if (parseInt(input.val()) == input.attr('max')) {
                        $(this).attr('disabled', true);
                    }

                }
            } else {
                input.val(0);
            }
        });

        $('.input-number').focusin(function() {
            $(this).data('oldValue', $(this).val());
        });

        $('.input-number').change(function() {

            minValue = parseInt($(this).attr('min'));
            maxValue = parseInt($(this).attr('max'));
            valueCurrent = parseInt($(this).val());

            name = $(this).attr('name');
            if (valueCurrent >= minValue) {
                $(".btn-number[data-type='minus'][data-field='" + name + "']").removeAttr('disabled')
            } else {
                alert('Sorry, the minimum value was reached');
                $(this).val($(this).data('oldValue'));
            }
            if (valueCurrent <= maxValue) {
                $(".btn-number[data-type='plus'][data-field='" + name + "']").removeAttr('disabled')
            } else {
                alert('Sorry, the maximum value was reached');
                $(this).val($(this).data('oldValue'));
            }


        });
        $(".input-number").keydown(function(e) {
            // Allow: backspace, delete, tab, escape, enter and .
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
                // Allow: Ctrl+A
                (e.keyCode == 65 && e.ctrlKey === true) ||
                // Allow: home, end, left, right
                (e.keyCode >= 35 && e.keyCode <= 39)) {
                // let it happen, don't do anything
                return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });
    }

     function imageInputInitialize(){
         $('.custom-input-file').each(function() {
             var $input = $(this),
                 $label = $input.next('label'),
                 labelVal = $label.html();

             $input.on('change', function(e) {
                 var fileName = '';

                 if (this.files && this.files.length > 1)
                     fileName = (this.getAttribute('data-multiple-caption') || '').replace('{count}', this.files.length);
                 else if (e.target.value)
                     fileName = e.target.value.split('\\').pop();

                 if (fileName)
                     $label.find('span').html(fileName);
                 else
                     $label.html(labelVal);
             });

             // Firefox bug fix
             $input
                 .on('focus', function() {
                     $input.addClass('has-focus');
                 })
                 .on('blur', function() {
                     $input.removeClass('has-focus');
                 });
         });
     }

</script>

<!-- Print Invoice -->
<script type="text/javascript">
function closePrint () {
  document.body.removeChild(this.__container__);
}

function setPrint () {
  this.contentWindow.__container__ = this;
  this.contentWindow.onbeforeunload = closePrint;
  this.contentWindow.onafterprint = closePrint;
  this.contentWindow.focus(); // Required for IE
  this.contentWindow.print();
}

function printPage (sURL) {
  var oHiddFrame = document.createElement("iframe");
  oHiddFrame.onload = setPrint;
  oHiddFrame.style.position = "fixed";
  oHiddFrame.style.right = "0";
  oHiddFrame.style.bottom = "0";
  oHiddFrame.style.width = "0";
  oHiddFrame.style.height = "0";
  oHiddFrame.style.border = "0";
  oHiddFrame.src = sURL;
  document.body.appendChild(oHiddFrame);
}
</script>
<!-- End Print Invoice -->

<script src="{{ asset('frontend/js/bootstrap-tagsinput.min.js') }}"></script>
<script src="{{ asset('frontend/js/jodit.min.js') }}"></script>
<script src="{{ asset('frontend/js/xzoom.min.js') }}"></script>

<!-- App JS -->
<script src="{{ asset('frontend/js/active-shop.js') }}"></script>
<script src="{{ asset('frontend/js/main.js') }}"></script>
<script src="{{ asset('frontend/js/fb-script.js') }}"></script>

@yield('script')
<script src="https://my.hellobar.com/e329e781b1cef0c8b943aa5ea7daac74647853ff.js" type="text/javascript" charset="utf-8" async="async"></script>
</body>
</html>
