<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\Metro_Core;

$url   = urlencode( get_permalink() );
$title = urlencode( get_the_title() );

$defaults = array(
	'facebook' => array(
		'url'  => "http://www.facebook.com/sharer.php?u=$url",
		'icon' => 'fa-facebook',
	),
	'twitter'  => array(
		'url'  => "https://twitter.com/intent/tweet?source=$url&text=$title:$url",
		'icon' => 'fa-twitter'
	),
	'linkedin' => array(
		'url'  => "http://www.linkedin.com/shareArticle?mini=true&url=$url&title=$title",
		'icon' => 'fa-linkedin'
	),
	'pinterest'=> array(
		'url'  => "http://pinterest.com/pin/create/button/?url=$url&description=$title",
		'icon' => 'fa-pinterest'
	),
	'tumblr'   => array(
		'url'  => "http://www.tumblr.com/share?v=3&u=$url &quote=$title",
		'icon' => 'fa-tumblr'
	),
	'reddit'   => array(
		'url'  => "http://www.reddit.com/submit?url=$url&title=$title",
		'icon' => 'fa-reddit'
	),
	'vk'       => array(
		'url'  => "http://vkontakte.ru/share.php?url=$url",
		'icon' => 'fa-vk'
	),
);

foreach ( $sharer as $key => $value ) {
	if ( !$value ) {
		unset( $defaults[$key] );
	}
}

$sharers = apply_filters( 'rdtheme_social_sharing_icons', $defaults );
?>
<div class="post-social">
	<h3 class="rtin-title"><?php esc_html_e( 'Share:', 'metro-core' );?></h3>
	<div class="post-social-sharing">
		<?php foreach ( $sharers as $key => $sharer ): ?>
			<a href="<?php echo esc_url( $sharer['url'] );?>" target="_blank"><i class="fa <?php echo esc_attr( $sharer['icon'] );?>"></i></a>
		<?php endforeach; ?>
	</div>
</div>