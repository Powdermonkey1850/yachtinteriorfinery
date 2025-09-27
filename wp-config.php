<?php

define( 'WP_DEBUG', FALSE );
define( 'WP_DEBUG_LOG', FALSE );
define( 'WP_DEBUG_DISPLAY', false );

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'yif2025' );

/** Database username */
define( 'DB_USER', 'yif2025' );

/** Database password */
define( 'DB_PASSWORD', 'Rooklinkertook555!' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '(Sh<IO{EG_Hw*mlN:uzJj.2_%(.UK&+/{%}YuJt~.;-X8nV/E6P4gmM#)@)|-lec' );
define( 'SECURE_AUTH_KEY',  'bfE5_#1_97}s/_n|9:w)kCGgG+V2LE[VPL@0Y.$`y:Tj_u ]Y1BD9!UqN,!9w--C' );
define( 'LOGGED_IN_KEY',    'lweIUn ,kpmwn@ #Eukt%Vk5ond.bU@L ty]rr9:ZO/6! ,;yGR;,I-ZBRrlAX<v' );
define( 'NONCE_KEY',        'd>RW.z|L7p@!M3L)^T^v1hsc[)LJ,qx0zB{qj_-$g=jnF/9$RH%^L~2ui7Aa!<I;' );
define( 'AUTH_SALT',        'I`6s*p94bBv10Q0Nvk$l%{qk_VNTve-Nr]N4,EX$lk&nh>_q>nY`)]&Z,?X>SM2,' );
define( 'SECURE_AUTH_SALT', '@]XyFFKm@@h{OB^r|XhJX0p7C4R7JhEnCp5NeZ?m[k[@DG4*#l-}`*S-{E B<0F?' );
define( 'LOGGED_IN_SALT',   'L.?|Md3ilb6FNg;Oh|xp.HT_@P-Lxrs`kis(54`vs+~IJ7t0M^/p9YCEinMen2;t' );
define( 'NONCE_SALT',       '|pcNEdu-gd:8grF-U==^uN]#g/g0#%a.nQ)Y9^BNQ;2XfKJ#g9IS*dM=(pE!RfBv' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wp_';

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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
/* define( 'WP_DEBUG', false ); */

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
