<?php
	$tab = isset($_GET['tab']) ? $_GET['tab'] : 'otp-settings';
	$tabs = array(
		['name' => 'OTP Settings', 			'url' => 'otp-settings'],
		['name' => 'OTP SMS Template', 		'url' => 'otp-sms-template'],
		['name' => 'Test SMS',				'url' => 'test-sms'],
		// ['name' => 'Bulk SMS',				'url' => 'bulk-sms'],
		['name' => 'Credentials', 			'url' => 'credentials'],
		// ['name' => 'Blacklists', 			'url' => 'blacklists'],
		['name' => 'SMS History', 			'url' => 'sms-history'],
		['name' => 'Documentation', 		'url' => 'documentation'],
	);
?>
<div class="wrap">

	<div id="logo-container">
		<img src="<?=plugins_url('biz-plasgate/resources/assets/img/logo-biz.svg')?>">
	</div>
	 
    
 
 	<div class="container">
 		<div class="row">
 			<div class="col-12">

				<h2 style="font-size: 1.3em;"><?php echo esc_html( get_admin_page_title() ); ?></h2>	

	 			<nav class="nav-tab-wrapper">
	 				<?php foreach( $tabs as $tab_item ): ?>
	 					<?php
	 						$active = '';
	 						if( $tab_item['url'] == $tab ):
	 							$active = 'nav-tab-active ' . $tab . ' ' . $tab_item['url'];
	 						endif;
	 					?>
	 					<a href="<?php echo admin_url('admin.php?page=biz-plasgate-page&tab=' . $tab_item['url']); ?>" class="nav-tab <?=$active?>"><?=$tab_item['name']?></a>
	 				<?php endforeach; ?>
				</nav>


				<p style="font-size:13px; color: #000;">
	 				<?php foreach( $tabs as $tab_item ): ?>
						<?php
							$file = '';
							if( $tab_item['url'] == $tab ):
								$file = $tab_item['url'];
								break;
							endif;
						?>
					<?php endforeach; ?>
					<?php require('parts/biz_plasgate_option_page/' . $file . '.php'); ?>
				</p>


 			</div>
 		</div>
	</div>
 
</div>