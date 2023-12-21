<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\Metro;
?>
<div class="icon-area-content notfication-area">
	<div class="notfication-product">
		<a href="<?php echo site_url() ?>/my-account/notification">
			<img width="20px" src="<?php echo get_stylesheet_directory_uri() ?>/assets/icons/notification.png">
		</a>
	</div>
</div>
<div class="icon-area-content favorite-area">
	<div class="favorite-product">
		<a href="<?php echo site_url() ?>/wishlist">
			<i class="flaticon-heart"></i>
		</a>
	</div>
</div>

<div class="icon-area-content langauge-area">
	<div class="switch-language">
		<ul class="i-nav">
			<li class="i-item">
				<a class="i-link active-lang lang"></a>
			</li>
		</ul>
		<div class="lang-wrapper">
			<?php
//				global $q_config;
//				qtranxf_generateLanguageSelectCode(array(
//					'type'   => 'custom',
//					'format' => '%n',
//					$q_config['language']
//				))
			?>
		</div>
	</div>
</div>

<!-- <div class="icon-area-content search-icon-area">
	<a href="#" class="popup_search"><i class="flaticon-search"></i></a>
	<div class="wrap-search">
			<form action="" method="post">
				<input type="text" class="form-control box" name="search_box" placeholder=" "><button type="submit" class="btn_search" name="btn_search">Search</button>
			</form>
	</div>	
</div> -->