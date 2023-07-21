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
define('DB_NAME', 'vr488025_dev');

/** Database username */
define('DB_USER', 'vr488025_dev');

/** Database password */
define('DB_PASSWORD', '7r;Sk^t8A7');

/** Database hostname */
define('DB_HOST', 'vr488025.mysql.tools');

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
define( 'AUTH_KEY',         '9zn1eU1GE,umMde&<*uQXu%hla )ay#qH+y/CGZsCQ&Sl$G{V;=bpt&B%b@CS_[f' );
define( 'SECURE_AUTH_KEY',  '8lY:C5|`6PzEW[uK-o)[NLZQ8!RL_!RoL_1iG,_efr0B/zla|:5O&^VbLo*o7C($' );
define( 'LOGGED_IN_KEY',    'kgG +o@I@*-`5=5~6,NrQUNc#>]e44r:jL0p=:OBO~(:P42]uACe$`/wyb6tJgKZ' );
define( 'NONCE_KEY',        'F/l2ju4agPLGe,e#X?p6Zn!.~B|C/0Fv)jX648)(nnC4_[{br@e*i;FWtTd1sPo;' );
define( 'AUTH_SALT',        'USY,*=J)Y==dYs%hf0|T5tr*-5Vg2OovKSt|{hxj,$E`Q?.>pNq5^+Vx|l*XRJug' );
define( 'SECURE_AUTH_SALT', '7aV2/+{qfDAn~TcY!AHm}AS>cpCAi$y6(Ap{iRvQ0(c2D4`k7o`vL> |i9f4+x{h' );
define( 'LOGGED_IN_SALT',   '+.@(Zn2{P{(3gG:QEec$lN:50a=G>^}Pp.tsK%FO^l=!6M:HY,$mKCUBjGK[z~:7' );
define( 'NONCE_SALT',       '-E(|8OgF174tIw^}HYwx(aYw[3o*&LXeSRNqrQ3IoEx$5}=cl|(OlAkBzt)A]s-!' );

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
