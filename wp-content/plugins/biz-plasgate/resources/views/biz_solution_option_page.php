<div class="wrap">

	<div id="logo-container">
		<img src="<?=plugins_url('biz-plasgate/resources/assets/img/logo-biz.svg')?>">
	</div>
 
</div>

<div class="container">
	<div class="row">
		<div class="col-12">

			<h2 style="font-size: 1.3em;"><?php echo esc_html( get_admin_page_title() ); ?></h2>	
			<p>PlasGate </p>
			
<pre class="highlight">
<?=site_url().'/wp-json/'.BIZ_PLASGATE_REST_URL.'/send-otp'?>
</pre>
<pre class="highlight">
<?=site_url().'/wp-json/'.BIZ_PLASGATE_REST_URL.'/verify-otp'?>
</pre>

		</div>
	</div>
</div>


<script type="text/javascript">
    window.jQuery = window.$ = jQuery;
	$(document).ready(function(){
        // $.SyntaxHighlighter.init();
	});
</script>