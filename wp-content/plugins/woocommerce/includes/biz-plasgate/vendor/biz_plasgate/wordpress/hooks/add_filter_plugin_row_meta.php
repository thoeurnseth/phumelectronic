<?php

	if ( ! function_exists( 'biz_plasgate_custom_plugin_row_meta' ) ):
		/**
		 * Adds the version of a package to the $jetpack_packages global array so that
		 * the autoloader is able to find it.
		 */
		function biz_plasgate_custom_plugin_row_meta( $links, $file )
		{
			if ( strpos( $file, 'biz-plasgate.php' ) !== false )
			{
				$new_links = array(
						'go-to-plugin' 	=> '<a style="background-color:#53a653;color:#fff;padding:3px 6px;border-radius:5px;" href="'. admin_url('admin.php?page=biz-plasgate-page') .'">Go to Plugin</a>',
						'doc' 			=> '<a style="background-color:#0c75bc;color:#fff;padding:3px 6px;border-radius:5px;" href="'. admin_url('admin.php?page=biz-plasgate-page') .'" target="_blank">Documentation</a>',
						'author' 		=> 'Developed by: <a style="color:#0c75bc;" href="http://bizsolution.com.kh/" target="_blank">Biz Solution Co., Ltd.</a>',
					);

				// If need merging, uncomment the code below
				// $links = array_merge( $links, $new_links );
				$links 	= $new_links;
			}
			
			return $links;
		}
		add_filter( 'plugin_row_meta', 'biz_plasgate_custom_plugin_row_meta', 10, 2 );
	endif;