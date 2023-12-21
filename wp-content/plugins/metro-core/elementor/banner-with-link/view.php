<?php
/**
 * This file can be overridden by copying it to yourtheme/elementor-custom/banner-with-link/view.php
 * 
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\Metro_Core;

$attr = '';

if ( !empty( $data['url']['url'] ) ) {
	$attr  = 'href="' . $data['url']['url'] . '"';
	$attr .= !empty( $data['url']['is_external'] ) ? ' target="_blank"' : '';
	$attr .= !empty( $data['url']['nofollow'] ) ? ' rel="nofollow"' : '';
}
?>
<div class="rt-el-banner-with-link">
	<a class="rtin-item" <?php echo $attr;?>>
		<?php if ( $data['linktext'] ): ?>
			<div class="rtin-btn-area"><div class="rtin-btn"><?php echo esc_html( $data['linktext'] );?></div></div>
		<?php endif; ?>		
	</a>
</div>