<?php
/**
 * Includes the composer Autoloader used for packages and classes in the inc/ directory.
 *
 * @package BizSolution/BizPlasGate
 */

namespace BizSolution\BizPlasGate;

defined( 'ABSPATH' ) || exit;

/**
 * Autoloader class.
 *
 * @since 1.0.0
 */
class Autoloader
{

	/**
	 * Static-only class.
	 */
	private function __construct() {}

	/**
	 * Require the autoloader and return the result.
	 *
	 * If the autoloader is not present, let's log the failure and display a nice admin notice.
	 *
	 * @return boolean
	 */

	public static function init()
	{
		$autoload_files 	=	[
			'/mvc.php',
			'/vendor/autoload.php',
		];

		foreach( $autoload_files as $autoload_file )
		{
			$autoload = BIZ_PLASGATE_PLUGIN_DIR . $autoload_file;
	
			if ( ! is_readable( $autoload ) ) {
				self::missing_autoloader();
				return false;
			}

			require $autoload;
		}
	}

	/**
	 * If the autoloader is missing, add an admin notice.
	 */
	protected static function missing_autoloader()
	{
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG )
		{
			error_log(  // phpcs:ignore
				esc_html__( 'Your installation of Biz PlasGate is incomplete. Please contact BizSolution for technical supports.', 'bizsolution' )
			);
		}
		add_action( 'admin_notices',
			function() {
				?>
				<div class="notice notice-error">
					<p>
						<?php
							printf(
								esc_html__( 'Your installation of Biz PlasGate is incomplete. Please contact BizSolution for technical supports.', 'bizsolution' ),
								'<a href="' . esc_url( 'https://bizsolution.com.kh' ) . '" target="_blank" rel="noopener noreferrer">',
								'</a>'
							);
						?>
					</p>
				</div>
				<?php
			}
		);
	}
}