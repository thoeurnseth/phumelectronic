<p>
	In order to send sms, it is required to fill in username, password and endpoint provided by PlasGate. <br> Please contact PlasGate for these credentials!
</p>
<form method="post" action="options.php">
	<?php settings_fields( 'biz_plasgate_credentials_settings' ); ?>
	<?php do_settings_sections( 'biz_plasgate_credentials_settings' ); ?>

	<table class="form-table">

		<tbody>
			<tr valign="top">
				<th scope="row" class="titledesc">
					<label for="biz_plasgate_username">PlasGate Username: <span style="color:red">*</span></label>
				</th>
				<td class="forminp forminp-select">
					<fieldset>
						<label for="biz_plasgate_username">
							<input name="biz_plasgate_username" id="biz_plasgate_username" type="text" class="" value="<?php echo get_option('biz_plasgate_username'); ?>">
						</label>
						<p class="description">Username is given by PlasGate, in order to have access to their server.</p>
					</fieldset>
				</td>
			</tr>


			<tr valign="top">
				<th scope="row" class="titledesc">
					<label for="biz_plasgate_password">PlasGate Password: <span style="color:red">*</span></label>
				</th>
				<td class="forminp forminp-select">
					<fieldset>
						<label for="biz_plasgate_password">
							<input name="biz_plasgate_password" id="biz_plasgate_password" type="text" class="" value="<?php echo get_option('biz_plasgate_password'); ?>">
						</label>
						<p class="description">Password is given by PlasGate, in order to have access to their server.</p>
					</fieldset>
				</td>
			</tr>


			<tr valign="top">
				<th scope="row" class="titledesc">
					<label for="biz_plasgate_endpoint">PlasGate Endpoint: <span style="color:red">*</span></label>
				</th>
				<td class="forminp forminp-select">
					<fieldset>
						<label for="biz_plasgate_endpoint">
							<textarea name="biz_plasgate_endpoint" id="biz_plasgate_endpoint"><?php echo get_option('biz_plasgate_endpoint'); ?></textarea>
						</label>
						<p class="description">Endpoint is given by PlasGate. It is an url to have the application curl from it.</p>
					</fieldset>
				</td>
			</tr>
		</tbody>
	</table>
	<?php submit_button(); ?>
</form>

<?php


?>