<form method="POST" action="options.php">
	<?php settings_fields( 'biz_plasgate_message_template_settings' ); ?>
	<?php do_settings_sections( 'biz_plasgate_message_template_settings' ); ?>

	<table class="form-table">
		<tbody>

			<tr valign="top">
				<th scope="row" class="titledesc">
					<label for="biz_plasgate_template_sender_id">Alphanumeric Sender ID:</label>
				</th>
				<td class="forminp forminp-select">
					<fieldset>
						<label for="biz_plasgate_template_sender_id">
							<input name="biz_plasgate_template_sender_id" id="biz_plasgate_template_sender_id" type="text" class="" value="<?=get_option('biz_plasgate_template_sender_id')?>"> 
						</label>
						<p class="description">Alphanumeric Sender ID allows you to set your company name or brand as the Sender ID when sending one-way SMS messages to supported prefixes.</p>
					</fieldset>
				</td>
			</tr>


			<tr valign="top">
				<th scope="row" class="titledesc">
					<label for="biz_plasgate_template_content">Body (or Content):</label>
				</th>
				<td class="forminp forminp-select">
					<fieldset>
						<label for="biz_plasgate_template_content">
							<textarea col="120" name="biz_plasgate_template_content" id="biz_plasgate_template_content"><?=get_option('biz_plasgate_template_content')?></textarea>
						</label>
						<p class="description">
							SMS Content in which users will be receiving. Please use {{OTP}} for the random number.
							<br>
							<br>
							<a href="javascript:void(0);" id="reset-btn">Reset</a> default template.
						</p>
					</fieldset>
				</td>
			</tr>


		</tbody>
	</table>
	<?php submit_button(); ?>
</form>


<script>
	jQuery(document).ready(function(){
		jQuery("body").on("click", "#reset-btn",function(){
			jQuery('#biz_plasgate_template_content').val("The OTP is: {{OTP}}.");
		});
	});
</script>