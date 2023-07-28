<?php
 // Added by WP Rocket

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'yappo' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', '127.0.0.1' );

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
define( 'AUTH_KEY',         'A/F{?ZO=.W172g;c/-a@:r*}lCO!Sg)X9:pj35ZU;0.B3]3{a+/r]rGi$|G_LHch' );
define( 'SECURE_AUTH_KEY',  '2cU{h9Qi3]xZ.t`}!p./NIRA</Jtcc[Fd<E2C,U[A:.Dg-QO6_i{>ACn5SgRu%Pc' );
define( 'LOGGED_IN_KEY',    'XKzM6#68xe|]G7o3o1mww@1X&g<g]WjST~vA!)P*a?Zy?;k2PlM!;]%yd?:`^=Ha' );
define( 'NONCE_KEY',        '{df *65:/e:_g=uv|<}e4}Z}?9U(v#fG5X1fGQ9}0XXsr(|JpyvrXptE>9mOLRl5' );
define( 'AUTH_SALT',        '=~Z5W%e=:-2g!2P{=w@cPr+`T/aung/:#~y[%6??(-?1TFGvuN%O~gve=6`FRgA1' );
define( 'SECURE_AUTH_SALT', '-?V?bhl<^^kc[n3&F0r[>{-*>O#SFDj^,,t_^2!8_KV|;qVH2n:><~>:^:&Y3!ib' );
define( 'LOGGED_IN_SALT',   '4j}A642AtPC-1z<gL3@+7>,TWIq_ H^-qhB&G-9#G9C3J7$4kum}q.A_c |U,iwt' );
define( 'NONCE_SALT',       ',s-vL1OF017&p[I~LO`p4DmqC#X_F4|{jp$SN=H=~%ikES;tVL9WmG*(Rn?4>,!M' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_'; // Changed by WP STAGING

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
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_DISPLAY', false );
define( 'WP_DEBUG_LOG', true );
error_reporting(1);
@ini_set('display_errors', 1);

define('WP_MEMORY_LIMIT', '1536M');
define( 'WP_MAX_MEMORY_LIMIT', '1536M' );

/* Add any custom values between this line and the "stop editing" line. */

//define('WP_REDIS_SCHEME', 'unix');
//define('WP_REDIS_PATH', '/home/vr488025/.system/redis.sock');
//define('WP_CACHE_KEY_SALT', 'yappo');

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
//define('UPLOADS', 'wp-content/uploads');
//define('WP_PLUGIN_DIR', __DIR__ . "/wp-content/plugins");
//define('WP_PLUGIN_URL', 'https://dev.yapposushi.com/wp-content/plugins');
//define('WP_LANG_DIR', __DIR__ . "/wp-content/languages");
//define('WP_HOME', 'https://dev.yapposushi.com');
//define('WP_SITEURL', 'https://dev.yapposushi.com');
//define('DISABLE_WP_CRON', false);
//if ( ! defined( 'WP_ENVIRONMENT_TYPE' ) ) {
//    define('WP_ENVIRONMENT_TYPE', 'staging');
//}
//define('MYSQL_CLIENT_FLAGS', MYSQLI_CLIENT_SSL | MYSQLI_CLIENT_SSL_DONT_VERIFY_SERVER_CERT);
//if ( ! defined( 'ABSPATH' ) ) {
//	define( 'ABSPATH', __DIR__ . '/' );
//}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
