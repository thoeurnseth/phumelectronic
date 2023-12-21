<form method="POST" action="">
	<table class="form-table">
		<tbody>

			<tr valign="top">
				<th scope="row" class="titledesc">
					<label for="biz_plasgate_sender_id">Alphanumeric Sender ID:</label>
				</th>
				<td class="forminp forminp-select">
					<fieldset>
						<label for="biz_plasgate_sender_id">
							<input name="biz_plasgate_sender_id" id="biz_plasgate_sender_id" type="text" class="" value="Mr. Phum"> 
						</label>
						<p class="description">Alphanumeric Sender ID allows you to set your company name or brand as the Sender ID when sending one-way SMS messages to supported prefixes.</p>
					</fieldset>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row" class="titledesc">
					<label for="biz_plasgate_recipient">Recipient:</label>
				</th>
				<td class="forminp forminp-select">
					<fieldset>
						<label for="biz_plasgate_recipient">
							<input name="biz_plasgate_recipient" id="biz_plasgate_recipient" type="text" class="" value="85516"> 
						</label>
						<p class="description">Receiver's Phone Number that you want to test (only single phone).</p>
					</fieldset>
				</td>
			</tr>


			<tr valign="top">
				<th scope="row" class="titledesc">
					<label for="biz_plasgate_content">Body (or Content):</label>
				</th>
				<td class="forminp forminp-select">
					<fieldset>
						<label for="biz_plasgate_content">
							<textarea col="120" name="biz_plasgate_content" id="biz_plasgate_content">Testing Message.</textarea>
						</label>
						<p class="description">SMS Content for testing.</p>
					</fieldset>
				</td>
			</tr>


		</tbody>
	</table>
	<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="TEST"></p>
</form>

<?php

	if( isset($_POST['biz_plasgate_sender_id']) && isset($_POST['biz_plasgate_recipient']) && isset($_POST['biz_plasgate_content']) ):

		$send_id 		= $_POST['biz_plasgate_sender_id'];
		$recipient 		= $_POST['biz_plasgate_recipient'];
		$content 		= $_POST['biz_plasgate_content'];

		$biz_plasgate_username = get_option('biz_plasgate_username');
		$biz_plasgate_password = get_option('biz_plasgate_password');
		$biz_plasgate_endpoint = get_option('biz_plasgate_endpoint');
		echo '<h3>Console Log:</h3>';
		echo '<pre class="highlight">';
			echo \BizSolution\BizPlasGate\Classes\SMS::init($biz_plasgate_username, $biz_plasgate_password, $biz_plasgate_endpoint);
			echo \BizSolution\BizPlasGate\Classes\SMS::send_sms($send_id, $recipient, $content);
		echo '</pre>';
	else:

	endif;

?>