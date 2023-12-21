<?php


//Begin Really Simple SSL session cookie settings
@ini_set('session.cookie_httponly', true);
@ini_set('session.cookie_secure', true);
@ini_set('session.use_only_cookies', true);
//END Really Simple SSL
define('WP_AUTO_UPDATE_CORE', 'minor');// This setting is required to make sure that WordPress updates can be properly managed in WordPress Toolkit. Remove this line if this WordPress website is not managed by WordPress Toolkit anymore.
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */
$domain_name = $_SERVER['HTTP_HOST'];
$environment = '';
switch( $domain_name )
{
    case "prod-clone.phumelectronic.com":
        $environment = 'clone';
        break;
	case "staging.phumelectronic.com":
		$environment = 'staging';
	break;
	case "phumelectronic.com":
	case "phumelectronics.com":
		$environment = 'production';
	break;
	case "local.phumelectronic":
		$environment = "development2";
		break;
	default:
		$environment = 'development';
	break;
}
$DB_NAME	=	[
	"staging"		=>	"stg_phumelectronic_com",
	"production"	=>	"prod_clone_phumelectronic_com",
	"development"	=>	"prod_phumelectronic",
	"development2"	=>	"local_phumelectronic",
	"clone"	        =>	"prod_clone_v2_phumelectronic_com",
];
$DB_USER	=	[
	"staging"		=>	"stgphum3loonic",
	"production"	=>	"prod_clone_phumelectronic_com",
	"development"	=>	"root",
	"development2"	=>	"root",
	"clone"	        =>	"prod_clone_v2_phumelectronic_com",
];
$DB_PASSWORD	=	[
	"staging"		=>	"7fdjlMbfkcP%K0p2",
	"production"	=>	"0^M4om8h0",
	"development"	=>	"admin",
	"development2"	=>	"root",
	"clone"	        =>	"4G!qka013",
];
$DB_HOST	=	[
	"staging"		=>	"localhost",
	"production"	=>	"localhost:3306",
	"development"	=>	"localhost",
	"development2"	=>	"localhost",
	"clone"	        =>	"localhost",
];

$ODOO_URL = [    
	"staging"		=>	"http://124.248.186.204:8071",
    "production"	=>	"https://ggeargroup.com",
    "development"	=>	"http://124.248.186.204:8071",
    "development2"	=>	"http://124.248.186.204:8071",
    "clone"	        =>	"http://124.248.186.204:8071",
];

$ODOO_CLIENT_ID = [
    "staging"		=>	"VYAcJmhMi6Q6uFKtiUk8R28VVG8bWpkKJXni8T5E2u9k4EhBULTAZR2Qu2v0NoG6znRsu7kjBydRzhozjnd9hd01Di80vmpjA3B2m036l3q7ngz1WNANvOj3SWr33H1f",
    "production"	=>	"crjWAqRE9K6JsuRT6WPvwFNaZaAZM1aALGw38CI9iznFUf3mdD9fZxAf3fZfDDxr0uAyHTz7nuACvzaplduRU8920AUMDF9Gdh6HA338LNMzw3x6F1SmoITtQPgtSIF7",
    "development"	=>	"VYAcJmhMi6Q6uFKtiUk8R28VVG8bWpkKJXni8T5E2u9k4EhBULTAZR2Qu2v0NoG6znRsu7kjBydRzhozjnd9hd01Di80vmpjA3B2m036l3q7ngz1WNANvOj3SWr33H1f",
    "development2"	=>	"VYAcJmhMi6Q6uFKtiUk8R28VVG8bWpkKJXni8T5E2u9k4EhBULTAZR2Qu2v0NoG6znRsu7kjBydRzhozjnd9hd01Di80vmpjA3B2m036l3q7ngz1WNANvOj3SWr33H1f",
    "clone"	        =>	"VYAcJmhMi6Q6uFKtiUk8R28VVG8bWpkKJXni8T5E2u9k4EhBULTAZR2Qu2v0NoG6znRsu7kjBydRzhozjnd9hd01Di80vmpjA3B2m036l3q7ngz1WNANvOj3SWr33H1f",
];
	
$ODOO_CLIENT_SECRET = [
    "staging"		=>	"fQ9SqaqsQz774RCPv825TJKWHAw4wltyNQvGtFRgY6o0YeasovdEP5BlCekQglfF7aiZszj13XAKb5LZXv3Z14eUtyBSo09se4DC5zTA6iV6dZ2ZkIWU1NFaeKS232oD",
    "production"	=>	"ny5mD9Ejj1FMzSOjS1XbDPD6tAjwR0EAsT5Kr39kEFaGY7diOr8TMQKvWAGEpti6zdmRchBsRjAresIaAcfha0vd51Cydv5sM7hMItPQaQYBSBBeW0qgFKRi3BnQZY5P",
    "development"	=>	"fQ9SqaqsQz774RCPv825TJKWHAw4wltyNQvGtFRgY6o0YeasovdEP5BlCekQglfF7aiZszj13XAKb5LZXv3Z14eUtyBSo09se4DC5zTA6iV6dZ2ZkIWU1NFaeKS232oD",
    "development2"	=>	"fQ9SqaqsQz774RCPv825TJKWHAw4wltyNQvGtFRgY6o0YeasovdEP5BlCekQglfF7aiZszj13XAKb5LZXv3Z14eUtyBSo09se4DC5zTA6iV6dZ2ZkIWU1NFaeKS232oD",
    "clone"	        =>	"fQ9SqaqsQz774RCPv825TJKWHAw4wltyNQvGtFRgY6o0YeasovdEP5BlCekQglfF7aiZszj13XAKb5LZXv3Z14eUtyBSo09se4DC5zTA6iV6dZ2ZkIWU1NFaeKS232oD",
];

$ODOO_DB = [
    "staging"		=>	"03vfbaxydIXVoQtf0iRGtD4tXY25TDtWvYlCBHHUypXLYk1P8jLTDT3wnP0ovP6Kqfqq36avtcfkpiHuWl4J2H9wuHyCZzYVzU2ykPTJUzV3dSSA6SwRVqeKn19yzY0DU7iEhmIInlsjA2",
    "production"	=>	"FLcTAq3Uh0Kf2ndwPylypFpRUV7O0HGrF7k7FVuQQv0hWN3xTbqfUyD8RlW5dguNQhhkekcnaFDtnfgd0HMsgnKrgK7SUKRjF6sdRbCkhiRF6p5p7yjQ73xRQLSc9fRQMjCvfzqmKYy",
    "development"	=>	"03vfbaxydIXVoQtf0iRGtD4tXY25TDtWvYlCBHHUypXLYk1P8jLTDT3wnP0ovP6Kqfqq36avtcfkpiHuWl4J2H9wuHyCZzYVzU2ykPTJUzV3dSSA6SwRVqeKn19yzY0DU7iEhmIInlsjA2",
    "development2"	=>	"03vfbaxydIXVoQtf0iRGtD4tXY25TDtWvYlCBHHUypXLYk1P8jLTDT3wnP0ovP6Kqfqq36avtcfkpiHuWl4J2H9wuHyCZzYVzU2ykPTJUzV3dSSA6SwRVqeKn19yzY0DU7iEhmIInlsjA2",
    "clone"	        =>	"03vfbaxydIXVoQtf0iRGtD4tXY25TDtWvYlCBHHUypXLYk1P8jLTDT3wnP0ovP6Kqfqq36avtcfkpiHuWl4J2H9wuHyCZzYVzU2ykPTJUzV3dSSA6SwRVqeKn19yzY0DU7iEhmIInlsjA2",
];

$CONSUMER_KEY = [
    "staging"		=>	"ck_f21b96da04587ad423b3085c039a687dae835d26",
    "production"	=>	"ck_f1505fcb7ec7a21d2288b77637b66d4e03ff0549",
    "development"	=>	"ck_f21b96da04587ad423b3085c039a687dae835d26",
    "development2"	=>	"ck_8f48a5f50c844b4796f8b7248bb724b26a2265fc",
    "clone"	        =>	"ck_f1505fcb7ec7a21d2288b77637b66d4e03ff0549",
];

$CONSUMER_SECRET = [
    "staging"		=>	"cs_5a8100a77933e97aa0d494e6f509e1cc4a2120d8",
    "production"	=>	"cs_f6c5cc0ca335f9bb2d1bde04df4437d3fc15be8c",
    "development"	=>	"cs_5a8100a77933e97aa0d494e6f509e1cc4a2120d8",
    "development2"	=>	"cs_04fb826a93b2d2844937487fbf3e9489175face9",
    "clone"	        =>	"cs_f6c5cc0ca335f9bb2d1bde04df4437d3fc15be8c",
];

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', $DB_NAME[$environment] );
/** MySQL database username */
define( 'DB_USER', $DB_USER[$environment] );
/** MySQL database password */
define( 'DB_PASSWORD', $DB_PASSWORD[$environment] );
/** MySQL hostname */
define( 'DB_HOST', $DB_HOST[$environment] );
/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );
/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );
/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'WD8simp>rkZuNa3|8L+Tj)b:g8zt`k$P~n&mG,@IZB#zZa4&%WC2z)m-,b+W@gip' );
define( 'SECURE_AUTH_KEY',  'u=ffYoSOR2jzl/OgICA4r.*ee,hL,z7X`F!COA((iiS=JILpeh0^lt^r$Lf$pKAZ' );
define( 'LOGGED_IN_KEY',    '>ml#C>IH0bfzI_k49VVjcJLb44hkW> %gWTvxtUoYkPf{1}qpC?e7}^6)*ENAHzB' );
define( 'NONCE_KEY',        'C cEm=Nln MYBDdF[*FedsH-^->Fxz>`BtWV5*KE_Gv*z3qn_/&!Qpo=XNrTmYYU' );
define( 'AUTH_SALT',        'wD^,im.yKypi1%+#msOY7P/U.GM$9;4q`*;Bg&68VYd1/^>:n`%@/V]3zV0 ~2#X' );
define( 'SECURE_AUTH_SALT', 'u9I`gb>YL$i_[#w5vD<)oO}^8D6I*@^QNlkTf|;He? l?z)Y,$28Rm: EuPl}|@r' );
define( 'LOGGED_IN_SALT',   'qj7{RsI)u29a@Y~okRF:`~lTB{YUd@)XbRQIPv&b6TPlS%]Wg#9lVud}appDo?qU' );
define( 'NONCE_SALT',       '*f&TJCoYgw0_;(]vyI%AX5_@SO_S%?sNw#B<#ql+VTO@*E$:ubWQ)j3pVC(p*R7d' );
/**#@-*/

/*******************************
 ***** Odoo API Credentail *****
********************************/
// staging
//define( 'ODOO_URL',         	'https://ggeargroup.com');
//define( 'ODOO_CLIENT_ID',       'SdmopIQJCh7Yz046XYw47WYLclMvAisGRJeQApKAO2JY9D3x08ebZquM5sKmPGxZ2WraiMi6WHSDqYnL9Fe33OMF7tgcN04ElE96g5e8srs4LTErYl3F3jtDyua0B4UA');
//define( 'ODOO_CLIENT_SECRET',   'tUgQTW7B6JmAWUdjy9VNxgpU6kSOPYKgHA593HlEJa81rFvfXuAJyEctFJfBxIJNuy1oqaEXxGKyLsh7CVwePLt31V5K0IKo85Optw1z5ollG2XejMdD1J4FV0oWr1ir');
//define( 'ODOO_DB',         		'GL7t0mHYt6AH8e4r354QlQ47nyHQMCQquq5NtOimbcafeFPLnwACaC8VvnHHGW5vQhhkekcnaFDaFwrnkecvga27VOO4K4xOPB23If0A0JTDyHxLBE62X6pex7lxTinXU2CSt4HWzYZ4oFRk50TIjhot');
//define( 'CONSUMER_KEY',         		'ck_f21b96da04587ad423b3085c039a687dae835d26');
//define( 'CONSUMER_SECRET',         		'cs_5a8100a77933e97aa0d494e6f509e1cc4a2120d8');

// production
define( 'ODOO_URL',         	$ODOO_URL[$environment]);
define( 'ODOO_CLIENT_ID',       $ODOO_CLIENT_ID[$environment]);
define( 'ODOO_CLIENT_SECRET',   $ODOO_CLIENT_SECRET[$environment]);
define( 'ODOO_DB',         		$ODOO_DB[$environment]);

define( 'CONSUMER_KEY',         $CONSUMER_KEY[$environment]);
define( 'CONSUMER_SECRET',      $CONSUMER_SECRET[$environment]);

/*******************************
 ***** Odoo API Credentail *****
********************************/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'ph0m31e_';
/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false);
define( 'UPLOADS', 'wp-content/'.'uploads' );

define('CONCATENATE_SCRIPTS', false);

define('WP_MEMORY_LIMIT', '512M');

define('DISABLE_WP_CRON', true);


/* That's all, stop editing! Happy publishing. */
/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}
/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';


