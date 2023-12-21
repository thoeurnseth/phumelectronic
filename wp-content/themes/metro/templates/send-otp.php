<?php
/**
 * Template Name: Send OTP
 * 
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

?>
<?php get_header(); ?>
<div id="primary" class="content-area">
	<div style="text-align:center;">
		<form action="https://phumelectronic.com/verify-otp/" method="POST">
			<input name="firstname" type="text" value="Afril">
			<input type="submit" value="button">
		</form>
	</div>
</div>
<?php get_footer(); ?>