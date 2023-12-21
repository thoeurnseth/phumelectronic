<?php
    
	if ( ! function_exists( 'biz_plasgate_add_menu_page_to_biz_solution' ) ):
		/**
		 * Adds Biz Plasgate Menu and Page
		 * Check if Biz Solution Menu and Page already exists otherwise add default menu and page
		 */
		function biz_plasgate_add_menu_page_to_biz_solution()
		{
			
			global $menu, $submenu;
			$main_menu_slug			= 'biz-solution-page';

			if( !isset($menu[ $main_menu_slug ]) ):

				$page_title = 'WordPress Biz Solution Plugins';
				$menu_title = 'Biz Solution';
				$capability = 'manage_options';
				$menu_slug  = $main_menu_slug;
				$function   = 'biz_solution_page';
				$icon_url   = plugins_url( 'biz-plasgate/resources/assets/img/biz-icon.png' );
				$position   = 4;

				add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );

			endif;
		}

		add_action('admin_menu', 'biz_plasgate_add_menu_page_to_biz_solution' , 1);

	endif;




    
	if ( ! function_exists( 'biz_plasgate_add_submenu_page_to_biz_solution' ) ):
		/**
		 * Adds Biz Plasgate Menu and Page
		 * Check if Biz Solution Menu and Page already exists otherwise add default menu and page
		 */
		function biz_plasgate_add_submenu_page_to_biz_solution()
		{
			
			global $menu, $submenu;
			$main_menu_slug			= 'biz-solution-page';
			$this_submenu_slug		= 'biz-plasgate-page';



			if ( !isset($submenu[ $main_menu_slug ]) ):

				$parent_slug		=	$main_menu_slug;
				$page_title			=	"Biz PlasGate WordPress Plugin";
				$menu_title			=	"Biz PlasGate";
				$capability			=	"manage_options";
				$menu_slug			=	$this_submenu_slug;
				$function			=	"biz_plasgate_option_page";
				$position			=	1;

				add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function, $position);

			endif;
		}

		add_action('admin_menu', 'biz_plasgate_add_submenu_page_to_biz_solution' , 2);

	endif;

	




	if ( ! function_exists( 'biz_solution_page' ) ):
		/**
		 * Load view for Biz Solution Page
		 */
		function biz_solution_page()
		{
			$option_page = BIZ_PLASGATE_PLUGIN_DIR . '/resources/views/biz_solution_option_page.php';

			if ( ! is_readable( $option_page ) )
			{
				?>
				<div class="notice notice-error">
					<p>
						<?php
						printf(
							esc_html__( '<strong>'.$option_page.'</strong> is missing! Please contact BizSolution for technical supports.', 'biz-solution' ),
							'<a href="' . esc_url( 'https://bizsolution.com.kh' ) . '" target="_blank" rel="noopener noreferrer">',
							'</a>'
						);
						?>
					</p>
				</div>
				<?php
			}
			else
			{
				require $option_page;
			}
		}
	endif;






	if ( ! function_exists( 'biz_plasgate_option_page' ) ):
		/**
		 * Load view for Biz PlasGate Page
		 */
		function biz_plasgate_option_page()
		{
			$option_page = BIZ_PLASGATE_PLUGIN_DIR . '/resources/views/biz_plasgate_option_page.php';

			if ( ! is_readable( $option_page ) )
			{
				?>
				<div class="notice notice-error">
					<p>
						<?php
						printf(
							esc_html__( $option_page.' is missing! Please contact BizSolution for technical supports.', 'biz-solution' ),
							'<a href="' . esc_url( 'https://bizsolution.com.kh' ) . '" target="_blank" rel="noopener noreferrer">',
							'</a>'
						);
						?>
					</p>
				</div>
				<?php
			}
			else
			{
				require $option_page;
			}
		}
	endif;
