<?php

	// if( in_array('application-passwords/application-passwords.php', apply_filters('active_plugins', get_option('active_plugins')))){ 
	// 	echo '<script>alert("asdf");</script>';
	// }

?>
<p>
	Below are basic policies where users can define by their own needs, leaving it blank is totally okay.
</p>
<form method="post" action="options.php">
	<?php settings_fields( 'biz_plasgate_otp_settings_settings' ); ?>
	<?php do_settings_sections( 'biz_plasgate_otp_settings_settings' ); ?>

	<table class="form-table">

		<tbody>


			<tr valign="top">
				<th scope="row" class="titledesc">
					<label for="biz_plasgate_disable_plasgate">Disable PlasGate:</label>
				</th>
				<td class="forminp forminp-select">
					<fieldset>
						<!-- <label for="biz_plasgate_disable_plasgate">
							<input name="biz_plasgate_disable_plasgate" id="biz_plasgate_disable_plasgate" type="checkbox" class="" value="1"> PlasGate is no longer available to send SMS.
						</label> -->
						<select name="biz_plasgate_disable_plasgate" id="biz_plasgate_disable_plasgate">

							<?php
								$values = [
									'enable'	=>	'Enable',
									'disable'	=>	'Disable'
								];
								$selected = get_option('biz_plasgate_disable_plasgate');

								foreach( $values as $key => $value ):
									$selected_elm = '';
									if($selected == $key):
										$selected_elm = 'selected="selected"';
									endif;
									echo '<option value="'. $key .'" '.$selected_elm.'>' . $value . '</option>';
								endforeach;
							?>

						</select>
						<p class="description">Leave it "Enable" to allow PlasGate keep sending sms.</p>
					</fieldset>
				</td>
			</tr>


			
			<tr valign="top">
				<th scope="row" class="titledesc">
					<label for="biz_plasgate_pin_code_digit_number">PIN Code Digit Number:</label>
				</th>
				<td class="forminp forminp-select">
					<fieldset>
						<label for="biz_plasgate_pin_code_digit_number">
							<input name="biz_plasgate_pin_code_digit_number" id="biz_plasgate_pin_code_digit_number" type="text" class="" value="<?=get_option('biz_plasgate_pin_code_digit_number')?>">
						</label>
						<p class="description">The number of digits of PIN Code whether 4 or 6.</p>
					</fieldset>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row" class="titledesc">
					<label for="biz_plasgate_interval">Interval (seconds):</label>
				</th>
				<td class="forminp forminp-select">
					<fieldset>
						<label for="biz_plasgate_interval">
							<input name="biz_plasgate_interval" id="biz_plasgate_interval" type="text" class="" value="<?=get_option('biz_plasgate_interval')?>">
						</label>
						<p class="description">The default interval per message, per user is 60 seconds.</p>
					</fieldset>
				</td>
			</tr>


			<tr valign="top">
				<th scope="row" class="titledesc">
					<label for="biz_plasgate_failed_attempt_limit">Failed Attempt Limit:</label>
				</th>
				<td class="forminp forminp-select">
					<fieldset>
						<label for="biz_plasgate_failed_attempt_limit">
							<input name="biz_plasgate_failed_attempt_limit" id="biz_plasgate_failed_attempt_limit" type="text" class="" value="<?=get_option('biz_plasgate_failed_attempt_limit')?>">
						</label>
						<p class="description">The number of failed attempt limits. Set 0 (sms) for unlimited!</p>
					</fieldset>
				</td>
			</tr>


			<tr valign="top">
				<th scope="row" class="titledesc">
					<label for="biz_plasgate_failed_attempt_period_of_suspension">Failed Attempt Period of Suspension:</label>
				</th>
				<td class="forminp forminp-select">
					<fieldset>
						<label for="biz_plasgate_failed_attempt_period_of_suspension">
							<input name="biz_plasgate_failed_attempt_period_of_suspension" id="biz_plasgate_failed_attempt_period_of_suspension" type="text" class="" value="<?php echo get_option('biz_plasgate_failed_attempt_period_of_suspension'); ?>"> 
						</label>
						<p class="description">The phone number will be suspended for the period of time (in days), that they already reach failed attempt limit. Set 0 (days) for unlimited!</p>
					</fieldset>
				</td>
			</tr>


			<tr valign="top">
				<th scope="row" class="titledesc">
					<label for="biz_plasgate_blocked_prefixes">Blocked Prefixes:</label>
				</th>
				<td class="forminp forminp-select">
					<fieldset>
						<label for="biz_plasgate_blocked_prefixes">
							<textarea name="biz_plasgate_blocked_prefixes" id="biz_plasgate_blocked_prefixes" col="120"><?php echo get_option('biz_plasgate_blocked_prefixes'); ?></textarea>
						</label>
						<p class="description">Please seperate every prefix by using comma (,) For eg: 016,098,096,015</p>
					</fieldset>
				</td>
			</tr>


			<tr valign="top">
				<th scope="row" class="titledesc">
					<label for="biz_plasgate_auth_plugin">Authentication:</label>
				</th>
				<td class="forminp forminp-select">
					<fieldset>
						<select name="biz_plasgate_auth_plugin" id="biz_plasgate_auth_plugin">

							<?php
								$values = [
									'no-auth'				=>	'No Auth',
									'basic-auth'			=>	'Basic Auth',
									// 'woocommerce'			=>	'WooCommerce Rest API',
									// 'application-passwords'	=>	'Application Passwords'
								];
								$selected = get_option('biz_plasgate_auth_plugin');

								foreach( $values as $key => $value ):
									$selected_elm = '';
									if($selected == $key):
										$selected_elm = 'selected="selected"';
									endif;
									echo '<option value="'. $key .'" '.$selected_elm.'>' . $value . '</option>';
								endforeach;
							?>

						</select>
						<p class="description">Please leave it selecting "No Auth", if you don't want to use "Basic Auth".</p>
					</fieldset>
				</td>
			</tr>

			<tr valign="top" class="auth_option basic_auth">
				<th scope="row" class="titledesc">
					<label for="biz_plasgate_php_auth_user">PHP_AUTH_USER:</label>
				</th>
				<td class="forminp forminp-select">
					<fieldset>
						<label for="biz_plasgate_php_auth_user">
							<input name="biz_plasgate_php_auth_user" id="biz_plasgate_php_auth_user" type="text" class="" value="<?php echo get_option('biz_plasgate_php_auth_user'); ?>"> 
						</label>
						<p class="description">Username for Basic Authentication.</p>
					</fieldset>
				</td>
			</tr>

			<tr valign="top" class="auth_option basic_auth">
				<th scope="row" class="titledesc">
					<label for="biz_plasgate_php_auth_pw">PHP_AUTH_PW:</label>
				</th>
				<td class="forminp forminp-select">
					<fieldset>
						<label for="biz_plasgate_php_auth_pw">
							<input name="biz_plasgate_php_auth_pw" id="biz_plasgate_php_auth_pw" type="text" class="" value="<?php echo get_option('biz_plasgate_php_auth_pw'); ?>"> 
						</label>
						<p class="description">Password for Basic Authentication. Please use md5() for requesting.</p>
					</fieldset>
				</td>
			</tr>



		</tbody>
	</table>
	<?php submit_button(); ?>
</form>

<script>
	window.jQuery = window.$ = jQuery;
	$(document).ready(function(){

		var showSelectedOption = function(){
			var selected = $("#biz_plasgate_auth_plugin").val();
			$(".auth_option").hide();
			switch( selected )
			{
				case "basic-auth":
					$(".basic_auth").show();
				break;
			}
		};

		showSelectedOption();

		$("#biz_plasgate_auth_plugin").on("change", function(){
			showSelectedOption();
		});
	});
</script>