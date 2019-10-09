<!-- FOOTER -->
<!--===================================================-->
<footer id="footer" style="padding-top:0px !important">
    <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
    <!-- Remove the class "show-fixed" and "hide-fixed" to make the content always appears. -->
    <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->

	<div class="col-sm-10">
		<p class="pad-lft">&#0169; {{ date('Y') }} {{ \App\GeneralSetting::first()->site_name }}</p>
	</div>

</footer>
  <script>
    var titleOrig = document.title;
    
    Pusher.logToConsole = false;

    var pusher = new Pusher('4afd8459a29ae885ba85', {
      cluster: 'ap1',
      forceTLS: true
    });

    var channel = pusher.subscribe('my-channel');
    channel.bind('my-event', function(data) {
        var json = JSON.parse(JSON.stringify(data))
        //alert(json.message);
        document.getElementById('messageNotif').innerHTML = json.message;
        $('.notif').modal('show');
        $.titleAlert("New message!", {
            requireBlur:false,
            stopOnFocus:false,
            duration:0,
            interval:700
        });
    });
  </script>