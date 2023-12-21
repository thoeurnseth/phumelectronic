<?php

	if ( ! function_exists( 'biz_plasgate_admin_enqueue_scripts' ) ):
		/**
		 * Adds the version of a package to the $jetpack_packages global array so that
		 * the autoloader is able to find it.
		 */
		function biz_plasgate_admin_enqueue_scripts($hook)
		{
			if( !in_array('biz-solution/index.php', apply_filters('active_plugins', get_option('active_plugins')))):
				if( in_array($hook, ["toplevel_page_biz-solution-page"] ) ):
					wp_register_style( 'biz-plasgate-simple-grid-css', plugins_url('biz-plasgate/resources/assets/css/simple-grid.css'), false, '1.0.2' );
					wp_enqueue_style( 'biz-plasgate-simple-grid-css' );
					
					wp_register_script( 'biz-plasgate-syntaxhighlighter-script', plugins_url('biz-plasgate/resources/assets/js/jquery.syntaxhighlighter.min.js'), array('jquery'), '1.0.1' );
					wp_enqueue_script( 'biz-plasgate-syntaxhighlighter-script' );
				endif;
			endif;

			if( in_array($hook, ["biz-solution_page_biz-plasgate-page"] ) ):
				wp_register_style( 'biz-plasgate-simple-grid-css', plugins_url('biz-plasgate/resources/assets/css/simple-grid.css'), false, '1.0.1' );
				wp_enqueue_style( 'biz-plasgate-simple-grid-css' );
			endif;
		}
		add_action( 'admin_enqueue_scripts', 'biz_plasgate_admin_enqueue_scripts' );
	endif;