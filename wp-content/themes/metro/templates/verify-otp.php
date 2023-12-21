<?php
/**
 * Template Name: Verify OTP
 * 
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */
	$firstname = $_POST['firstname'];

            $curl = curl_init();


            curl_setopt_array($curl, array(
	          CURLOPT_URL => "https://staging.toysnme.com.kh/wp-json/biz-plasgate/api/v2/send-otp",
	          CURLOPT_RETURNTRANSFER => true,
	          CURLOPT_ENCODING => "",
	          CURLOPT_MAXREDIRS => 10,
	          CURLOPT_TIMEOUT => 0,
	          CURLOPT_FOLLOWLOCATION => true,
	          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	          CURLOPT_CUSTOMREQUEST => "POST",
	          CURLOPT_POSTFIELDS => array('phone' => '015721137'),
	          // CURLOPT_POSTFIELDS => array('phone' => '095907222'),
	          CURLOPT_HTTPHEADER => array(
	            "Authorization: Basic dDB5c25tM2NvbHRkYjF6Ok9VVzZ5VXI2eVhnYWl0RWxKWVJoWmhwUnpLRGhXQ3JLMTFIVlVBdERGeUZ6S0tvQW9BT3BEd1Y1MURVOA==",
	            "Cookie: PHPSESSID=ki8cmn9ehb0gafiqhrb6jmj0g8"
	          ),
	        ));

            $response = curl_exec($curl);

            curl_close($curl);

?>
<?php get_header(); ?>
<div id="primary" class="content-area">
	<div style="text-align:center;">
		<form action="/verify-otp" method="POST">
		  	<input type="text"
		         
		         autocomplete="one-time-code"
		         
		         required>
			<input type="submit" value="button">
		</form>
	</div>
</div>
<script>
	if ('OTPCredential' in window) {
	  window.addEventListener('DOMContentLoaded', e => {
	    const input = document.querySelector('input[autocomplete="one-time-code"]');
	    if (!input) return;
	    // Cancel the Web OTP API if the form is submitted manually.
	    const ac = new AbortController();
	    const form = input.closest('form');
	    if (form) {
	      form.addEventListener('submit', e => {
	        // Cancel the Web OTP API.
	        ac.abort();
	      });
	    }
	    // Invoke the Web OTP API
	    navigator.credentials.get({
	      otp: { transport:['sms'] },
	      signal: ac.signal
	    }).then(otp => {
	      input.value = otp.code;
	      // alert(otp.code);
	      // Automatically submit the form when an OTP is obtained.
	      // if (form) form.submit();
	    }).catch(err => {
	      console.log(err);
	    });
	  });
	}
</script>
<?php get_footer(); ?>