<?php
/**
 * Includes the composer Autoloader used for packages and classes in the inc/ directory.
 *
 * @package BizSolution/BizOdooPusher
 */

namespace BizSolution\BizOdooPusher;

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
		$autoloader = BIZ_ODOO_PUSHER_PLUGIN_DIR . '/vendor/autoload_hooks.php';

		if ( ! is_readable( $autoloader ) ) {
			self::missing_autoloader();
			return false;
		}

		return require $autoloader;
	}

	/**
	 * If the autoloader is missing, add an admin notice.
	 */
	protected static function missing_autoloader()
	{
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG )
		{
			error_log(  // phpcs:ignore
				esc_html__( 'Your installation of Biz OdooPusher is incomplete. Please contact BizSolution for technical supports.', 'bizsolution' )
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