<?php
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
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'crawler_test');

/** MySQL database username */
define('DB_USER', 'crawler_test');

/** MySQL database password */
define('DB_PASSWORD', 'sOLOBARIC2278!');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '~Gf+H9#Mk%+F+Vt!n(ifCy(*yhvu)&7G5qj<UQ@B>`X++RJ(jJpoF`:%wX>ne%Hj');
define('SECURE_AUTH_KEY',  'x#2K2um,M%OwziLkfc0$p4^/U-]9uG<xnUL!nK?lv,k-jbEc4}w^<,DSe?[n,=4[');
define('LOGGED_IN_KEY',    'GWC!(U.QI+(svm)K,H0BP}v^6O<Qr[x/f+{vI#Yc[DF(12PP|_y~18/f[9m,7U9:');
define('NONCE_KEY',        '_tO$%!6Fa?I#C^j}F|-}2d%g30bR)%U g.WKD@(^!Lzc+@&Nz7am$jF4&h*;W[?W');
define('AUTH_SALT',        'V>!IWUOa;[KYVb`#GH,3U(j`giL<!.|z}Nyade:.7~IE+pc*7Ah8M0Xe9tx/v)-3');
define('SECURE_AUTH_SALT', 'OD->x r(_aalRci6!YK_:*#<f_xEI61K%0}]X?px%:N/;<aQ{Z9/N4/Aztv;EQBl');
define('LOGGED_IN_SALT',   'J}##)om$^*~Z:z*<7A}t{.$-eb[5HP_5!Dzvf/+K00wrV~(&BDTn36C`<c{Tl0 c');
define('NONCE_SALT',       'c,7(+d75L(fD:0{&fFFpWMfg*h;KTO$?O?Eb./xk@NzsreqWKxDG;&46^l56>CF;');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', true );

// Enable Debug logging to the /wp-content/debug.log file
define( 'WP_DEBUG_LOG', true );

// Disable display of errors and warnings 
define( 'WP_DEBUG_DISPLAY', false );
@ini_set( 'display_errors', 0 );
/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
