<?php
/** Enable W3 Total Cache */
define('WP_CACHE', false); // Added by W3 Total Cache

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
define('DB_NAME', 'yappo');

/** Database username */
define('DB_USER', 'root');

/** Database password */
define('DB_PASSWORD', '');

/** Database hostname */
define('DB_HOST', 'localhost');

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
define( 'AUTH_KEY',         ')nNUt15f+&cn0g?jU2iE7w/+}6I;4Yr4v&FlwFB,h!;H{Oik:TaJ vy99^Mt]|:1' );
define( 'SECURE_AUTH_KEY',  '8gO@?K6*I6?2@z5;v2%*El^Oy>}=Dn3n$,{0~1K;&OuvRr<M_;Qj2[^1(|y-w$}x' );
define( 'LOGGED_IN_KEY',    'uFnlB@zVsMB-8G=I[H52`K!l8f/:r1zTe_LwGY}Wq8fS7^egwpcsFoBgJY!$k;F3' );
define( 'NONCE_KEY',        '`Sq_x4Vv)NytK2l/|:gxZ[;AhP-O;KA@I<5+JT?Djq>We5k(riC,d;BYu0AP+z/W' );
define( 'AUTH_SALT',        'iC#+rh6ELRibRwMQYttY?ZUmmhHK?z *_w8ltj~!(!0hkN1:!)ONGGDd_L?UeW@J' );
define( 'SECURE_AUTH_SALT', 'L__dY98Q4_]D+hNA(|t5&qy5B@J(]ive:ft/%]Ur|gR]<<d#T~KI2(nRMp|b-hUf' );
define( 'LOGGED_IN_SALT',   'Ti >9w8{dA]1`aeVVo230;KsLdV5-4b!L0rf3>_,zsRVCVW=hW>Tb&R2882FVxQu' );
define( 'NONCE_SALT',       'QTfLfmsxzS/ZSoA1i{*q~)uh:S9cHUlV&8yhv4}EdIItB%op{QkU,TzPCcODS2&M' );

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

define('WP_REDIS_SCHEME', 'unix');
define('WP_REDIS_PATH', '/home/vr488025/.system/redis.sock');
define('WP_CACHE_KEY_SALT', 'yappo');

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
define('UPLOADS', 'wp-content/uploads');
define('WP_PLUGIN_DIR', __DIR__ . "/wp-content/plugins");
define('WP_PLUGIN_URL', 'https://dev.yapposushi.com/wp-content/plugins');
define('WP_LANG_DIR', __DIR__ . "/wp-content/languages");
define('WP_HOME', 'https://dev.yapposushi.com');
define('WP_SITEURL', 'https://dev.yapposushi.com');
define('DISABLE_WP_CRON', false);
if ( ! defined( 'WP_ENVIRONMENT_TYPE' ) ) {
    define('WP_ENVIRONMENT_TYPE', 'staging');
}
define('MYSQL_CLIENT_FLAGS', MYSQLI_CLIENT_SSL | MYSQLI_CLIENT_SSL_DONT_VERIFY_SERVER_CERT);
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
